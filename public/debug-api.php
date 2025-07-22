<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    require_once "../vendor/autoload.php";
    require_once "../app/config/bootstrap.php";
    
    // Test base de données
    $db = \App\Core\Database::getInstance();
    $conn = $db->getConnection();
    
    // Test service
    $citoyenService = \App\Core\App::getDependency('services', 'citoyenServ');
    
    // Test recherche simple
    $result = $citoyenService->rechercherParNci('1234567890', '127.0.0.1', 'Test');
    
    header('Content-Type: application/json');
    echo json_encode([
        'debug' => 'success',
        'result' => $result,
        'steps' => [
            'autoload' => 'OK',
            'bootstrap' => 'OK', 
            'database' => 'OK',
            'service' => 'OK',
            'recherche' => 'OK'
        ]
    ]);
    
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode([
        'debug' => 'error',
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString()
    ]);
}
?>
