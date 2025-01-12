<?php
session_start();

if (isset($_SESSION["user"])) {
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Login to Your Account</h1>
    <form action="login.php" method="POST">
        <div class="form-group">
            <label for="username_or_email">Email or Username:</label>
            <input type="text" id="username_or_email" name="username_or_email" placeholder="Enter your email or username">
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter your password">
        </div>
        <button type="submit">Login</button>
    </form>
    <div class="register-link">
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</div>
</body>
</html>
