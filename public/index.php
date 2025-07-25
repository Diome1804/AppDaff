<?php

// Headers CORS pour permettre l'accès depuis d'autres domaines
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Gérer les requêtes OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Désactiver l'affichage des erreurs pour ne pas polluer le JSON
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

// Gérer les erreurs de façon propre
set_error_handler(function($severity, $message, $file, $line) {
    if (strpos($_SERVER['REQUEST_URI'] ?? '', '/api/') === 0) {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Erreur serveur',
            'error' => 'Internal server error'
        ]);
        exit();
    }
    return false; // Permet aux erreurs normales de s'afficher pour les pages HTML
});

require_once "../vendor/autoload.php";
require_once "../app/config/bootstrap.php";
require_once '../route/route.web.php';

use App\Core\Router;

Router::resolve($routes);
