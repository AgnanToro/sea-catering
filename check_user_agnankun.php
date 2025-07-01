<?php
// Script untuk cek dan buat akun user agnankun
echo "Checking user account: agnankun18@gmail.com\n\n";

// Test connection first
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'sea_apps_db';
$port = 3306;

$mysqli = new mysqli($host, $username, $password, $database, $port);

if ($mysqli->connect_error) {
    echo "❌ Database connection failed: " . $mysqli->connect_error . "\n";
    exit(1);
}

echo "✅ Database connected successfully\n\n";

// Check if user exists
$email = 'agnankun18@gmail.com';
$stmt = $mysqli->prepare("SELECT id, email, name, role FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo "✅ User found:\n";
    echo "   ID: " . $user['id'] . "\n";
    echo "   Name: " . $user['name'] . "\n";
    echo "   Email: " . $user['email'] . "\n";
    echo "   Role: " . $user['role'] . "\n";
} else {
    echo "⚠️  User not found. Creating user...\n";
    
    // Create user
    $name = 'Agnan Kun';
    $password_hash = password_hash('agnankun123', PASSWORD_DEFAULT); // Default password
    $role = 'user';
    $created_at = date('Y-m-d H:i:s');
    
    $stmt = $mysqli->prepare("INSERT INTO users (name, email, password, role, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $email, $password_hash, $role, $created_at, $created_at);
    
    if ($stmt->execute()) {
        echo "✅ User created successfully!\n";
        echo "   Name: $name\n";
        echo "   Email: $email\n";
        echo "   Password: agnankun123\n";
        echo "   Role: $role\n";
    } else {
        echo "❌ Failed to create user: " . $mysqli->error . "\n";
    }
}

$mysqli->close();

echo "\n=== Login Information ===\n";
echo "Email: agnankun18@gmail.com\n";
echo "Password: agnankun123\n";
echo "Role: user\n";
echo "\nSekarang bisa login di: http://localhost:8080/login\n";
?>
