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
            return;
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
            return;
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
            return;
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
            return;
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
            return;
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
        $testFile = __DIR__ . '/../../public/test.html';
        
        if (file_exists($testFile)) {
            $testHtml = file_get_contents($testFile);
            echo $testHtml;
        } else {
            // Fallback: créer une page de test simple
            echo '<!DOCTYPE html>
<html>
<head>
    <title>Test API AppDAF</title>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial; max-width: 800px; margin: 0 auto; padding: 20px; }
        .form-group { margin: 15px 0; }
        input, button { padding: 10px; margin: 5px; }
        .success { background: #d4edda; color: #155724; padding: 10px; }
        .error { background: #f8d7da; color: #721c24; padding: 10px; }
    </style>
</head>
<body>
    <h1>🔍 Test API AppDAF</h1>
    
    <h2>Rechercher un citoyen par NCI</h2>
    <div class="form-group">
        <input type="text" id="nci" placeholder="Entrez le NCI (ex: 1234567890)" />
        <button onclick="rechercher()">Rechercher</button>
    </div>
    <div id="result"></div>
    
    <h2>Créer un nouveau citoyen</h2>
    <div class="form-group">
        <input type="text" id="nom" placeholder="Nom" />
        <input type="text" id="prenom" placeholder="Prénom" />
        <input type="text" id="cni" placeholder="CNI" />
        <input type="date" id="dateNaissance" />
        <input type="text" id="lieuNaissance" placeholder="Lieu de naissance" />
        <button onclick="creer()">Créer</button>
    </div>
    <div id="createResult"></div>
    
    <h2>📊 Citoyens de test disponibles</h2>
    <ul>
        <li><strong>1234567890</strong> - Amina Diop (née le 1990-05-15 à Dakar)</li>
        <li><strong>0987654321</strong> - Ibrahim Sow (né le 1985-11-22 à Thiès)</li>
        <li><strong>1122334455</strong> - Fatou Kane (née le 1995-03-08 à Saint-Louis)</li>
    </ul>
    
    <script>
        async function rechercher() {
            const nci = document.getElementById("nci").value;
            const resultDiv = document.getElementById("result");
            
            try {
                const response = await fetch("/api/citoyen/rechercher", {
                    method: "POST",
                    headers: {"Content-Type": "application/json"},
                    body: JSON.stringify({nci})
                });
                
                const data = await response.json();
                
                if (data.statut === "success") {
                    const c = data.data.citoyen;
                    resultDiv.innerHTML = `<div class="success">
                        <h3>✅ Citoyen trouvé !</h3>
                        <p><strong>NCI:</strong> ${c.cni}</p>
                        <p><strong>Nom:</strong> ${c.nom}</p>
                        <p><strong>Prénom:</strong> ${c.prenom}</p>
                        <p><strong>Date de naissance:</strong> ${c.date_naissance}</p>
                        <p><strong>Lieu de naissance:</strong> ${c.lieu_naissance}</p>
                        <p><strong>Photo:</strong> ${data.data.urlPhoto}</p>
                        <p><strong>Message:</strong> ${data.message}</p>
                    </div>`;
                } else {
                    resultDiv.innerHTML = `<div class="error">❌ ${data.message}</div>`;
                }
            } catch (error) {
                resultDiv.innerHTML = `<div class="error">❌ Erreur: ${error.message}</div>`;
            }
        }
        
        async function creer() {
            const nom = document.getElementById("nom").value;
            const prenom = document.getElementById("prenom").value;
            const cni = document.getElementById("cni").value;
            const dateNaissance = document.getElementById("dateNaissance").value;
            const lieuNaissance = document.getElementById("lieuNaissance").value;
            const resultDiv = document.getElementById("createResult");
            
            try {
                const response = await fetch("/api/citoyen", {
                    method: "POST",
                    headers: {"Content-Type": "application/json"},
                    body: JSON.stringify({nom, prenom, cni, dateNaissance, lieuNaissance})
                });
                
                const data = await response.json();
                
                if (data.statut === "success") {
                    resultDiv.innerHTML = `<div class="success">✅ ${data.message}</div>`;
                } else {
                    resultDiv.innerHTML = `<div class="error">❌ ${data.message}</div>`;
                }
            } catch (error) {
                resultDiv.innerHTML = `<div class="error">❌ Erreur: ${error.message}</div>`;
            }
        }
    </script>
</body>
</html>';
        }
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