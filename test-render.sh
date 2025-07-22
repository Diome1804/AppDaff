#!/bin/bash

# Script pour tester l'API AppDAF sur Render
API_URL="https://appdaff-zwqf.onrender.com"

echo "🧪 Test API AppDAF sur Render"
echo "URL: $API_URL"
echo "=================================="

echo -e "\n1️⃣ Test page d'accueil:"
curl -s "$API_URL/"

echo -e "\n\n2️⃣ Test recherche citoyen SUCCESS (NCI: 1234567890):"
curl -s -X POST "$API_URL/api/citoyen/rechercher" \
  -H "Content-Type: application/json" \
  -d '{"nci": "1234567890"}' | jq '.'

echo -e "\n3️⃣ Test recherche citoyen ERROR (NCI inexistant):"
curl -s -X POST "$API_URL/api/citoyen/rechercher" \
  -H "Content-Type: application/json" \
  -d '{"nci": "9999999999999"}' | jq '.'

echo -e "\n4️⃣ Test validation (NCI trop court):"
curl -s -X POST "$API_URL/api/citoyen/rechercher" \
  -H "Content-Type: application/json" \
  -d '{"nci": "123"}' | jq '.'

echo -e "\n✅ Tests terminés !"
