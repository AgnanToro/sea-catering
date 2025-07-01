<?php
// Test database connection script
echo "Testing database connection...\n\n";

// Test mysqli connection
echo "1. Testing MySQLi connection:\n";
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'sea_apps_db';
$port = 3306;

$mysqli = new mysqli($host, $username, $password, '', $port);

if ($mysqli->connect_error) {
    echo "   ❌ MySQLi Connection failed: " . $mysqli->connect_error . "\n";
    echo "   Error code: " . $mysqli->connect_errno . "\n";
} else {
    echo "   ✅ MySQLi Connection successful!\n";
    
    // Test if database exists
    $result = $mysqli->query("SHOW DATABASES LIKE '$database'");
    if ($result && $result->num_rows > 0) {
        echo "   ✅ Database '$database' exists\n";
    } else {
        echo "   ⚠️  Database '$database' does not exist\n";
        echo "   Creating database...\n";
        if ($mysqli->query("CREATE DATABASE IF NOT EXISTS `$database`")) {
            echo "   ✅ Database '$database' created successfully\n";
        } else {
            echo "   ❌ Failed to create database: " . $mysqli->error . "\n";
        }
    }
    
    $mysqli->close();
}

echo "\n2. Testing CodeIgniter database connection:\n";

// Set up CodeIgniter constants
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);
define('APPPATH', __DIR__ . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR);
define('ROOTPATH', __DIR__ . DIRECTORY_SEPARATOR);
define('WRITEPATH', __DIR__ . DIRECTORY_SEPARATOR . 'writable' . DIRECTORY_SEPARATOR);

// Load CodeIgniter
require_once 'vendor/autoload.php';

// Set the environment
putenv('CI_ENVIRONMENT=development');

try {
    // Load configuration
    $config = new \Config\Database();
    $db = \CodeIgniter\Database\Config::connect($config->default);
    $result = $db->query('SELECT 1 as test');
    if ($result) {
        echo "   ✅ CodeIgniter database connection successful!\n";
        $row = $result->getRow();
        echo "   Test query result: " . $row->test . "\n";
    }
} catch (Exception $e) {
    echo "   ❌ CodeIgniter database connection failed: " . $e->getMessage() . "\n";
}

echo "\n3. Common solutions:\n";
echo "   - Install and start MySQL/MariaDB server\n";
echo "   - Install XAMPP, WAMP, or similar local development environment\n";
echo "   - Check if MySQL service is running\n";
echo "   - Verify database credentials in app/Config/Database.php\n";
echo "   - Make sure port 3306 is not blocked by firewall\n";

echo "\nDone.\n";
?>
