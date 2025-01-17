<?php
session_start();

require_once "../configuration/database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    $content = $data['content'];
    $userId = $data['userId'];
    $postId = $data['postId'];
    $parentId = $data['parentId'] ?? null;

    if ($userId !== $_SESSION['user_id']) {
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO comment (content, post_id, user_id, parent_comment_id) VALUES (?, ?, ?, ?);");
    $stmt->bind_param("siii", $content, $postId, $userId, $parentId);

    if ($stmt->execute()) {
        exit();
    } else {
        $errors[] = "Something went wrong. Please try again later.";
    }

    $stmt->close();
    $conn->close();
}
