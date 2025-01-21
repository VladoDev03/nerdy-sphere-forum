<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
}

require_once "configuration/database.php";

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST["title"];
    $content = $_POST["content"];
    $category = $_POST["category"];
    $userId = $_SESSION["user_id"];
    $uploadDir = 'uploads/';
    $uploadedImages = [];

    if (empty($title) || empty($content)) {
        $errors[] = "All fields are required.";
    }

    if (!empty($_FILES['images']['name'][0])) {
        foreach ($_FILES['images']['tmp_name'] as $index => $tmpName) {
            $fileName = basename($_FILES['images']['name'][$index]);
            $fileSize = $_FILES['images']['size'][$index];
            $fileTmp = $_FILES['images']['tmp_name'][$index];
            $fileType = mime_content_type($fileTmp);
            $targetFile = $uploadDir . uniqid() . '_' . $fileName;

            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

            if (!in_array($fileType, $allowedTypes)) {
                $errors[] = "File type not allowed for file: $fileName.";
                continue;
            }

            if ($fileSize > 5 * 1024 * 1024) {
                $errors[] = "File is too large. Must be less than 5MB: $fileName.";
                continue;
            }

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            if (move_uploaded_file($fileTmp, $targetFile)) {
                $uploadedImages[] = $targetFile;
            } else {
                $errors[] = "Failed to upload file: $fileName.";
            }
        }
    }

    if (count($errors) === 0) {
        $conn = getDatabaseConnection();

        $stmt = $conn->prepare("INSERT INTO post (title, content, category, user_id) VALUES (?, ?, ?, ?);");
        $stmt->bind_param("sssi", $title, $content, $category, $userId);

        if ($stmt->execute()) {
            $postId = $stmt->insert_id;

            if (!empty($uploadedImages)) {
                $imageStmt = $conn->prepare("INSERT INTO post_image (post_id, image_url) VALUES (?, ?);");

                foreach ($uploadedImages as $imagePath) {
                    $imageStmt->bind_param("is", $postId, $imagePath);
                    $imageStmt->execute();
                }

                $imageStmt->close();
            }

            $conn->commit();

            header("Location: user_posts.php");
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
