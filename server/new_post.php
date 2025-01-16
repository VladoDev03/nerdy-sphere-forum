<?php
session_start();

if(!isset($_SESSION['user'])) {
    header('Location: login.php');
}

require_once "database.php";

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST["title"];
    $content = $_POST["content"];
    $category = $_POST["category"];
    $userId = $_SESSION["user_id"];

    if (empty($title) || empty($content)) {
        $errors[] = "All fields are required.";
    }

    if (count($errors) === 0) {
        $conn = getDatabaseConnection();

        $stmt = $conn->prepare("INSERT INTO post (title, content, category, user_id) VALUES (?, ?, ?, ?);");
        $stmt->bind_param("sssi", $title, $content, $category, $userId);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            $errors[] = "Something went wrong. Please try again later.";
        }

        $stmt->close();
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Post</title>
    <link rel="stylesheet" href="styles/forms.css">
</head>
<body>
<div class="container">
    <h1 class="form-title">Create a New Post</h1>
    <form action="new_post.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" placeholder="Enter post title" required>
        </div>
        <div class="form-group">
            <label for="content">Content:</label>
            <textarea id="content" name="content" placeholder="Enter post content" rows="5" required></textarea>
        </div>
        <div class="form-group">
            <label for="category">Category:</label>
            <select id="category" name="category" required>
                <option value="">--Select a Category--</option>
                <option value="Video Games">Video Games</option>
                <option value="Books">Books</option>
                <option value="Manga">Manga</option>
                <option value="Comics">Comics</option>
                <option value="Anime">Anime</option>
                <option value="Cartoons">Cartoons</option>
                <option value="Animations">Animations</option>
                <option value="Board Games">Board Games</option>
                <option value="Music">Music</option>
                <option value="Puzzles">Puzzles</option>
                <option value="Movies">Movies</option>
                <option value="Card Games">Card Games</option>
                <option value="Series">Series</option>
            </select>
        </div>
        <div class="form-group">
            <label for="images">Upload Images:</label>
            <input type="file" id="images" name="images[]" accept="image/*" multiple>
        </div>
        <div class="form-group">
            <button type="submit">Create Post</button>
        </div>
    </form>
</div>
</body>
</html>
