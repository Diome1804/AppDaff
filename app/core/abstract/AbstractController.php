<?php

namespace App\Core\Abstract;
use App\Core\App;
use App\Core\Session;

abstract class AbstractController extends Session{
 
     protected $session;
     private static AbstractController|null $instance = null;

    public static function getInstance(){
        if(self::$instance === null){
            self::$instance = new static();
        }
        return self::$instance;
    }

    public function __construct(){
        $this->session = App::getDependency('core', 'session');
    }


    abstract public function index();
    abstract public function create();
    abstract public function store();
    abstract public function edit();
    abstract public function show();

    /**
     * Retourne une réponse JSON
     */
    protected function jsonResponse($data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

}