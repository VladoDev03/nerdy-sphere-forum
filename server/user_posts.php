<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
}

require_once "configuration/database.php";

$currentUser = $_SESSION['user_id'];
$userId = isset($_GET['id']) ? intval($_GET['id']) : $currentUser;

$postSql = "
    SELECT
	    p.id,
        p.title,
        p.created_at,
        u.username,
        count(c.id) AS comments_count
    FROM post AS p
    LEFT JOIN comment AS c ON c.post_id = p.id
    LEFT JOIN user AS u ON u.id = p.user_id
    WHERE p.user_id = '$userId'
    GROUP BY p.id;
";

$stmt = $conn->prepare($postSql);
$stmt->execute();
$stmt->bind_result($postId, $title, $createdAt, $username, $commentsCount);

$posts = [];

while ($stmt->fetch()) {
    $posts[] = [
        'id' => $postId,
        'title' => $title,
        'created_at' => $createdAt,
        '$comments_count' => $commentsCount
    ];
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Posts</title>
    <link rel="stylesheet" href="styles/user_posts.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="js/delete-post.js" defer></script>
    <script src="js/edit-post-navigate.js" defer></script>
    <script src="js/share-post.js" defer></script>
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<header>
    <h1 class="page-title"><?= $currentUser === $userId ? 'My' : $username . '\'s' ?> Posts</h1>
</header>
<?php foreach ($posts as $post): ?>
    <main class="container">
        <a href="post.php?id=<?= $post['id'] ?>" class="post-link">
        <div class="post">
            <div class="post-icons">
                <i class="fas fa-share-alt share-icon share" data-id="<?= $post['id'] ?>" data-url="post.php?id=<?= $post['id'] ?>"></i>
                <i class="fas fa-edit edit-icon edit" data-id="<?= $post['id'] ?>"></i>
                <i class="fas fa-trash-alt delete-icon delete" data-id="<?= $post['id'] ?>"></i>
            </div>
            <h2 class="post-title"><?= $post['title'] ?></h2>
            <div class="info-container">
                <p class="posted-at">Posted at: <?= $post['created_at'] ?></p>
                <p class="comments-count"><?= $post['$comments_count'] ?> comments</p>
            </div>
        </div>
        </a>
    </main>
<?php endforeach; ?>
</body>
</html>
