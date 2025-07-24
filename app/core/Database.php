<?php

namespace App\Core;

use \PDO;
use \PDOException;

class Database{
    
    private $connection;
    private  static $instance = null;

      private function __construct() {
        try {
            // Parser l'URL PostgreSQL
            $url = parse_url(dsn);
            $pdo_dsn = sprintf(
                "pgsql:host=%s;port=%d;dbname=%s",
                $url['host'],
                $url['port'] ?? 5432,
                ltrim($url['path'], '/')
            );
            $user = $url['user'] ?? '';
            $password = $url['pass'] ?? '';
            
            $this->connection = new PDO(
              $pdo_dsn,
              $user,
              $password,
              [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
              ]
              );
        }catch(PDOException $e){
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }

    public static function getInstance(){
        if(self::$instance === null){
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection():PDO{
        return $this->connection;
    }
}