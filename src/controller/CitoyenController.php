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

        // Validation du NCI (13 chiffres pour le Sénégal)
        $rules = [
            'nci' => ['required', 'string', ['minLength', 13], ['maxLength', 13]]
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
      
    }
    
//     public function test()
//     {
//         $filePath = __DIR__ . '/../../public/test.html';
//         if (file_exists($filePath)) {
//             $testHtml = file_get_contents($filePath);
//             echo $testHtml;
//         } else {
//             // Fallback vers l'interface directement intégrée
//             echo '<!DOCTYPE html>
// <html lang="fr">
// <head>
//     <meta charset="UTF-8">
//     <meta name="viewport" content="width=device-width, initial-scale=1.0">
//     <title>Test API AppDAF</title>
//     <style>
//         body { font-family: Arial, sans-serif; margin: 40px; }
//         .container { max-width: 800px; margin: 0 auto; }
//         .form-group { margin-bottom: 20px; }
//         label { display: block; margin-bottom: 5px; font-weight: bold; }
//         input, button { padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
//         button { background: #007bff; color: white; cursor: pointer; }
//         .result { margin-top: 20px; padding: 15px; border-radius: 4px; }
//         .success { background: #d4edda; color: #155724; }
//         .error { background: #f8d7da; color: #721c24; }
//     </style>
// </head>
// <body>
//     <div class="container">
//         <h1>🏛️ Test API AppDAF</h1>
        
//         <h2>🔍 Rechercher un citoyen par NCI</h2>
//         <div class="form-group">
//             <input type="text" id="nci" placeholder="Entrez le NCI (13 chiffres)" value="1234567890123">
//             <button onclick="rechercherCitoyen()">Rechercher</button>
//         </div>
//         <div id="result-recherche"></div>
        
//         <h2>➕ Créer un nouveau citoyen</h2>
//         <div class="form-group">
//             <input type="text" id="nom" placeholder="Nom" value="Test">
//             <input type="text" id="prenom" placeholder="Prénom" value="User">
//             <input type="text" id="cni" placeholder="CNI" value="9999999999">
//             <input type="date" id="dateNaissance" value="1990-01-01">
//             <input type="text" id="lieuNaissance" placeholder="Lieu de naissance" value="Dakar">
//             <button onclick="creerCitoyen()">Créer</button>
//         </div>
//         <div id="result-creation"></div>
//     </div>

//     <script>
//         async function rechercherCitoyen() {
//             const nci = document.getElementById("nci").value;
//             const resultDiv = document.getElementById("result-recherche");
            
//             try {
//                 const response = await fetch("/api/citoyen/rechercher", {
//                     method: "POST",
//                     headers: { "Content-Type": "application/json" },
//                     body: JSON.stringify({ nci })
//                 });
                
//                 const data = await response.json();
                
//                 if (data.statut === "success") {
//                     resultDiv.innerHTML = `<div class="result success">
//                         <h3>✅ Citoyen trouvé !</h3>
//                         <p><strong>NCI:</strong> ${data.data.nci}</p>
//                         <p><strong>Nom:</strong> ${data.data.nom}</p>
//                         <p><strong>Prénom:</strong> ${data.data.prenom}</p>
//                         <p><strong>Date de naissance:</strong> ${data.data.dateNaissance}</p>
//                         <p><strong>Lieu de naissance:</strong> ${data.data.lieuNaissance}</p>
//                         <p><strong>Message:</strong> ${data.message}</p>
//                     </div>`;
//                 } else {
//                     resultDiv.innerHTML = `<div class="result error">❌ ${data.message}</div>`;
//                 }
//             } catch (error) {
//                 resultDiv.innerHTML = `<div class="result error">❌ Erreur: ${error.message}</div>`;
//             }
//         }
        
//         async function creerCitoyen() {
//             const data = {
//                 nom: document.getElementById("nom").value,
//                 prenom: document.getElementById("prenom").value,
//                 cni: document.getElementById("cni").value,
//                 dateNaissance: document.getElementById("dateNaissance").value,
//                 lieuNaissance: document.getElementById("lieuNaissance").value
//             };
            
//             const resultDiv = document.getElementById("result-creation");
            
//             try {
//                 const response = await fetch("/api/citoyen", {
//                     method: "POST",
//                     headers: { "Content-Type": "application/json" },
//                     body: JSON.stringify(data)
//                 });
                
//                 const result = await response.json();
                
//                 if (result.statut === "success") {
//                     resultDiv.innerHTML = `<div class="result success">✅ ${result.message}</div>`;
//                 } else {
//                     resultDiv.innerHTML = `<div class="result error">❌ ${result.message}</div>`;
//                 }
//             } catch (error) {
//                 resultDiv.innerHTML = `<div class="result error">❌ Erreur: ${error.message}</div>`;
//             }
//         }
//     </script>
// </body>
// </html>';
//         }
//     }
    
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