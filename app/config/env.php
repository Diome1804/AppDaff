<?php

use Dotenv\Dotenv;


$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();


//ici on defini les constantes qu on va utiliser dans notre application
define('APP_URL', $_ENV['APP_URL'] ?? 'http://localhost:8000');

// Construction du DSN depuis les variables Render ou fallback
if (isset($_ENV['DB_HOST']) && isset($_ENV['DB_NAME'])) {
    $host = $_ENV['DB_HOST'];
    $dbname = $_ENV['DB_NAME'];
    $user = $_ENV['DB_USERNAME'] ?? $_ENV['DB_USER'] ?? '';
    $password = $_ENV['DB_PASSWORLD'] ?? $_ENV['DB_PASSWORD'] ?? '';
    $dsn_constructed = "pgsql:host=$host;port=5432;dbname=$dbname";
    define('dsn', $dsn_constructed);
    define('DB_USER', $user);
    define('DB_PASSWORD', $password);
} else {
    define('DB_USER', $_ENV['DB_USER'] ?? '');
    define('DB_PASSWORD', $_ENV['DB_PASSWORD'] ?? '');
    define('dsn', $_ENV['DATABASE_URL'] ?? $_ENV['dsn']);
}

// Constantes pour Cloudinary
define('CLOUDINARY_CLOUD_NAME', $_ENV['CLOUDINARY_CLOUD_NAME'] ?? '');
define('CLOUDINARY_API_KEY', $_ENV['CLOUDINARY_API_KEY'] ?? '');
define('CLOUDINARY_API_SECRET', $_ENV['CLOUDINARY_API_SECRET'] ?? '');
