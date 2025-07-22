<?php
echo "<h1>Debug Router</h1>";

echo "<h2>REQUEST_URI:</h2>";
echo "<pre>" . ($_SERVER['REQUEST_URI'] ?? 'NON DÉFINI') . "</pre>";

echo "<h2>Parsed URI:</h2>";
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$parsedUri = parse_url($requestUri);
$currentUri = rtrim($parsedUri['path'] ?? '/', '/') ?: '/';
echo "<pre>$currentUri</pre>";

echo "<h2>Routes disponibles:</h2>";
require_once '../route/route.web.php';
echo "<pre>";
foreach ($routes as $route => $config) {
    echo "$route => {$config['controller']}::{$config['method']}\n";
}
echo "</pre>";

echo "<h2>Route trouvée?</h2>";
if (isset($routes[$currentUri])) {
    echo "<p style='color: green'>✅ Route '$currentUri' trouvée!</p>";
    echo "<pre>" . print_r($routes[$currentUri], true) . "</pre>";
} else {
    echo "<p style='color: red'>❌ Route '$currentUri' NON trouvée!</p>";
}
?>
