# AppDAF - Déploiement sur Render

Date: 2025-07-22
Version: Fixed Dockerfile with PHP CLI server

## Changements:
- Correction Dockerfile (FROM php:8.3-cli)
- Serveur PHP intégré sur port 8000
- Suppression Apache et .htaccess
- Configuration PostgreSQL
- Interface de test disponible sur /test

## Test URLs:
- API: /api/citoyen/rechercher 
- Test: /test
