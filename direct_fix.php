<?php
// Set PHP timezone
date_default_timezone_set('Asia/Manila');

// Database credentials (hardcoded from the database.php file)
$host = 'localhost';
$username = 'root';
$password = '@Password123';
$database = 'invengo';
$socket = '/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock'; // Add MySQL socket path

// Connect to database using socket
$conn = mysqli_init();
if (!$conn) {
    die("mysqli_init failed");
}

if (!mysqli_real_connect($conn, $host, $username, $password, $database, null, $socket)) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "<h1>Activity Logs Checker and Fixer</h1>";

// Check if the log table exists
$table_check = $conn->query("SHOW TABLES LIKE 'activity_logs'");
if ($table_check->num_rows == 0) {
    die("The activity_logs table does not exist in the database.");
}

// Get the table structure
echo "<h2>Table Structure</h2>";
$structure = $conn->query("DESCRIBE activity_logs");
echo "<table border='1'>";
echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
while ($row = $structure->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['Field']) . "</td>";
    echo "<td>" . htmlspecialchars($row['Type']) . "</td>";
    echo "<td>" . htmlspecialchars($row['Null']) . "</td>";
    echo "<td>" . htmlspecialchars($row['Key']) . "</td>";
    echo "<td>" . htmlspecialchars($row['Default']) . "</td>";
    echo "<td>" . htmlspecialchars($row['Extra']) . "</td>";
    echo "</tr>";
}
echo "</table>";

// Check current Orders-related logs
echo "<h2>Order-related Activity Logs</h2>";
$result = $conn->query("SELECT * FROM activity_logs WHERE description LIKE '%Orders%' ORDER BY created_at DESC LIMIT 10");

if ($result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>User ID</th><th>Username</th><th>Action</th><th>Description</th><th>Created At</th><th>Timestamp</th></tr>";
    
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['user_id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['username']) . "</td>";
        echo "<td>" . htmlspecialchars($row['action']) . "</td>";
        echo "<td>" . htmlspecialchars($row['description']) . "</td>";
        echo "<td>" . date('Y-m-d H:i:s', $row['created_at']) . "</td>";
        echo "<td>" . htmlspecialchars($row['timestamp']) . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
} else {
    echo "No order-related activity logs found.";
}

// Fix existing logs by adding # if needed
echo "<h2>Fixing Order Logs</h2>";

$fix_query = "
    UPDATE activity_logs 
    SET description = REPLACE(description, 'order ', 'order #')
    WHERE description LIKE '%Orders: %order %' 
    AND description NOT LIKE '%Orders: %order #%'
";

if ($conn->query($fix_query)) {
    echo "<p>Fixed " . $conn->affected_rows . " log entries by adding # before order numbers.</p>";
} else {
    echo "<p>Error fixing logs: " . $conn->error . "</p>";
}

// Also fix any 'Archived order', 'Restored order', etc.
$patterns = [
    "Archived order", 
    "Restored order", 
    "Deleted order", 
    "Created new order", 
    "Updated order"
];

foreach ($patterns as $pattern) {
    $fix_query = "
        UPDATE activity_logs 
        SET description = REPLACE(description, '$pattern ', '$pattern #')
        WHERE description LIKE '%Orders: $pattern %' 
        AND description NOT LIKE '%Orders: $pattern #%'
    ";
    
    if ($conn->query($fix_query)) {
        echo "<p>Fixed " . $conn->affected_rows . " log entries with pattern '$pattern'.</p>";
    } else {
        echo "<p>Error fixing logs with pattern '$pattern': " . $conn->error . "</p>";
    }
}

// Add a test log entry with proper formatting
echo "<h2>Adding Test Log Entry</h2>";

$current_time = time();
$current_datetime = date('Y-m-d H:i:s', $current_time);

$test_log = [
    'user_id' => 1, // Assuming admin user ID
    'username' => 'admin',
    'action' => 'Test',
    'description' => 'Orders: Test order #12345 with proper formatting',
    'created_at' => $current_time,
    'timestamp' => $current_datetime
];

$stmt = $conn->prepare("INSERT INTO activity_logs (user_id, username, action, description, created_at, timestamp) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isssss", $test_log['user_id'], $test_log['username'], $test_log['action'], $test_log['description'], $test_log['created_at'], $test_log['timestamp']);

if ($stmt->execute()) {
    echo "<p>Test log entry added successfully.</p>";
} else {
    echo "<p>Error adding test log: " . $stmt->error . "</p>";
}

// Display updated logs
echo "<h2>Updated Order-related Activity Logs</h2>";
$result = $conn->query("SELECT * FROM activity_logs WHERE description LIKE '%Orders%' ORDER BY created_at DESC LIMIT 10");

if ($result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>User ID</th><th>Username</th><th>Action</th><th>Description</th><th>Created At</th><th>Timestamp</th></tr>";
    
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['user_id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['username']) . "</td>";
        echo "<td>" . htmlspecialchars($row['action']) . "</td>";
        echo "<td>" . htmlspecialchars($row['description']) . "</td>";
        echo "<td>" . date('Y-m-d H:i:s', $row['created_at']) . "</td>";
        echo "<td>" . htmlspecialchars($row['timestamp']) . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
}

// Close connection
$conn->close();
?> 