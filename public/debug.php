<?php

// Page de diagnostic pour Render
header('Content-Type: text/html; charset=utf-8');

echo "<!DOCTYPE html>
<html>
<head>
    <title>Debug API AppDAF</title>
    <style>
        body { font-family: monospace; margin: 20px; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
        pre { background: #f5f5f5; padding: 10px; border-left: 3px solid #ccc; }
    </style>
</head>
<body>";

echo "<h1>🔍 Debug API AppDAF sur Render</h1>";

try {
    require_once '../vendor/autoload.php';
    require_once '../app/config/env.php';
    
    echo "<h2>✅ Autoload et Config OK</h2>";

    // Test Database
    echo "<h3>📊 Base de Données</h3>";
    try {
        $db = \App\Core\Database::getInstance();
        $connection = $db->getConnection();
        $stmt = $connection->query("SELECT COUNT(*) as count FROM citoyen");
        $result = $stmt->fetch();
        echo "<div class='success'>✅ Connexion DB OK - " . $result['count'] . " citoyens</div>";
    } catch (Exception $e) {
        echo "<div class='error'>❌ DB Error: " . $e->getMessage() . "</div>";
    }

    // Test Controller
    echo "<h3>🎮 Controller</h3>";
    try {
        $controller = \App\Core\App::getDependency('controller', 'citoyenController');
        echo "<div class='success'>✅ CitoyenController: " . get_class($controller) . "</div>";
    } catch (Exception $e) {
        echo "<div class='error'>❌ Controller Error: " . $e->getMessage() . "</div>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }

    // Test API Endpoint
    echo "<h3>🚀 Test API Recherche</h3>";
    try {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        
        $citoyenService = \App\Core\App::getDependency('services', 'citoyenServ');
        $result = $citoyenService->rechercherParNci('2001567890123', '127.0.0.1', 'Test Render');
        
        echo "<div class='success'>✅ API Test OK</div>";
        echo "<pre>" . json_encode($result, JSON_PRETTY_PRINT) . "</pre>";
        
    } catch (Exception $e) {
        echo "<div class='error'>❌ API Error: " . $e->getMessage() . "</div>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }

    // Test Session Fix
    echo "<h3>🔧 Test Session Fix</h3>";
    try {
        $session = \App\Core\Session::getInstance();
        echo "<div class='success'>✅ Session OK (pas de warning)</div>";
    } catch (Exception $e) {
        echo "<div class='error'>❌ Session Error: " . $e->getMessage() . "</div>";
    }

    echo "<h3>📋 Informations Système</h3>";
    echo "<div>PHP Version: " . PHP_VERSION . "</div>";
    echo "<div>DSN: " . (defined('dsn') ? '✅ Défini' : '❌ Manquant') . "</div>";
    echo "<div>Cloudinary: " . (defined('CLOUDINARY_CLOUD_NAME') ? '✅ Défini' : '❌ Manquant') . "</div>";

} catch (Exception $e) {
    echo "<div class='error'>";
    echo "<h2>❌ Erreur Fatale</h2>";
    echo "<p>Message: " . $e->getMessage() . "</p>";
    echo "<p>Fichier: " . $e->getFile() . ":" . $e->getLine() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
    echo "</div>";
}

echo "
<h3>🛠️ Actions Recommandées</h3>
<ol>
    <li>Si tout est ✅, le problème vient du déploiement</li>
    <li>Vérifiez que vos changements sont commitées et pushées</li>
    <li>Redéployez manuellement sur Render si nécessaire</li>
    <li>Testez avec l'endpoint: <code>POST /api/citoyen/rechercher</code></li>
</ol>

<h3>📱 Test Direct</h3>
<form method='POST' action='/api/citoyen/rechercher' style='margin: 20px 0;'>
    <p>
        <label>CNI (13 chiffres):</label><br>
        <input type='text' name='nci' value='2001567890123' style='padding: 5px; width: 200px;'><br><br>
        <button type='submit' style='padding: 10px 20px;'>🔍 Tester API</button>
    </p>
</form>

</body>
</html>";
