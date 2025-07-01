<?php
// Script untuk menghapus subscription berdasarkan ID atau kondisi tertentu

echo "=== DELETE SUBSCRIPTION DATA ===\n\n";

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

// List semua subscription
echo "CURRENT SUBSCRIPTIONS:\n";
$result = $mysqli->query("
    SELECT s.id, s.plan, s.price, s.status, s.start_date, u.name, u.email 
    FROM subscriptions s 
    JOIN users u ON s.user_id = u.id 
    ORDER BY s.id
");

if ($result->num_rows > 0) {
    echo "ID\tUser\t\t\tPlan\t\tPrice\t\tStatus\t\tStart Date\n";
    echo "=================================================================================\n";
    while($row = $result->fetch_assoc()) {
        echo sprintf("%d\t%-20s\t%-10s\t%s\t%-10s\t%s\n", 
            $row['id'], 
            substr($row['name'] . ' (' . $row['email'] . ')', 0, 20),
            ucfirst($row['plan']),
            'Rp ' . number_format($row['price'], 0),
            ucfirst($row['status']),
            $row['start_date']
        );
    }
} else {
    echo "No subscriptions found.\n";
}

echo "\n=== DELETE OPTIONS ===\n";
echo "Uncomment salah satu option dibawah untuk menghapus data:\n\n";

// Option 1: Delete by ID
echo "// Option 1: Delete specific subscription by ID\n";
echo "// \$id = 5; // Ganti dengan ID yang ingin dihapus\n";
echo "// \$stmt = \$mysqli->prepare('DELETE FROM subscriptions WHERE id = ?');\n";
echo "// \$stmt->bind_param('i', \$id);\n";
echo "// if (\$stmt->execute()) {\n";
echo "//     echo \"Subscription ID \$id berhasil dihapus\\n\";\n";
echo "// } else {\n";
echo "//     echo \"Error: \" . \$mysqli->error . \"\\n\";\n";
echo "// }\n\n";

// Option 2: Delete multiple by IDs
echo "// Option 2: Delete multiple subscriptions by IDs\n";
echo "// \$ids = [1, 2, 3, 4]; // Ganti dengan array ID yang ingin dihapus\n";
echo "// \$placeholders = str_repeat('?,', count(\$ids) - 1) . '?';\n";
echo "// \$stmt = \$mysqli->prepare(\"DELETE FROM subscriptions WHERE id IN (\$placeholders)\");\n";
echo "// \$stmt->bind_param(str_repeat('i', count(\$ids)), ...\$ids);\n";
echo "// if (\$stmt->execute()) {\n";
echo "//     echo \"Successfully deleted \" . \$stmt->affected_rows . \" subscriptions\\n\";\n";
echo "// } else {\n";
echo "//     echo \"Error: \" . \$mysqli->error . \"\\n\";\n";
echo "// }\n\n";

// Option 3: Delete by user email
echo "// Option 3: Delete all subscriptions by user email\n";
echo "// \$email = 'user@seaapps.com'; // Ganti dengan email user\n";
echo "// \$stmt = \$mysqli->prepare('DELETE s FROM subscriptions s JOIN users u ON s.user_id = u.id WHERE u.email = ?');\n";
echo "// \$stmt->bind_param('s', \$email);\n";
echo "// if (\$stmt->execute()) {\n";
echo "//     echo \"All subscriptions for \$email deleted\\n\";\n";
echo "// } else {\n";
echo "//     echo \"Error: \" . \$mysqli->error . \"\\n\";\n";
echo "// }\n\n";

// Option 4: Delete all test data
echo "// Option 4: Delete ALL subscriptions (DANGER!)\n";
echo "// if (confirm('Are you sure you want to delete ALL subscriptions?')) {\n";
echo "//     \$result = \$mysqli->query('DELETE FROM subscriptions');\n";
echo "//     if (\$result) {\n";
echo "//         echo \"All subscriptions deleted\\n\";\n";
echo "//     } else {\n";
echo "//         echo \"Error: \" . \$mysqli->error . \"\\n\";\n";
echo "//     }\n";
echo "// }\n\n";

echo "AKTIFKAN salah satu option diatas dengan menghapus komentar (//) lalu jalankan script ini lagi.\n";

$mysqli->close();
echo "\nDone.\n";
?>
