<?php

namespace Src\Repository;
use App\Core\Abstract\AbstractRepository;
use Src\Entity\Journal;
use Src\Entity\Statut;
use PDO;

class JournalRepository extends AbstractRepository{

    private string $table = 'journal';

    private static JournalRepository|null $instance = null;

    public static function getInstance():JournalRepository{
        if(self::$instance == null){
            self::$instance = new JournalRepository();
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
         
         return array_map(fn($row) => Journal::toObject($row), $results);
     }

     public function insert(array $data): bool
     {
         $sql = "INSERT INTO {$this->table} (cni, ip_adresse, localisation, date_recherche, statut) 
                 VALUES (:cni, :ip_adresse, :localisation, :date_recherche, :statut)";
         
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

     public function selectById(int $id): void
     {
         // À implémenter selon besoins
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
         return array_map(fn($row) => Journal::toObject($row), $results);
     }

     /**
      * Méthode spécialisée pour enregistrer une recherche (journalisation obligatoire)
      */
     public function logRecherche(string $nci, string $ip, string $localisation, Statut $statut): bool
     {
         $data = [
             'cni' => $nci,
             'ip_adresse' => $ip,
             'localisation' => $localisation,
             'date_recherche' => date('Y-m-d H:i:s'),
             'statut' => $statut->value
         ];
         
         return $this->insert($data);
     }

}