<?php

namespace Src\Controller;

use App\Core\Abstract\AbstractController;
use App\Core\App;
use App\Core\Session;
use App\Core\Validator;

class CitoyenController extends AbstractController
{
    private $citoyenService;
    private $validator;

    public function __construct()
    {
        parent::__construct();
        $this->citoyenService = App::getDependency('services', 'citoyenServ');
        $this->validator = App::getDependency('core', 'validator');
    }

    /**
     * API Endpoint : POST /citoyen/rechercher
     * Recherche un citoyen par NCI
     */
    public function rechercher()
    {
        // Vérifier que c'est une requête POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse([
                'data' => null,
                'statut' => 'error',
                'code' => 405,
                'message' => 'Méthode non autorisée'
            ], 405);
        }

        // Récupérer les données JSON
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            $this->jsonResponse([
                'data' => null,
                'statut' => 'error', 
                'code' => 400,
                'message' => 'Données JSON invalides'
            ], 400);
        }

        // Validation du NCI
        $rules = [
            'nci' => ['required', 'string', ['minLength', 10]]
        ];

        if (!$this->validator->validate($input, $rules)) {
            $this->jsonResponse([
                'data' => null,
                'statut' => 'error',
                'code' => 400,
                'message' => 'Données invalides',
                'errors' => Validator::getErrors()
            ], 400);
        }

        // Récupération des informations de la requête
        $nci = $input['nci'];
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $localisation = $this->getLocalisation($ip);

        // Recherche du citoyen
        $result = $this->citoyenService->rechercherParNci($nci, $ip, $localisation);
        
        $this->jsonResponse($result, $result['code']);
    }

    /**
     * Endpoint pour créer un citoyen (pour tests/seeding)
     */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse([
                'data' => null,
                'statut' => 'error',
                'code' => 405,
                'message' => 'Méthode non autorisée'
            ], 405);
        }

        $input = json_decode(file_get_contents('php://input'), true);
        
        $rules = [
            'nom' => ['required', 'string'],
            'prenom' => ['required', 'string'],
            'cni' => ['required', 'string'],
            'dateNaissance' => ['required', 'string'],
            'lieuNaissance' => ['required', 'string']
        ];

        if (!$this->validator->validate($input, $rules)) {
            $this->jsonResponse([
                'data' => null,
                'statut' => 'error',
                'code' => 400,
                'message' => 'Données invalides',
                'errors' => Validator::getErrors()
            ], 400);
        }

        $success = $this->citoyenService->creerCitoyen($input);
        
        if ($success) {
            $this->jsonResponse([
                'data' => $input,
                'statut' => 'success',
                'code' => 201,
                'message' => 'Citoyen créé avec succès'
            ], 201);
        } else {
            $this->jsonResponse([
                'data' => null,
                'statut' => 'error',
                'code' => 500,
                'message' => 'Erreur lors de la création'
            ], 500);
        }
    }

    // Méthodes obligatoires de AbstractController
    public function index() 
    {
        echo "<h1>API AppDAF</h1><p>Utilisez <a href='/test'>la page de test</a> pour tester l'API.</p>";
    }
    
    public function test()
    {
        $testHtml = file_get_contents(__DIR__ . '/../../public/test.html');
        echo $testHtml;
    }
    
    public function create() {}
    public function show() {}
    public function edit() {}

    /**
     * Récupère la localisation approximative basée sur l'IP
     */
    private function getLocalisation(string $ip): string
    {
        // Pour l'instant, retourner une valeur par défaut
        // À l'avenir, on pourrait intégrer un service de géolocalisation
        return 'Dakar, Sénégal';
    }
}