<?php
// Router pour le serveur intégré PHP

$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$parsedUri = parse_url($requestUri);
$path = $parsedUri['path'] ?? '/';

// Si c'est un fichier statique existant, le servir directement
if ($path !== '/' && file_exists(__DIR__ . $path)) {
    return false; // Laisser le serveur intégré servir le fichier
}

// Sinon, rediriger vers index.php pour le routage
require_once __DIR__ . '/index.php';
?>
