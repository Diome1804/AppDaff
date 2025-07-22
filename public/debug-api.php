<?php
try {
    require_once "../vendor/autoload.php";
    require_once "../app/config/bootstrap.php";
    
    $citoyenService = \App\Core\App::getDependency('services', 'citoyenServ');
    $result = $citoyenService->rechercherParNci('1234567890', '127.0.0.1', 'Test');
    
    header('Content-Type: application/json');
    echo json_encode([
        'debug' => 'success',
        'result' => $result
    ]);
    
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode([
        'debug' => 'error',
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
}
?>
