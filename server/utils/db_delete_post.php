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
