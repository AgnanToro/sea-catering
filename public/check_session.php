<?php
session_start();

echo "<h2>Current Session Data:</h2>\n";

$sessionData = $_SESSION ?? [];

if (empty($sessionData)) {
    echo "<p>No session data found. Please login first.</p>\n";
} else {
    echo "<table border='1'>\n";
    echo "<tr><th>Key</th><th>Value</th></tr>\n";
    foreach($sessionData as $key => $value) {
        $displayValue = is_bool($value) ? ($value ? 'true' : 'false') : $value;
        echo "<tr><td>{$key}</td><td>{$displayValue}</td></tr>\n";
    }
    echo "</table>\n";
}

echo "<h3>Required Session Keys for API:</h3>\n";
$requiredKeys = ['isLoggedIn', 'user_id', 'email', 'name', 'is_admin'];

foreach($requiredKeys as $key) {
    $exists = isset($sessionData[$key]);
    $value = $exists ? $sessionData[$key] : 'NOT SET';
    $status = $exists ? '✅' : '❌';
    
    echo "<p>{$status} {$key}: {$value}</p>\n";
}

echo "<h3>Authentication Status:</h3>\n";
$isLoggedIn = isset($sessionData['isLoggedIn']) && $sessionData['isLoggedIn'];
$isAdmin = isset($sessionData['is_admin']) && $sessionData['is_admin'];

echo "<p>Is Logged In: " . ($isLoggedIn ? '✅ Yes' : '❌ No') . "</p>\n";
echo "<p>Is Admin: " . ($isAdmin ? '✅ Yes' : '❌ No') . "</p>\n";
echo "<p>Can Access Admin API: " . ($isLoggedIn && $isAdmin ? '✅ Yes' : '❌ No') . "</p>\n";
