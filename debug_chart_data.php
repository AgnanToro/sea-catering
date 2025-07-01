<?php
// Debug script untuk melihat data chart yang akan ditampilkan

echo "=== DEBUG CHART DATA ===\n\n";

// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'sea_apps_db';

$mysqli = new mysqli($host, $username, $password, $database);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

echo "✅ Database connected\n\n";

// Get subscription growth data (last 6 months)
echo "=== SUBSCRIPTION GROWTH DATA (Last 6 Months) ===\n";
$growthData = [];
$revenueData = [];
$labels = [];

for ($i = 5; $i >= 0; $i--) {
    $date = date('Y-m-01', strtotime("-$i months"));
    $monthEnd = date('Y-m-t', strtotime("-$i months"));
    $monthName = date('M Y', strtotime("-$i months"));
    
    // Count new subscriptions in this month
    $stmt = $mysqli->prepare("
        SELECT COUNT(*) as count 
        FROM subscriptions 
        WHERE created_at >= ? AND created_at <= ?
    ");
    $monthEndFull = $monthEnd . ' 23:59:59';
    $stmt->bind_param('ss', $date, $monthEndFull);
    $stmt->execute();
    $result = $stmt->get_result();
    $newSubscriptions = $result->fetch_assoc()['count'];
    
    // Calculate revenue for this month (active subscriptions created before or during this month)
    $stmt = $mysqli->prepare("
        SELECT SUM(price) as total 
        FROM subscriptions 
        WHERE status = 'active' AND created_at <= ?
    ");
    $stmt->bind_param('s', $monthEndFull);
    $stmt->execute();
    $result = $stmt->get_result();
    $monthlyRevenue = $result->fetch_assoc()['total'] ?? 0;
    
    $labels[] = $monthName;
    $growthData[] = $newSubscriptions;
    $revenueData[] = round($monthlyRevenue / 1000000, 2); // Convert to millions
    
    echo sprintf("%-10s: %2d new subscriptions, Rp %.2f juta revenue\n", 
        $monthName, $newSubscriptions, round($monthlyRevenue / 1000000, 2));
}

echo "\n=== CURRENT VS LAST MONTH ===\n";

// Get current month statistics for comparison
$currentMonth = date('Y-m-01');
$lastMonth = date('Y-m-01', strtotime('-1 month'));

$stmt = $mysqli->prepare("
    SELECT COUNT(*) as count 
    FROM subscriptions 
    WHERE created_at >= ?
");
$stmt->bind_param('s', $currentMonth);
$stmt->execute();
$result = $stmt->get_result();
$currentMonthSubscriptions = $result->fetch_assoc()['count'];

$stmt = $mysqli->prepare("
    SELECT COUNT(*) as count 
    FROM subscriptions 
    WHERE created_at >= ? AND created_at < ?
");
$stmt->bind_param('ss', $lastMonth, $currentMonth);
$stmt->execute();
$result = $stmt->get_result();
$lastMonthSubscriptions = $result->fetch_assoc()['count'];

$subscriptionGrowthPercent = $lastMonthSubscriptions > 0 
    ? round((($currentMonthSubscriptions - $lastMonthSubscriptions) / $lastMonthSubscriptions) * 100, 1)
    : 0;

echo "Current month (" . date('M Y') . "): $currentMonthSubscriptions subscriptions\n";
echo "Last month (" . date('M Y', strtotime('-1 month')) . "): $lastMonthSubscriptions subscriptions\n";
echo "Growth: $subscriptionGrowthPercent%\n";

echo "\n=== TOTAL MRR ===\n";
$stmt = $mysqli->prepare("
    SELECT SUM(price) as total, COUNT(*) as count
    FROM subscriptions 
    WHERE status = 'active'
");
$stmt->execute();
$result = $stmt->get_result();
$mrrData = $result->fetch_assoc();
$mrr = $mrrData['total'] ?? 0;
$activeCount = $mrrData['count'] ?? 0;

echo "Active subscriptions: $activeCount\n";
echo "Total MRR: Rp " . number_format($mrr, 0, ',', '.') . "\n";

echo "\n=== CHART DATA OUTPUT ===\n";
echo "Labels: " . json_encode($labels) . "\n";
echo "Growth Data: " . json_encode($growthData) . "\n";
echo "Revenue Data: " . json_encode($revenueData) . "\n";

$mysqli->close();
echo "\nDone.\n";
?>
