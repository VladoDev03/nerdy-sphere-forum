<?php

session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

require_once "../configuration/database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $postId = $_POST['post_id'];
    $userId = $_SESSION['user_id'];

    $conn = getDatabaseConnection();

    $selectImagesSql = "SELECT image_url FROM post_image WHERE post_id = ?";
    $selectStmt = $conn->prepare($selectImagesSql);
    $selectStmt->bind_param('i', $postId);
    $selectStmt->execute();
    $result = $selectStmt->get_result();

    $imagePaths = [];

    while ($row = $result->fetch_assoc()) {
        $imagePaths[] = $row['image_url'];
    }

    $selectStmt->close();

    foreach ($imagePaths as $imagePath) {
        if (!is_writable($imagePath)) {
            echo "File is not writable: $imagePath<br>";
        }
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

    $deleteImagesSql = "DELETE FROM post_image WHERE post_id = ?";
    $deleteImageStmt = $conn->prepare($deleteImagesSql);
    $deleteImageStmt->bind_param('i', $postId);
    $deleteImageStmt->execute();
    $deleteImageStmt->close();

    $deletePostSql = "DELETE FROM post WHERE id = ?";
    $deleteStmt = $conn->prepare($deletePostSql);
    $deleteStmt->bind_param('i', $postId);

    if ($deleteStmt->execute()) {
        echo "Post and related data have been deleted successfully.";
    } else {
        echo "Error deleting post: " . $deleteStmt->error;
    }

    $deleteStmt->close();
}

$conn->close();
