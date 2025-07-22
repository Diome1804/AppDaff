<?php 

use Src\Controller\CitoyenController;

$routes = [
    '/' => [
        'controller' => CitoyenController::class, 
        'method' => 'index'
    ],
    '/api/citoyen/rechercher' => [
        'controller' => CitoyenController::class,
        'method' => 'rechercher'
    ],
    '/api/citoyen' => [
        'controller' => CitoyenController::class,
        'method' => 'store'
    ],
    '/test' => [
        'controller' => CitoyenController::class,
        'method' => 'test'
    ]
];