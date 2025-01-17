<?php

session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

require_once "../configuration/database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $commentId = $_POST['comment_id'];
    $userId = $_SESSION['user_id'];

    $deleteCommentSql = "DELETE FROM comment WHERE id = ?";
    $deleteStmt = $conn->prepare($deleteCommentSql);
    $deleteStmt->bind_param('i', $commentId);

    if ($deleteStmt->execute()) {
        echo "Comment and related data have been deleted successfully.";
    } else {
        echo "Error deleting comment: " . $deleteStmt->error;
    }

    $deleteStmt->close();
}

$conn->close();
