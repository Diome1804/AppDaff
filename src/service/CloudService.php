<?php

namespace Src\Service;

use Cloudinary\Cloudinary;

class CloudService
{
    private ?Cloudinary $cloudinary;
    private static CloudService|null $instance = null;

    public static function getInstance(): CloudService
    {
        if (self::$instance === null) {
            self::$instance = new CloudService();
        }
        return self::$instance;
    }

    public function __construct()
    {
        // Utiliser les constantes définies dans env.php
        $cloudName = CLOUDINARY_CLOUD_NAME;
        $apiKey = CLOUDINARY_API_KEY;
        $apiSecret = CLOUDINARY_API_SECRET;

        if (!empty($cloudName) && !empty($apiKey) && !empty($apiSecret)) {
            try {
                $this->cloudinary = new Cloudinary([
                    'cloud' => [
                        'cloud_name' => $cloudName,
                        'api_key'    => $apiKey,
                        'api_secret' => $apiSecret,
                    ],
                    'url' => [
                        'secure' => true
                    ]
                ]);
            } catch (\Exception $e) {
                error_log("Erreur configuration Cloudinary: " . $e->getMessage());
                $this->cloudinary = null;
            }
        } else {
            error_log("Configuration Cloudinary manquante - Mode test activé");
            $this->cloudinary = null;
        }
    }

    /**
     * Upload une image et retourne l'URL sécurisée
     */
    public function upload($filePath): ?string
    {
        if (!$this->cloudinary) {
            return 'https://via.placeholder.com/400x300?text=Photo+Test'; // URL de test
        }
        
        try {
            $result = $this->cloudinary->uploadApi()->upload($filePath);
            return $result['secure_url'] ?? null;
        } catch (\Exception $e) {
            // Log ou gestion d'erreur ici
            return null;
        }
    }

    /**
     * Upload spécifique pour les cartes d'identité avec dossier organisé
     */
    public function uploadCarteIdentite($filePath, string $nci): ?string
    {
        if (!$this->cloudinary) {
            return "https://via.placeholder.com/400x300?text=Carte+ID+{$nci}"; // URL de test
        }
        
        try {
            $result = $this->cloudinary->uploadApi()->upload($filePath, [
                'folder' => 'appdaf/cartes_identite',
                'public_id' => 'carte_' . $nci,
                'overwrite' => true
            ]);
            return $result['secure_url'] ?? null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Récupère l'URL optimisée d'une carte d'identité
     */
    public function getCarteIdentiteUrl(string $nci): ?string
    {
        if (!$this->cloudinary) {
            return "https://via.placeholder.com/400x300?text=Carte+ID+{$nci}"; // URL de test
        }
        
        try {
            $publicId = 'appdaf/cartes_identite/carte_' . $nci;
            return $this->cloudinary->image($publicId)->toUrl();
        } catch (\Exception $e) {
            return null;
        }
    }
}