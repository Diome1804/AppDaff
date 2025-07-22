<?php
echo "PHP fonctionne!<br>";
echo "Version PHP: " . phpversion() . "<br>";

// Test des extensions
if (extension_loaded('pdo_pgsql')) {
    echo "Extension PDO PostgreSQL: OK<br>";
} else {
    echo "Extension PDO PostgreSQL: MANQUANTE<br>";
}

// Test variables d'environnement
echo "DATABASE_URL: " . (getenv('DATABASE_URL') ? 'Définie' : 'Non définie') . "<br>";

// Test connexion
try {
    if (getenv('DATABASE_URL')) {
        $pdo = new PDO(getenv('DATABASE_URL'));
        echo "Connexion base de données: OK<br>";
    } else {
        echo "DATABASE_URL non trouvée<br>";
    }
} catch (Exception $e) {
    echo "Erreur connexion: " . $e->getMessage() . "<br>";
}
?>
