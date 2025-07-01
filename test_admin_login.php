<?php
// Test login admin untuk Quick Actions
// File: test_admin_login.php

$loginUrl = 'http://localhost:8080/login';
$adminDashboardUrl = 'http://localhost:8080/admin/dashboard';

// Admin credentials
$email = 'admin@seaapps.com';
$password = 'password123'; // Default password from seed

echo "Testing admin login...\n";
echo "Email: $email\n";
echo "URL: $loginUrl\n";

// Use cURL to simulate login
$ch = curl_init();

// Enable cookie jar to maintain session
$cookieJar = tempnam(sys_get_temp_dir(), 'cookie');

curl_setopt($ch, CURLOPT_URL, $loginUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'email' => $email,
    'password' => $password
]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieJar);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieJar);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);

echo "HTTP Code: $httpCode\n";
echo "Final URL: $finalUrl\n";

if ($httpCode == 200) {
    echo "Login successful!\n";
    
    // Now test admin dashboard
    echo "\nTesting admin dashboard...\n";
    curl_setopt($ch, CURLOPT_URL, $adminDashboardUrl);
    curl_setopt($ch, CURLOPT_POST, false);
    
    $dashboardResponse = curl_exec($ch);
    $dashboardCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    echo "Dashboard HTTP Code: $dashboardCode\n";
    
    if ($dashboardCode == 200) {
        echo "Admin dashboard accessible!\n";
        
        // Test export endpoint
        echo "\nTesting export endpoint...\n";
        curl_setopt($ch, CURLOPT_URL, 'http://localhost:8080/admin/export');
        $exportResponse = curl_exec($ch);
        $exportCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        echo "Export HTTP Code: $exportCode\n";
        if ($exportCode == 200) {
            echo "Export endpoint works!\n";
            echo "Response length: " . strlen($exportResponse) . " bytes\n";
        } else {
            echo "Export endpoint failed!\n";
            echo "Response: " . substr($exportResponse, 0, 200) . "\n";
        }
        
    } else {
        echo "Admin dashboard not accessible!\n";
        echo "Response: " . substr($dashboardResponse, 0, 200) . "\n";
    }
} else {
    echo "Login failed!\n";
    echo "Response: " . substr($response, 0, 200) . "\n";
}

curl_close($ch);
unlink($cookieJar);
?>
