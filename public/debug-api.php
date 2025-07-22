<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Debug API:\n";

try {
    require_once "../vendor/autoload.php";
    echo "✅ Autoload OK\n";
    
    require_once "../app/config/bootstrap.php";
    echo "✅ Bootstrap OK\n";
    
    // Test base de données
    use App\Core\Database;
    $db = Database::getInstance();
    $conn = $db->getConnection();
    echo "✅ Database OK\n";
    
    // Test service
    use App\Core\App;
    $citoyenService = App::getDependency('services', 'citoyenServ');
    echo "✅ Service OK\n";
    
    // Test recherche simple
    $result = $citoyenService->rechercherParNci('1234567890', '127.0.0.1', 'Test');
    echo "✅ Recherche OK\n";
    
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
