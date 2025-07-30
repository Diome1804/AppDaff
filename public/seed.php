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

    // Effacer les anciennes données
    $pdo->exec("DELETE FROM journal");
    $pdo->exec("DELETE FROM citoyen");

    // Insertion des citoyens de test avec CNI sénégalais (13 chiffres)
    $citoyens = [
        ['1234567890123', 'Diop', 'Amina', '1990-05-15', 'Dakar', 'cni_amina.jpg'],
        ['0987654321098', 'Sow', 'Ibrahim', '1985-11-22', 'Thiès', 'cni_ibrahim.jpg'],
        ['1122334455667', 'Kane', 'Fatou', '1995-03-08', 'Saint-Louis', 'cni_fatou.jpg'],
        ['2233445566778', 'Fall', 'Moussa', '1988-07-12', 'Kaolack', 'cni_moussa.jpg'],
        ['3344556677889', 'Ndiaye', 'Aissatou', '1992-12-03', 'Ziguinchor', 'cni_aissatou.jpg']
    ];

    $stmt = $pdo->prepare("INSERT INTO citoyen (cni, nom, prenom, date_naissance, lieu_naissance, photo_identite_url) VALUES (?, ?, ?, ?, ?, ?)");

    foreach ($citoyens as $citoyen) {
        $stmt->execute($citoyen);
    }

    // Insertion dans le journal avec les nouvelles CNI
    $journaux = [
        ['192.168.1.1', '1234567890123', 'succes'],
        ['192.168.1.2', '0987654321098', 'error'],
        ['192.168.1.4', '1122334455667', 'succes'],
        ['192.168.1.5', '2233445566778', 'succes'],
        ['192.168.1.6', '3344556677889', 'error'],
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
