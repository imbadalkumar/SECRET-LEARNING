<?php
include('../includes/db.php');
include('../includes/header.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $pass = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = mysqli_prepare($conn, "INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sss", $name, $email, $pass);
    mysqli_stmt_execute($stmt);

    header("Location: login.php?registered=1");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
<link rel="stylesheet" href="/edu-app/assets/css/style.css">

</head>
<body>
<div class="auth-container">
    <h2>User Registration</h2>
    <form method="POST">
        <input type="text" name="name" required placeholder="Full Name"><br>
        <input type="email" name="email" required placeholder="Email"><br>
        <input type="password" name="password" required placeholder="Password"><br>
        <button type="submit">Register</button>
    </form>
    <p>Already have an account? <a href="login.php">Login here</a></p>
</div>

</body>
</html>
