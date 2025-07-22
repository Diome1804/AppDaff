<?php
echo "PHP fonctionne!<br>";
echo "Version PHP: " . phpversion() . "<br>";

// Liste toutes les extensions
echo "<h3>Extensions chargées:</h3>";
$extensions = get_loaded_extensions();
foreach ($extensions as $ext) {
    if (strpos(strtolower($ext), 'pdo') !== false || strpos(strtolower($ext), 'pgsql') !== false) {
        echo "- $ext<br>";
    }
}

// Test PDO drivers
echo "<h3>Drivers PDO:</h3>";
foreach (PDO::getAvailableDrivers() as $driver) {
    echo "- $driver<br>";
}

// Test variables d'environnement
echo "<h3>Variables d'environnement:</h3>";
echo "DATABASE_URL (getenv): " . (getenv('DATABASE_URL') ? 'Définie' : 'Non définie') . "<br>";
echo "DATABASE_URL (\$_ENV): " . (isset($_ENV['DATABASE_URL']) ? 'Définie' : 'Non définie') . "<br>";

echo "<h3>Toutes les variables d'environnement:</h3>";
foreach ($_ENV as $key => $value) {
    if (strpos($key, 'DB') !== false || strpos($key, 'DATABASE') !== false) {
        echo "- $key: " . (strlen($value) > 50 ? substr($value, 0, 50) . '...' : $value) . "<br>";
    }
}

// Test connexion
echo "<h3>Test connexion:</h3>";
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
