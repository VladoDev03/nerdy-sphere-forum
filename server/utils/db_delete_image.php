<?php

session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

require_once "../configuration/database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST['array'])) {
        exit();
    }

    $imagesToDelete = [];

    foreach ($_POST['array'] as $key => $value) {
        $imagesToDelete[$key] = $value;
    }

    foreach ($imagesToDelete as $key => $value) {
        $sql = "DELETE FROM post_image WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $value);

        if ($stmt->execute()) {
            echo "Comment and related data have been deleted successfully.";
        } else {
            echo "Error deleting comment: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
