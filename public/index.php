<?php

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
