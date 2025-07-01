<?php
// Test endpoint for debugging subscription data
echo "=== DEBUG SUBSCRIPTION DATA ===\n\n";

// Get POST data
$input = json_decode(file_get_contents('php://input'), true);

echo "RAW POST DATA:\n";
echo file_get_contents('php://input') . "\n\n";

echo "PARSED JSON DATA:\n";
print_r($input);
echo "\n\n";

echo "VALIDATION CHECK:\n";

// Check required fields
$required = ['plan', 'phone', 'meal_types', 'delivery_days', 'address', 'price'];
foreach ($required as $field) {
    if (isset($input[$field])) {
        echo "✅ $field: " . (is_array($input[$field]) ? json_encode($input[$field]) : $input[$field]) . "\n";
    } else {
        echo "❌ $field: MISSING\n";
    }
}

echo "\n";
echo "meal_types is array: " . (is_array($input['meal_types'] ?? null) ? 'YES' : 'NO') . "\n";
echo "delivery_days is array: " . (is_array($input['delivery_days'] ?? null) ? 'YES' : 'NO') . "\n";
echo "meal_types count: " . (isset($input['meal_types']) ? count($input['meal_types']) : 0) . "\n";
echo "delivery_days count: " . (isset($input['delivery_days']) ? count($input['delivery_days']) : 0) . "\n";
?>
