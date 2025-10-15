<?php
// Database connection file (db.php)

$host = "localhost";   // usually "localhost"
$user = "root";        // your MySQL username
$pass = "";            // your MySQL password (empty by default in XAMPP)
$db   = "complaint_db"; // your database name

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
