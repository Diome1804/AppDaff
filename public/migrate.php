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

    // Création des ENUMS si non existants
    $pdo->exec("DO $$
    BEGIN
        IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'statut_enum') THEN
            CREATE TYPE statut_enum AS ENUM ('succes', 'error');
        END IF;
    END$$;");

    // Table: citoyen
    $pdo->exec("CREATE TABLE IF NOT EXISTS citoyen (
        id SERIAL PRIMARY KEY,
        cni VARCHAR(20) NOT NULL UNIQUE,
        nom VARCHAR(100) NOT NULL,
        prenom VARCHAR(100) NOT NULL,
        date_naissance DATE NOT NULL,
        lieu_naissance VARCHAR(100) NOT NULL,
        photo_identite_url TEXT NOT NULL
    );");

    // Table: journal
    $pdo->exec("CREATE TABLE IF NOT EXISTS journal (
        id SERIAL PRIMARY KEY,
        ip_adresse VARCHAR(50) NOT NULL,
        date_recherche TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        cni VARCHAR(20) NOT NULL,
        localisation VARCHAR(100) NOT NULL,
        statut VARCHAR(20) NOT NULL
        );");

    echo json_encode([
        'success' => true,
        'message' => 'Tables créées avec succès!'
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erreur lors du seeding',
        'error' => $e->getMessage()
    ]);
}
?>
