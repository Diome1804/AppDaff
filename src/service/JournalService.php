<?php

namespace Src\Service;

use App\Core\App;
use Src\Repository\JournalRepository;
use Src\Entity\Statut;

class JournalService
{
    private JournalRepository $journalRepo;
    private static JournalService|null $instance = null;

    public static function getInstance(): JournalService
    {
        if (self::$instance === null) {
            self::$instance = new JournalService();
        }
        return self::$instance;
    }

    public function __construct()
    {
        $this->journalRepo = App::getDependency('repository', 'journalRepo');
    }

    /**
     * Enregistre une recherche dans le journal
     */
    public function logRecherche(string $nci, string $ip, string $localisation, Statut $statut): bool
    {
        return $this->journalRepo->logRecherche($nci, $ip, $localisation, $statut);
    }

    /**
     * Récupère l'historique des recherches
     */
    public function getHistorique(array $filter = []): array
    {
        return empty($filter) ? 
            $this->journalRepo->selectAll() : 
            $this->journalRepo->selectBy($filter);
    }
}
