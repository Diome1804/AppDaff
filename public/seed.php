<?php
try {
    require_once __DIR__ . '/../vendor/autoload.php';
    require_once __DIR__ . '/../app/config/env.php';

    $pdo = new PDO(
        dsn,
        DB_USER, 
        DB_PASSWORD,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

    // Insertion des citoyens de test
    $citoyens = [
        ['1234567890', 'Diop', 'Amina', '1990-05-15', 'Dakar', 'cni_amina.jpg'],
        ['0987654321', 'Sow', 'Ibrahim', '1985-11-22', 'Thiès', 'cni_ibrahim.jpg'],
        ['1122334455', 'Kane', 'Fatou', '1995-03-08', 'Saint-Louis', 'cni_fatou.jpg']
    ];

    $stmt = $pdo->prepare("INSERT INTO citoyen (cni, nom, prenom, date_naissance, lieu_naissance, photo_identite_url) VALUES (?, ?, ?, ?, ?, ?) ON CONFLICT (cni) DO NOTHING");

    foreach ($citoyens as $citoyen) {
        $stmt->execute($citoyen);
    }

    // Insertion dans le journal
    $journaux = [
        ['192.168.1.1', '1234567890', 'succes'],
        ['192.168.1.2', '0987654321', 'error'],
        ['192.168.1.4', '1122334455', 'succes'],
    ];

    $stmtJournal = $pdo->prepare("INSERT INTO journal (ip_adresse, cni, statut, localisation) VALUES (?, ?, ?, 'Dakar, Sénégal')");

    foreach ($journaux as $journal) {
        $stmtJournal->execute($journal);
    }

    echo json_encode([
        'success' => true,
        'message' => 'Données insérées avec succès!'
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erreur lors du seeding',
        'error' => $e->getMessage()
    ]);
}
?>
