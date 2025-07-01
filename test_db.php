<?php
echo "Testing database connection...\n";
echo "Host: localhost\n";
echo "Database: sea_apps_db\n";
echo "Username: root\n";

try {
    $pdo = new PDO(
        "mysql:host=localhost;port=3306;dbname=sea_apps_db",
        "root",
        ""
    );
    
    echo "✅ Database connection successful!\n";
    
    // Test query
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables found: " . implode(', ', $tables) . "\n";
    
    // Check users table
    if (in_array('users', $tables)) {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
        $count = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "Users count: " . $count['count'] . "\n";
        
        // Check admin user
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute(['admin@seaapps.com']);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($admin) {
            echo "✅ Admin user found: " . $admin['name'] . "\n";
        } else {
            echo "❌ Admin user not found\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
}
