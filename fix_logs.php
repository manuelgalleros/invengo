<?php
// Set PHP timezone
date_default_timezone_set('Asia/Manila');

// Include CodeIgniter bootstrap file to access the CI environment
define('BASEPATH', TRUE);
define('ENVIRONMENT', 'development');
require_once('./application/config/constants.php');

// Database credentials (hardcoded from the database.php file)
$host = 'localhost';
$username = 'root';
$password = '@Password123';
$database = 'invengo';
$socket = '/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock'; // Add MySQL socket path

// Connect to database using socket
$mysqli = mysqli_init();
if (!$mysqli) {
    die("mysqli_init failed");
}

if (!mysqli_real_connect($mysqli, $host, $username, $password, $database, null, $socket)) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "<h1>Fix Order Numbers in Activity Logs</h1>";

// Get current Orders-related logs
echo "<h2>Original Order Logs</h2>";
$result = $mysqli->query("SELECT * FROM activity_logs WHERE description LIKE '%Orders:%' ORDER BY created_at DESC LIMIT 20");

if ($result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Description</th><th>Created At</th></tr>";
    
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['description']) . "</td>";
        echo "<td>" . date('Y-m-d H:i:s', $row['created_at']) . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
} else {
    echo "No order-related activity logs found.";
}

// Fix logs with various patterns
$patterns = [
    // Specific action verbs followed by "order" without a # symbol
    ["pattern" => "Order: Created new order ", "replacement" => "Orders: Created new order #"],
    ["pattern" => "Order: Updated order ", "replacement" => "Orders: Updated order #"],
    ["pattern" => "Order: Deleted order ", "replacement" => "Orders: Deleted order #"],
    ["pattern" => "Order: Archived order ", "replacement" => "Orders: Archived order #"],
    ["pattern" => "Order: Restored order ", "replacement" => "Orders: Restored order #"],
    ["pattern" => "Orders: Created new order ", "replacement" => "Orders: Created new order #"],
    ["pattern" => "Orders: Updated order ", "replacement" => "Orders: Updated order #"],
    ["pattern" => "Orders: Deleted order ", "replacement" => "Orders: Deleted order #"],
    ["pattern" => "Orders: Archived order ", "replacement" => "Orders: Archived order #"],
    ["pattern" => "Orders: Restored order ", "replacement" => "Orders: Restored order #"],
    
    // Generic pattern for any "order" followed by a number without # symbol
    ["pattern" => "order ", "replacement" => "order #", "where" => "description LIKE '%order %' AND description NOT LIKE '%order #%'"]
];

$total_fixed = 0;

foreach ($patterns as $fix) {
    $where_clause = isset($fix['where']) ? $fix['where'] : "description LIKE '%" . $fix['pattern'] . "%'";
    
    $select_query = "SELECT id, description FROM activity_logs WHERE " . $where_clause;
    $select_result = $mysqli->query($select_query);
    
    echo "<h3>Checking pattern: " . htmlspecialchars($fix['pattern']) . "</h3>";
    
    if ($select_result->num_rows > 0) {
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Before</th><th>After</th></tr>";
        
        while ($row = $select_result->fetch_assoc()) {
            $old_description = $row['description'];
            $new_description = str_replace($fix['pattern'], $fix['replacement'], $old_description);
            
            // Only update if there was a change
            if ($old_description !== $new_description) {
                $update_query = "UPDATE activity_logs SET description = ? WHERE id = ?";
                $stmt = $mysqli->prepare($update_query);
                $stmt->bind_param("si", $new_description, $row['id']);
                $stmt->execute();
                
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                echo "<td>" . htmlspecialchars($old_description) . "</td>";
                echo "<td>" . htmlspecialchars($new_description) . "</td>";
                echo "</tr>";
                
                $total_fixed++;
            }
        }
        
        echo "</table>";
    } else {
        echo "<p>No logs found matching this pattern.</p>";
    }
}

echo "<h2>Summary</h2>";
echo "<p>Total logs fixed: " . $total_fixed . "</p>";

// Get updated logs
echo "<h2>Updated Order Logs</h2>";
$result = $mysqli->query("SELECT * FROM activity_logs WHERE description LIKE '%Orders:%' ORDER BY created_at DESC LIMIT 20");

if ($result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Description</th><th>Created At</th></tr>";
    
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['description']) . "</td>";
        echo "<td>" . date('Y-m-d H:i:s', $row['created_at']) . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
}

// Close connection
$mysqli->close();
?> 