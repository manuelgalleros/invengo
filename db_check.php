<?php
// Database connection parameters - adjust these to match your configuration
$host = 'localhost';
$username = 'root';
$password = '@Password123';
$database = 'invengo';

// Connect to the database
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected to the database successfully.<br><br>";

// Query to get all groups with their permissions
$sql = "SELECT id, group_name, permission FROM groups";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h3>Groups and Permissions</h3>";
    echo "<pre>";
    
    while($row = $result->fetch_assoc()) {
        echo "Group ID: " . $row["id"] . "<br>";
        echo "Group Name: " . $row["group_name"] . "<br>";
        echo "Permission (raw): " . htmlspecialchars($row["permission"]) . "<br>";
        echo "Permission length: " . strlen($row["permission"]) . " bytes<br>";
        
        // Try to unserialize
        $unserialized = @unserialize($row["permission"]);
        if ($unserialized !== false) {
            echo "Unserialized successfully: ";
            print_r($unserialized);
        } else {
            echo "Failed to unserialize permission data<br>";
            
            // Display hexdump of the permission string to diagnose corruption
            echo "Hex dump: ";
            for ($i = 0; $i < strlen($row["permission"]); $i++) {
                echo bin2hex($row["permission"][$i]) . " ";
                if (($i + 1) % 16 == 0) echo "<br>";
            }
        }
        
        echo "<hr>";
    }
    
    echo "</pre>";
} else {
    echo "No groups found in the database.";
}

// Query to get user-group mappings
echo "<h3>User-Group Mappings</h3>";
echo "<pre>";

$sql = "SELECT user_group.user_id, users.username, user_group.group_id, groups.group_name 
        FROM user_group 
        JOIN users ON user_group.user_id = users.id 
        JOIN groups ON user_group.group_id = groups.id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "User ID: " . $row["user_id"] . "<br>";
        echo "Username: " . $row["username"] . "<br>";
        echo "Group ID: " . $row["group_id"] . "<br>";
        echo "Group Name: " . $row["group_name"] . "<br>";
        echo "<hr>";
    }
} else {
    echo "No user-group mappings found.";
}
echo "</pre>";

// Close the connection
$conn->close();
?> 