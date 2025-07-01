<?php
$db = new PDO('mysql:host=localhost;dbname=sea_catering_db', 'root', '');

echo "<h2>Check Admin User in Database:</h2>\n";

// Check for admin users
$stmt = $db->prepare('SELECT id, name, email, is_admin, password FROM users WHERE is_admin = 1 OR email LIKE "%admin%"');
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($users)) {
    echo "<p>No admin users found. Let's check all users:</p>\n";
    
    $stmt = $db->prepare('SELECT id, name, email, is_admin FROM users');
    $stmt->execute();
    $allUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1'>\n";
    echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Is Admin</th></tr>\n";
    foreach($allUsers as $user) {
        echo "<tr>\n";
        echo "<td>{$user['id']}</td>\n";
        echo "<td>{$user['name']}</td>\n";
        echo "<td>{$user['email']}</td>\n";
        echo "<td>" . ($user['is_admin'] ? 'Yes' : 'No') . "</td>\n";
        echo "</tr>\n";
    }
    echo "</table>\n";
    
    // Create admin user if none exists
    echo "<h3>Creating admin user...</h3>\n";
    
    $adminData = [
        'name' => 'Admin',
        'email' => 'admin@seaapps.com',
        'password' => password_hash('admin123', PASSWORD_DEFAULT),
        'is_admin' => 1
    ];
    
    $stmt = $db->prepare('INSERT INTO users (name, email, password, is_admin) VALUES (?, ?, ?, ?)');
    $result = $stmt->execute([$adminData['name'], $adminData['email'], $adminData['password'], $adminData['is_admin']]);
    
    if ($result) {
        echo "<p>✅ Admin user created successfully!</p>\n";
        echo "<p>Email: admin@seaapps.com</p>\n";
        echo "<p>Password: admin123</p>\n";
    } else {
        echo "<p>❌ Failed to create admin user</p>\n";
    }
    
} else {
    echo "<table border='1'>\n";
    echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Is Admin</th><th>Password Hash</th></tr>\n";
    foreach($users as $user) {
        echo "<tr>\n";
        echo "<td>{$user['id']}</td>\n";
        echo "<td>{$user['name']}</td>\n";
        echo "<td>{$user['email']}</td>\n";
        echo "<td>" . ($user['is_admin'] ? 'Yes' : 'No') . "</td>\n";
        echo "<td>" . substr($user['password'], 0, 20) . "...</td>\n";
        echo "</tr>\n";
    }
    echo "</table>\n";
    
    // Test password verification
    echo "<h3>Password Verification Test:</h3>\n";
    foreach($users as $user) {
        $isValid = password_verify('admin123', $user['password']);
        echo "<p>User {$user['email']}: Password 'admin123' " . ($isValid ? '✅ Valid' : '❌ Invalid') . "</p>\n";
    }
}
