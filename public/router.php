<?php
// Router pour le serveur PHP intégré
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$parsedUri = parse_url($requestUri);
$path = rtrim($parsedUri['path'] ?? '/', '/') ?: '/';

// Si c'est un fichier statique qui existe, le servir
if ($path !== '/' && file_exists(__DIR__ . $path)) {
    return false; // Laisse PHP servir le fichier
}

// Sinon, rediriger vers index.php
$_SERVER['REQUEST_URI'] = $path;
require_once 'index.php';
?>
