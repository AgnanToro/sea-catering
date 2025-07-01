<?php
// Test login admin dengan credential yang benar
// File: test_admin_login_correct.php

$loginUrl = 'http://localhost:8080/login';
$adminDashboardUrl = 'http://localhost:8080/admin/dashboard';

// Admin credentials yang benar
$email = 'admin@seaapps.com';
$password = 'Admin123!';

echo "Testing admin login with correct credentials...\n";
echo "Email: $email\n";
echo "Password: $password\n";
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

if ($httpCode == 200 && strpos($finalUrl, 'admin') !== false) {
    echo "Login successful!\n";
    
    // Test API endpoints yang digunakan oleh Quick Actions
    echo "\nTesting API endpoints:\n";
    
    // Test subscriptions API
    echo "1. Testing /api/subscriptions...\n";
    curl_setopt($ch, CURLOPT_URL, 'http://localhost:8080/api/subscriptions');
    curl_setopt($ch, CURLOPT_POST, false);
    
    $subResponse = curl_exec($ch);
    $subCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    echo "   Status: $subCode\n";
    
    if ($subCode == 200) {
        $subData = json_decode($subResponse, true);
        $count = isset($subData['data']) ? count($subData['data']) : (is_array($subData) ? count($subData) : 0);
        echo "   Success: Found $count subscriptions\n";
    } else {
        echo "   Error: " . substr($subResponse, 0, 100) . "\n";
    }
    
    // Test contacts API
    echo "2. Testing /api/contact...\n";
    curl_setopt($ch, CURLOPT_URL, 'http://localhost:8080/api/contact');
    
    $contactResponse = curl_exec($ch);
    $contactCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    echo "   Status: $contactCode\n";
    
    if ($contactCode == 200) {
        $contactData = json_decode($contactResponse, true);
        $count = isset($contactData['data']) ? count($contactData['data']) : (is_array($contactData) ? count($contactData) : 0);
        echo "   Success: Found $count contacts\n";
    } else {
        echo "   Error: " . substr($contactResponse, 0, 100) . "\n";
    }
    
    // Test export endpoint
    echo "3. Testing /admin/export...\n";
    curl_setopt($ch, CURLOPT_URL, 'http://localhost:8080/admin/export');
    
    $exportResponse = curl_exec($ch);
    $exportCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    echo "   Status: $exportCode\n";
    
    if ($exportCode == 200) {
        echo "   Success: Export works (Size: " . strlen($exportResponse) . " bytes)\n";
    } else {
        echo "   Error: " . substr($exportResponse, 0, 100) . "\n";
    }
    
    // Test report endpoint
    echo "4. Testing /admin/report...\n";
    curl_setopt($ch, CURLOPT_URL, 'http://localhost:8080/admin/report');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'start_date' => '2025-06-01',
        'end_date' => '2025-07-01',
        'type' => 'comprehensive'
    ]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'X-Requested-With: XMLHttpRequest'
    ]);
    
    $reportResponse = curl_exec($ch);
    $reportCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    echo "   Status: $reportCode\n";
    
    if ($reportCode == 200) {
        $reportData = json_decode($reportResponse, true);
        if (isset($reportData['success']) && $reportData['success']) {
            echo "   Success: Report generated (" . $reportData['filename'] . ")\n";
        } else {
            echo "   Error: " . ($reportData['message'] ?? 'Unknown error') . "\n";
        }
    } else {
        echo "   Error: " . substr($reportResponse, 0, 100) . "\n";
    }
    
} else {
    echo "Login failed!\n";
    echo "Response: " . substr($response, 0, 300) . "\n";
}

curl_close($ch);
unlink($cookieJar);
?>
