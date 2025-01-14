<?php
session_start();

if (isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}

require_once "database.php";

$errors = array();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usernameOrEmail = $_POST["username_or_email"];
    $password = $_POST["password"];

    if (empty($usernameOrEmail) || empty($password)) {
        $errors[] = "Both fields are required.";
    }

    if (count($errors) === 0) {
        $conn = getDatabaseConnection();
        $stmt = $conn->prepare("SELECT id, username, password_hash FROM user WHERE email = ? OR username = ?");
        $stmt->bind_param("ss", $usernameOrEmail, $usernameOrEmail);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $id = $row['id'];
            $username = $row['username'];
            $hashed_password = $row['password_hash'];

            if (password_verify($password, $hashed_password)) {
                $_SESSION["user"] = $username;
                $_SESSION["user_id"] = $id;

                header("Location: index.php");
                exit();
            } else {
                $errors[] = "Wrong username or password.";
            }
        } else {
            $errors[] = "Wrong username or password.";
        }
    }
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

    <?php if (count($errors) > 0): ?>
        <div class="error-input">
            <?php foreach ($errors as $error): ?>
                <p><?php echo $error; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

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
