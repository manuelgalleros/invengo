<?php
// Database configuration
$host = 'localhost';
$user = 'root';
$password = '@Password123';
$database = 'invengo'; // Updated with correct database name

// Connect to database
$mysqli = new mysqli($host, $user, $password, $database);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Check if activity_logs table exists
$table_check = $mysqli->query("SHOW TABLES LIKE 'activity_logs'");
if ($table_check->num_rows == 0) {
    die("The activity_logs table does not exist in the database.");
}

// Get table structure
echo "<h2>Table Structure</h2>";
$structure = $mysqli->query("DESCRIBE activity_logs");
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

// Get recent logs
echo "<h2>Recent Activity Logs</h2>";
$result = $mysqli->query("SELECT * FROM activity_logs ORDER BY created_at DESC LIMIT 20");

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
    echo "No activity logs found.";
}

// Get logs with Orders in description
echo "<h2>Order-related Activity Logs</h2>";
$result = $mysqli->query("SELECT * FROM activity_logs WHERE description LIKE '%Orders%' ORDER BY created_at DESC LIMIT 20");

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

// Close connection
$mysqli->close();
?> 