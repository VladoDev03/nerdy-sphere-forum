<?php
session_start();

if(!isset($_SESSION['user'])) {
     header('Location: login.php');
}
?>

<?php
$posts = [
    [
        'title' => 'Exploring the Latest in Video Games',
        'author' => 'user1',
        'category' => 'Video Games',
        'posted_at' => '2025-01-12 12:00',
        'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce convallis justo eget orci efficitur, eget fermentum justo tristique. Sed ut placerat nunc. Quisque vel mauris felis.',
        'comments' => [
            [
                'author' => 'user2',
                'content' => 'Great insights! I agree with your perspective on the latest releases.',
                'posted_at' => '2025-01-12 14:00',
                'reply' => [
                    'author' => 'user1',
                    'content' => 'Thanks! I\'m glad you liked the insights.',
                    'posted_at' => '2025-01-12 14:30'
                ]
            ]
        ]
    ],
    [
        'title' => 'The Future of Manga: Trends and Predictions',
        'author' => 'user3',
        'category' => 'Manga',
        'posted_at' => '2025-01-11 18:00',
        'content' => 'Nullam vehicula, justo vel cursus convallis, leo nisl fermentum dui, in tincidunt ligula neque eget turpis.',
        'images' => [
            'https://res.cloudinary.com/dtqmlqc0d/image/upload/v1736643122/forum/cnqwbt1ckjclw9grgkle.jpg'
        ],
        'comments' => [
            [
                'author' => 'user1',
                'content' => 'I can\'t wait to see how these trends shape the industry!',
                'posted_at' => '2025-01-11 20:00',
                'reply' => [
                    'author' => 'user2',
                    'content' => 'Yes, it\'ll be interesting to see how the market reacts!',
                    'posted_at' => '2025-01-11 20:30'
                ]
            ]
        ]
    ],
    [
        'title' => 'The Evolution of Anime: A Visual Journey',
        'author' => 'user2',
        'category' => 'Anime',
        'posted_at' => '2025-01-10 10:00',
        'content' => 'Curabitur ultricies dolor ac velit malesuada, nec fermentum erat mollis. Phasellus sit amet augue vitae est eleifend pretium.',
        'images' => [
            'https://res.cloudinary.com/dtqmlqc0d/image/upload/v1736643122/forum/cnqwbt1ckjclw9grgkle.jpg',
            'https://res.cloudinary.com/dtqmlqc0d/image/upload/v1736643122/forum/cnqwbt1ckjclw9grgkle.jpg',
            'https://res.cloudinary.com/dtqmlqc0d/image/upload/v1736643122/forum/cnqwbt1ckjclw9grgkle.jpg',
            'https://res.cloudinary.com/dtqmlqc0d/image/upload/v1736643122/forum/cnqwbt1ckjclw9grgkle.jpg'
        ],
        'comments' => [
            [
                'author' => 'user3',
                'content' => 'This is such an amazing breakdown of the anime industry!',
                'posted_at' => '2025-01-10 12:00',
                'reply' => [
                    'author' => 'user2',
                    'content' => 'I appreciate your thoughts, it\'s a fascinating topic.',
                    'posted_at' => '2025-01-10 12:30'
                ]
            ]
        ]
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nerdy Sphere Forum</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }

        .navbar {
            background-color: #333;
            overflow: hidden;
        }

        .navbar a {
            float: right;
            display: block;
            color: white;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
        }

        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
        }

        .post {
            background-color: #fff;
            padding: 20px;
            margin: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .post h2 {
            font-size: 24px;
            margin: 0;
        }

        .post p {
            font-size: 16px;
        }

        .post-images img {
            width: 100%;
            max-width: 500px;
            margin: 10px 0;
            border-radius: 8px;
        }

        .comments {
            margin-top: 20px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .comment {
            padding: 10px;
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .reply {
            margin-left: 30px;
            border-left: 2px solid #ddd;
            padding-left: 10px;
        }

        .comment p {
            margin: 5px 0;
        }

        form {
            margin-top: 20px;
        }

        textarea {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            padding: 10px 15px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="navbar">
    <a href="logout.php">Logout</a>
    <a href="new_post.php">Create Post</a>
</div>

<h1>Welcome to Nerdy Sphere Forum</h1>

<?php foreach ($posts as $post): ?>
    <div class="post">
        <h2><?= $post['title'] ?></h2>
        <p><strong>Posted by:</strong> <?= $post['author'] ?> | <strong>Category:</strong> <?= $post['category'] ?> | <strong>Posted at:</strong> <?= $post['posted_at'] ?></p>
        <p><?= $post['content'] ?></p>

        <?php if (isset($post['images'])): ?>
            <div class="post-images">
                <?php foreach ($post['images'] as $image): ?>
                    <img src="<?= $image ?>" alt="Post Image">
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <h3>Comments:</h3>
        <div class="comments">
            <?php foreach ($post['comments'] as $comment): ?>
                <div class="comment">
                    <p><strong><?= $comment['author'] ?>:</strong></p>
                    <p><?= $comment['content'] ?></p>
                    <p><small>Posted at: <?= $comment['posted_at'] ?></small></p>

                    <?php if (isset($comment['reply'])): ?>
                        <div class="reply">
                            <p><strong><?= $comment['reply']['author'] ?>:</strong></p>
                            <p><?= $comment['reply']['content'] ?></p>
                            <p><small>Posted at: <?= $comment['reply']['posted_at'] ?></small></p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <form action="#">
            <textarea name="comment_content" placeholder="Add a comment..." required></textarea><br>
            <button type="submit">Submit Comment</button>
        </form>
    </div>
<?php endforeach; ?>

</body>
</html>
