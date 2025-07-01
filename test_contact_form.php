<?php
// Test contact form submission

$url = 'http://localhost:8080/api/contact';

$data = [
    'name' => 'Test User',
    'email' => 'test@example.com',
    'phone' => '081234567890',
    'subject' => 'Test Subject',
    'message' => 'This is a test message from API test.'
];

echo "Testing contact form submission...\n";
echo "URL: $url\n";
echo "Data: " . json_encode($data) . "\n\n";

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'X-Requested-With: XMLHttpRequest'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

echo "HTTP Code: $httpCode\n";
echo "Response: $response\n";

if ($httpCode == 201) {
    echo "\n✅ Contact form submission successful!\n";
} else {
    echo "\n❌ Contact form submission failed!\n";
}

curl_close($ch);
?>
