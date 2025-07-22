<?php

namespace Src\Repository;
use App\Core\Abstract\AbstractRepository;
use Src\Entity\Citoyen;
use PDO;

class CitoyenRepository extends AbstractRepository{

    private string $table = 'citoyen';

    private static CitoyenRepository|null $instance = null;

    public static function getInstance():CitoyenRepository{
        if(self::$instance == null){
            self::$instance = new CitoyenRepository();
        }
        return self::$instance;
    }

    public function __construct(){
        parent::__construct();
    }

     public function selectAll(): array
     {
         $sql = "SELECT * FROM {$this->table}";
         $stmt = $this->pdo->query($sql);
         $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
         
         return array_map(fn($row) => Citoyen::toObject($row), $results);
     }

     public function insert(array $data): bool
     {
         $sql = "INSERT INTO {$this->table} (nom, prenom, cni, date_naissance, lieu_naissance, photo_identite_url) 
                 VALUES (:nom, :prenom, :cni, :date_naissance, :lieu_naissance, :photo_identite_url)";
         
         $stmt = $this->pdo->prepare($sql);
         return $stmt->execute($data);
     }

     public function update(): void
     {
         // À implémenter selon besoins
     }

     public function delete(): void
     {
         // À implémenter selon besoins
     }

     public function selectById(int $id): ?Citoyen
     {
         $sql = "SELECT * FROM {$this->table} WHERE id = :id";
         $stmt = $this->pdo->prepare($sql);
         $stmt->execute(['id' => $id]);
         
         $result = $stmt->fetch(PDO::FETCH_ASSOC);
         return $result ? Citoyen::toObject($result) : null;
     }

     public function selectBy(array $filter): array
     {
         $conditions = [];
         $params = [];
         
         foreach ($filter as $key => $value) {
             $conditions[] = "{$key} = :{$key}";
             $params[$key] = $value;
         }
         
         $sql = "SELECT * FROM {$this->table} WHERE " . implode(' AND ', $conditions);
         $stmt = $this->pdo->prepare($sql);
         $stmt->execute($params);
         
         $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
         return array_map(fn($row) => Citoyen::toObject($row), $results);
     }

     /**
      * Méthode clé pour l'API AppDAF - recherche par NCI
      */
     public function findByNci(string $nci): ?Citoyen
     {
         $sql = "SELECT * FROM {$this->table} WHERE cni = :cni";
         $stmt = $this->pdo->prepare($sql);
         $stmt->execute(['cni' => $nci]);
         
         $result = $stmt->fetch(PDO::FETCH_ASSOC);
         return $result ? Citoyen::toObject($result) : null;
     }

}