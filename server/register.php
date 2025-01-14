<?php
session_start();

if (isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}

require_once "database.php";

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $username = $_POST["username"];
    $firstName = $_POST["first_name"];
    $lastName = $_POST["last_name"];
    $password = $_POST["password"];
    $repeatPassword = $_POST["repeat_password"];

    if (empty($email) || empty($username) || empty($firstName) || empty($lastName) || empty($password) || empty($repeatPassword)) {
        $errors[] = "All fields are required.";
    }

    $passwordErrors = validatePassword($password);

    if (count($passwordErrors) > 0) {
        $errors = array_merge($errors, $passwordErrors);
    }

    if ($password !== $repeatPassword) {
        $errors[] = "Passwords do not match.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }

    if (count($errors) === 0) {
        $conn = getDatabaseConnection();

        $stmt = $conn->prepare("SELECT 1 FROM user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = "Email is already taken.";
        }

        $stmt->close();

        $stmt = $conn->prepare("SELECT 1 FROM user WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = "Username is already taken.";
        }

        $stmt->close();

        if (count($errors) === 0) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO user (email, username, first_name, last_name, password_hash) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $email, $username, $firstName, $lastName, $hashedPassword);

            if ($stmt->execute()) {
                header("Location: login.php");
                exit();
            } else {
                $errors[] = "Something went wrong. Please try again later.";
            }

            $stmt->close();
        }

        $conn->close();
    }
}

function validatePassword($password) {
    $passwordErrors = [];

    if (strlen($password) < 6) {
        $passwordErrors[] = "Password must be at least 6 characters.";
    }
    if (!preg_match('/[A-Z]/', $password)) {
        $passwordErrors[] = "Password must contain at least one uppercase letter.";
    }
    if (!preg_match('/[a-z]/', $password)) {
        $passwordErrors[] = "Password must contain at least one lowercase letter.";
    }
    if (!preg_match('/[0-9]/', $password)) {
        $passwordErrors[] = "Password must contain at least one digit.";
    }
    if (!preg_match('/[\W_]/', $password)) {
        $passwordErrors[] = "Password must contain at least one special character.";
    }

    return $passwordErrors;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="styles/forms.css">
</head>
<body>
<div class="container">
    <h1 class="form-title">Create New Account</h1>

    <?php if (count($errors) > 0): ?>
        <div class="error-input">
            <?php foreach ($errors as $error): ?>
                <p><?php echo $error; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="register.php" method="post">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
        </div>
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" placeholder="Enter your username" required>
        </div>
        <div class="form-group">
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" placeholder="Enter your first name" required>
        </div>
        <div class="form-group">
            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" placeholder="Enter your last name" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
        </div>
        <div class="form-group">
            <label for="repeat_password">Repeat Password:</label>
            <input type="password" id="repeat_password" name="repeat_password" placeholder="Repeat your password"
                   required>
        </div>
        <button type="submit" name="submit">Register</button>
    </form>

    <div class="login-link">
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</div>
</body>
</html>
