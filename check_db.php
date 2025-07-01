<?php
try {
    $pdo = new PDO('mysql:host=localhost;port=3306', 'root', '');
    echo "✅ Connected to MySQL\n";
    
    // Cek database
    $stmt = $pdo->query('SHOW DATABASES');
    echo "\n📋 Available databases:\n";
    while($row = $stmt->fetch()) {
        echo "- " . $row[0] . "\n";
        if ($row[0] === 'sea_apps_db') {
            echo "  ✅ sea_apps_db found!\n";
        }
    }
    
    // Cek apakah database sea_apps_db ada
    $stmt = $pdo->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'sea_apps_db'");
    if ($stmt->fetch()) {
        echo "\n✅ Database 'sea_apps_db' exists\n";
        
        // Cek tables dalam sea_apps_db
        $pdo->exec('USE sea_apps_db');
        $stmt = $pdo->query('SHOW TABLES');
        echo "\n📋 Tables in sea_apps_db:\n";
        while($row = $stmt->fetch()) {
            echo "- " . $row[0] . "\n";
        }
    } else {
        echo "\n❌ Database 'sea_apps_db' NOT found\n";
        echo "Creating database...\n";
        $pdo->exec('CREATE DATABASE sea_apps_db');
        echo "✅ Database 'sea_apps_db' created\n";
    }
    
} catch(Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
