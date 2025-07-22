#!/bin/bash

# Tests API AppDAF avec cURL
BASE_URL="http://localhost:8081"

echo "=== Tests API AppDAF ==="

echo -e "\n1. Test création citoyen:"
curl -X POST "$BASE_URL/api/citoyen" \
  -H "Content-Type: application/json" \
  -d '{
    "nom": "Diome",
    "prenom": "Abdoulaye", 
    "cni": "1234567890123",
    "dateNaissance": "1990-05-15",
    "lieuNaissance": "Dakar",
    "photoIdentiteUrl": "https://example.com/photo.jpg"
  }' | jq '.'

echo -e "\n2. Test recherche SUCCESS:"
curl -X POST "$BASE_URL/api/citoyen/rechercher" \
  -H "Content-Type: application/json" \
  -d '{"nci": "1234567890123"}' | jq '.'

echo -e "\n3. Test recherche ERROR (NCI inexistant):"
curl -X POST "$BASE_URL/api/citoyen/rechercher" \
  -H "Content-Type: application/json" \
  -d '{"nci": "9999999999999"}' | jq '.'

echo -e "\n4. Test validation (NCI trop court):"
curl -X POST "$BASE_URL/api/citoyen/rechercher" \
  -H "Content-Type: application/json" \
  -d '{"nci": "123"}' | jq '.'

echo -e "\n=== Fin des tests ==="
