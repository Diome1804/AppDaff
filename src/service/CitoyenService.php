<?php

namespace Src\Service;

use App\Core\App;
use Src\Repository\CitoyenRepository;
use Src\Repository\JournalRepository;
use Src\Entity\Citoyen;
use Src\Entity\Statut;

class CitoyenService
{
    private CitoyenRepository $citoyenRepo;
    private JournalRepository $journalRepo;
    private CloudService $cloudService;
    private static CitoyenService|null $instance = null;

    public static function getInstance(): CitoyenService
    {
        if (self::$instance === null) {
            self::$instance = new CitoyenService();
        }
        return self::$instance;
    }

    public function __construct()
    {
        $this->citoyenRepo = App::getDependency('repository', 'citoyenRepo');
        $this->journalRepo = App::getDependency('repository', 'journalRepo');
        $this->cloudService = App::getDependency('services', 'cloudServ');
    }

    /**
     * Recherche un citoyen par NCI avec journalisation automatique
     * (Méthode principale pour l'API AppDAF)
     */
    public function rechercherParNci(string $nci, string $ip, string $localisation): array
    {
        $citoyen = $this->citoyenRepo->findByNci($nci);
        
        // Journalisation obligatoire
        $statut = $citoyen ? Statut::SUCCESS : Statut::ERROR;
        $this->journalRepo->logRecherche($nci, $ip, $localisation, $statut);

        if ($citoyen) {
            // Récupération de l'URL Cloudinary de la carte d'identité
            $photoUrl = $this->cloudService->getCarteIdentiteUrl($nci);
            if ($photoUrl) {
                $citoyen->setPhotoIdentiteUrl($photoUrl);
            }

            return [
                'data' => [
                    'nci' => $citoyen->getCni(),
                    'nom' => $citoyen->getNom(),
                    'prenom' => $citoyen->getPrenom(),
                    'dateNaissance' => $citoyen->getDateNaissance(),
                    'lieuNaissance' => $citoyen->getLieuNaissance(),
                    'photoIdentiteUrl' => $citoyen->getPhotoIdentiteUrl()
                ],
                'statut' => 'success',
                'code' => 200,
                'message' => 'Le numéro de carte d\'identité a été retrouvé'
            ];
        }

        return [
            'data' => null,
            'statut' => 'error',
            'code' => 404,
            'message' => 'Le numéro de carte d\'identité non retrouvé'
        ];
    }

    /**
     * Crée un nouveau citoyen avec upload de photo vers Cloudinary
     */
    public function creerCitoyen(array $data, $photoFile = null): bool
    {
        if ($photoFile) {
            $photoUrl = $this->cloudService->uploadCarteIdentite($photoFile, $data['cni']);
            $data['photoIdentiteUrl'] = $photoUrl ?? '';
        }

        return $this->citoyenRepo->insert($data);
    }
}
