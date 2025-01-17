<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
}

require_once "configuration/database.php";
require_once "utils/db_post_data.php";

$postSql = "
    SELECT
        p.id,
        p.title,
        p.content,
        p.category,
        p.created_at,
        u.id AS user_id,
        u.username,
        count(c.id) AS comments_count
    FROM post AS p
    LEFT JOIN user AS u ON u.id = p.user_id
    LEFT JOIN comment AS c ON c.post_id = p.id
    GROUP BY p.id;
";

$postStmt = $conn->prepare($postSql);
$postStmt->execute();
$postStmt->bind_result($postId, $title, $content, $category, $createdAt, $userId, $username, $commentsCount);

echo $commentsCount;

$postData = [];

while ($postStmt->fetch()) {
    $postData[] = [
        'id' => $postId,
        'title' => $title,
        'content' => $content,
        'category' => $category,
        'created_at' => $createdAt,
        'user_id' => $userId,
        'username' => $username,
        'comments_count' => $commentsCount
    ];
}

$postStmt->close();

$posts = [];

foreach ($postData as $post) {
    $postId = $post['id'];

    $images = fetchImages($conn, $postId);
    $comments = fetchComments($conn, $postId);

    $posts[] = array_merge($post, [
        'images' => $images,
        'comments' => $comments
    ]);
}

foreach ($posts as $postIndex => $post) {
    $posts[$postIndex] = fetchReplies($conn, $post);
}

//echo json_encode($posts, JSON_PRETTY_PRINT);
?>

<?php
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NerdySphere_Forum</title>
    <link rel="stylesheet" href="styles/index.css">
    <script src="js/index.js" defer></script>
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<?php foreach ($posts as $post): ?>
    <?php include 'includes/post_view.php'; ?>
<?php endforeach; ?>
<script>
    const currentUsername = '<?= $_SESSION['user'] ?>';
    const currentUserId = <?= $_SESSION['user_id'] ?>;
</script>
</body>
</html>
