<?php
session_start();
include("db.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Database connect
    $conn = new mysqli("localhost", "root", "", "complaint_db");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check user (users table should have "role" column: student/teacher)
    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password' LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        
        $_SESSION['email'] = $row['email'];
        $_SESSION['role'] = $row['role'];

        // Redirect based on role
        if ($row['role'] === "student") {
            header("Location: complaint.php");
            exit();
        } elseif ($row['role'] === "teacher") {
            header("Location: check.php");
            exit();
        } else {
            $error = "Role not recognized!";
        }
    } else {
        $error = "Invalid Email or Password!";
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - Complaint Portal</title>
    <link rel="stylesheet" href="style0.css">
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form method="POST" action="">
            <input type="email" name="email" placeholder="Enter Email" required><br>
            <input type="password" name="password" placeholder="Enter Password" required><br>
            <button type="submit">Login</button>
        </form>
        <?php if(isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
    </div>
</body>
</html>
