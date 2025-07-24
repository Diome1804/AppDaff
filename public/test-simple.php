<?php

// Test ultra simple pour vérifier que PHP fonctionne sur Render
header('Content-Type: application/json');
http_response_code(200);

$response = [
    'status' => 'success',
    'message' => 'PHP fonctionne sur Render',
    'timestamp' => date('Y-m-d H:i:s'),
    'php_version' => PHP_VERSION,
    'method' => $_SERVER['REQUEST_METHOD'] ?? 'GET',
    'url' => $_SERVER['REQUEST_URI'] ?? '/',
    'headers' => []
];

// Ajouter quelques headers utiles
foreach ($_SERVER as $key => $value) {
    if (strpos($key, 'HTTP_') === 0) {
        $header = str_replace('HTTP_', '', $key);
        $header = str_replace('_', '-', $header);
        $response['headers'][$header] = $value;
    }
}

echo json_encode($response, JSON_PRETTY_PRINT);
exit;
