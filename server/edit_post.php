<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
}

require_once "configuration/database.php";
require_once "utils/db_post_data.php";

$currentUserId = $_SESSION['user_id'];
$postId = intval($_GET['id']);

$postSql = "
    SELECT
        p.title,
        p.content,
        p.category,
        u.id
    FROM post AS p
    LEFT JOIN user AS u ON u.id = p.user_id
    WHERE p.id = ?;
";

$stmt = $conn->prepare($postSql);
$stmt->bind_param("i", $postId);
$stmt->execute();
$stmt->bind_result($oldTitle, $oldContent, $oldCategory, $userId);

if (!$stmt->fetch()) {
    header('Location: user_posts.php');
    exit();
}

if ($userId !== $currentUserId) {
    header('Location: index.php');
}

$stmt->close();

$post = [
    "id" => $postId,
    "title" => $oldTitle,
    "content" => $oldContent,
    "category" => $oldCategory,
    "user_id" => $userId
];

$post['images'] = fetchImages($conn, $postId);

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
        $stmt = $conn->prepare("UPDATE post
            SET 
                title = ?,
                content = ?,
                category = ?
            WHERE 
                id = ?;
        ");

        $stmt->bind_param("sssi", $title, $content, $category, $postId);

        if ($stmt->execute()) {
            $postId = $stmt->insert_id;

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="js/edit-post.js" defer></script>
</head>
<body>

<div class="container">
    <h1 class="form-title">Edit Post</h1>
    <form id="edit-form" action="edit_post.php?id=<?= $post["id"]; ?>" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" placeholder="Edit post title" required value="<?= $post["title"]; ?>">
        </div>
        <div class="form-group">
            <label for="content">Content:</label>
            <input id="hidden-value" type="hidden" value="<?= $post["content"]; ?>">
            <textarea id="content" name="content" placeholder="Edit post content" rows="5" required></textarea>
        </div>
        <div class="form-group">
            <label for="category">Category:</label>
            <select id="category" name="category" required>
                <option value="<?= $post["category"]; ?>"><?= $post["category"]; ?></option>
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
        <?php if (isset($post['images'])): ?>
            <div class="post-images">
                <?php foreach ($post['images'] as $image): ?>
                    <div class="image-container" data-image-id="<?= $image['id'] ?>" data-post-id="<?= $post['id'] ?>">
                        <i class="fas fa-trash-alt delete-icon delete"></i>
                        <img class="image" src="<?= $image['url'] ?>" alt="Post Image">
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <div class="form-group">
            <button type="submit">Edit Post</button>
        </div>
        <div class="form-group">
            <button class="cancel-edit-container" type="button"><a href="user_posts.php" class="cancel-edit">Cancel</a></button>
        </div>
    </form>
</div>
</body>
</html>
