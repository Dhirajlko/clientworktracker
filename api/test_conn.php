<?php
require_once 'config.php';
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "✅ Connected to database '$DB_NAME' with user '$DB_USER'";
$conn->close();
?>