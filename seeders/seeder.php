<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

try {
    $dsn = $_ENV['DATABASE_URL'] ?? $_ENV['dsn'] ?? "{$_ENV['DB_DRIVER']}:host={$_ENV['DB_HOST']};port={$_ENV['DB_PORT']};dbname={$_ENV['DB_NAME']}";
    if (isset($_ENV['DATABASE_URL'])) {
        $pdo = new PDO($dsn);
    } else {
        $pdo = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
    }
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connexion réussie à la base de données\n";
    } catch (PDOException $e) {
            die("Connexion échouée : " . $e->getMessage());
    }

try {
    $pdo->beginTransaction();

    // 1. Création des ENUMS
    echo "Création des types ENUM...\n";
    $pdo->exec("DO $$
    BEGIN
        IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'statut_enum') THEN
            CREATE TYPE statut_enum AS ENUM ('success', 'error');
        END IF;
    END$$;");

    // 2. Table Citoyen
    echo "Insertion des données dans la table Citoyen...\n";
    $citoyens = [
        ['1234567890123', 'Diop', 'Amina', '1990-05-15', 'Dakar', 'cni_amina.jpg'],
        ['0987654321098', 'Sow', 'Ibrahim', '1985-11-22', 'Thiès', 'cni_ibrahim.jpg'],
        ['1122334455667', 'Kane', 'Fatou', '1995-03-08', 'Saint-Louis', 'cni_fatou.jpg']
    ];

    $stmtCitoyen = $pdo->prepare("INSERT INTO citoyen (cni, nom, prenom, date_naissance, lieu_naissance, photo_identite_url) VALUES (?, ?, ?, ?, ?, ?)");

    foreach ($citoyens as $citoyen) {
        $stmtCitoyen->execute($citoyen);
        echo "Citoyen {$citoyen[2]} {$citoyen[1]} inséré\n";
    }

    // 3. Table journal
    echo "\nInsertion des données dans la table journal...\n";
    $journaux = [
        ['192.168.1.1', '1234567890123', 'success'],
        ['192.168.1.2', '0987654321098', 'error'],
        ['192.168.1.4', '1122334455667', 'success'],
        ['192.168.1.5', '1234567890123', 'success'],
    ];

    $stmtJournal = $pdo->prepare("INSERT INTO journal (ip_adresse, cni, statut, localisation) VALUES (?, ?, ?, 'Dakar, Sénégal')");

    foreach ($journaux as $journal) {
        $stmtJournal->execute($journal);
        echo "Entrée journal pour CNI {$journal[1]} insérée\n";
    }

    $pdo->commit();

    echo "\nRécapitulatif final:\n";
    echo "- Types ENUM créés\n";
    echo "- " . count($citoyens) . " citoyens insérés\n";
    echo "- " . count($journaux) . " entrées de journal insérées\n";

} catch (PDOException $e) {
    $pdo->rollBack();
    die("Erreur lors de l'insertion des données : " . $e->getMessage());
}