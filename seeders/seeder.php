<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

try {
    $database_url = $_ENV['DATABASE_URL'] ?? $_ENV['dsn'];
    
    // Parser l'URL PostgreSQL
    $url = parse_url($database_url);
    $pdo_dsn = sprintf(
        "pgsql:host=%s;port=%d;dbname=%s",
        $url['host'],
        $url['port'] ?? 5432,
        ltrim($url['path'], '/')
    );
    $user = $url['user'] ?? '';
    $password = $url['pass'] ?? '';
    
    $pdo = new PDO($pdo_dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connexion réussie à la base de données\n";
    } catch (PDOException $e) {
            die("Connexion échouée : " . $e->getMessage());
    }

try {
    $pdo->beginTransaction();

    // 0. Vider les tables existantes
    echo "Suppression des données existantes...\n";
    $pdo->exec("TRUNCATE TABLE journal, citoyen RESTART IDENTITY CASCADE");

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
        ['1234567890123', 'Diop', 'Amina', '1990-05-15', 'Dakar', 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=400&h=400&fit=crop&crop=face'],
        ['0987654321098', 'Sow', 'Ibrahim', '1985-11-22', 'Thiès', 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&h=400&fit=crop&crop=face'],
        ['1122334455667', 'Kane', 'Fatou', '1995-03-08', 'Saint-Louis', 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=400&h=400&fit=crop&crop=face']
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