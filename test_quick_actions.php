<?php
// Test Quick Actions di Admin Dashboard
// File: test_quick_actions.php

// Check if we have admin session
session_start();

// Create admin session for testing
$_SESSION['isLoggedIn'] = true;
$_SESSION['user_id'] = 1;
$_SESSION['name'] = 'Admin Test';
$_SESSION['email'] = 'admin@test.com';
$_SESSION['role'] = 'admin';

echo "Session set for admin testing.\n";
echo "User ID: " . $_SESSION['user_id'] . "\n";
echo "Role: " . $_SESSION['role'] . "\n";
echo "Login: " . ($_SESSION['isLoggedIn'] ? 'YES' : 'NO') . "\n";

// Test endpoints
echo "\nTesting endpoints:\n";
echo "Admin Dashboard: http://localhost:8080/admin/dashboard\n";
echo "Export Data: http://localhost:8080/admin/export\n";
echo "All Subscriptions API: http://localhost:8080/api/subscriptions\n";
echo "All Contacts API: http://localhost:8080/api/contact\n";
?>
