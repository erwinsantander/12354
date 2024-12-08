<?php
// Database connection
$mysqli = new mysqli('127.0.0.1', 'u510162695_ancminimart', '1Ancminimart', 'u510162695_ancminimart');

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Securely hash the new password
$new_password = password_hash('santander', PASSWORD_BCRYPT);

// Update query
$sql = "UPDATE users SET password = ? WHERE username = 'Admin'";
$stmt = $mysqli->prepare($sql);

if ($stmt) {
    $stmt->bind_param('s', $new_password); // Bind the hashed password
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Password updated successfully.";
    } else {
        echo "No rows were updated. Check if the username exists.";
    }

    $stmt->close();
} else {
    echo "Error preparing the query: " . $mysqli->error;
}

$mysqli->close();
?>
