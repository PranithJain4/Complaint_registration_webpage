<?php
$host = "localhost";   // or 127.0.0.1
$user = "root";        // default XAMPP/WAMP phpMyAdmin user
$pass = "";            // leave empty if no password is set
$db   = "complaint_system"; // your database name

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
