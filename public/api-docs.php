<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentation API AppDAF</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; line-height: 1.6; }
        .container { max-width: 1000px; margin: 0 auto; }
        .endpoint { background: #f8f9fa; padding: 20px; margin: 20px 0; border-radius: 8px; }
        .method { background: #28a745; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px; }
        .url { font-family: monospace; background: #e9ecef; padding: 8px; border-radius: 4px; }
        pre { background: #f8f9fa; padding: 15px; border-radius: 4px; overflow-x: auto; }
        .response { background: #d1ecf1; padding: 15px; border-radius: 4px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>📚 Documentation API AppDAF</h1>
        <p><strong>Base URL:</strong> <code>https://appdaff-zwqf.onrender.com</code></p>
        
        <div class="endpoint">
            <h2>🔍 Rechercher un citoyen par CNI</h2>
            <p><span class="method">POST</span> <span class="url">/api/citoyen/rechercher</span></p>
            
            <h3>Paramètres (JSON)</h3>
            <pre>{
  "nci": "1234567890123"  // CNI de 13 chiffres (obligatoire)
}</pre>

            <h3>Réponse en cas de succès</h3>
            <div class="response">
<pre>{
  "data": {
    "nci": "1234567890123",
    "nom": "Diop",
    "prenom": "Amina",
    "dateNaissance": "1990-05-15",
    "lieuNaissance": "Dakar",
    "photoIdentiteUrl": "https://via.placeholder.com/400x300?text=Carte+ID+1234567890123"
  },
  "statut": "success",
  "code": 200,
  "message": "Le numéro de carte d'identité a été retrouvé"
}</pre>
            </div>

            <h3>Réponse en cas d'erreur</h3>
            <div class="response">
<pre>{
  "data": null,
  "statut": "error", 
  "code": 404,
  "message": "Citoyen non trouvé"
}</pre>
            </div>

            <h3>Exemple d'utilisation JavaScript</h3>
            <pre>
async function verifierCNI(cni) {
    try {
        const response = await fetch('https://appdaff-zwqf.onrender.com/api/citoyen/rechercher', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ nci: cni })
        });
        
        const data = await response.json();
        
        if (data.statut === 'success') {
            console.log('Citoyen trouvé:', data.data);
            // Utilisez data.data.nom, data.data.prenom, etc.
        } else {
            console.log('Erreur:', data.message);
        }
    } catch (error) {
        console.error('Erreur réseau:', error);
    }
}

// Utilisation
verifierCNI('1234567890123');
            </pre>
        </div>

        <div class="endpoint">
            <h2>📋 CNI de test disponibles</h2>
            <ul>
                <li><strong>1234567890123</strong> - Amina Diop</li>
                <li><strong>0987654321098</strong> - Ibrahim Sow</li>
                <li><strong>1122334455667</strong> - Fatou Kane</li>
            </ul>
        </div>

        <div class="endpoint">
            <h2>⚠️ Gestion des erreurs</h2>
            <ul>
                <li><strong>400:</strong> Données invalides (CNI manquant ou format incorrect)</li>
                <li><strong>404:</strong> Citoyen non trouvé</li>
                <li><strong>405:</strong> Méthode non autorisée (utilisez POST)</li>
                <li><strong>500:</strong> Erreur serveur</li>
            </ul>
        </div>
    </div>
</body>
</html>
