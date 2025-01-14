<?php
session_start();

if (!isset($_SESSION['user'])) {
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
            ],
            [
                'author' => 'user3',
                'content' => 'This is such an amazing breakdown of the anime industry!',
                'posted_at' => '2025-01-10 12:00',
                'reply' => [
                    [
                        'author' => 'user2',
                        'content' => 'I appreciate your thoughts, it\'s a fascinating topic.',
                        'posted_at' => '2025-01-10 12:30'
                    ],
                    [
                        'author' => 'user2',
                        'content' => 'I appreciate your thoughts, it\'s a fascinating topic.',
                        'posted_at' => '2025-01-10 12:30'
                    ]
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
    <link rel="stylesheet" href="styles/index.css">
</head>
<body>

<div class="navbar">
    <div class="left-navigation">
        <a class="nav-url" href="logout.php">Logout</a>
    </div>
    <h1 class="nav-title">NerdySphere_Forum</h1>
    <div class="right-navigation">
        <a class="nav-url" href="new_post.php">Create Post</a>
        <a class="nav-url" href="profile.php">Profile</a>
    </div>
</div>

<?php foreach ($posts as $post): ?>
    <div class="post">
        <div class="post-body">
            <p class="post-info">Posted by: <?= $post['author'] ?> | Category: <?= $post['category'] ?> | Posted at: <?= $post['posted_at'] ?></p>
            <h2 class="title"><?= $post['title'] ?></h2>
            <p class="content"><?= $post['content'] ?></p>

            <?php if (isset($post['images'])): ?>
                <div class="post-images">
                    <?php foreach ($post['images'] as $image): ?>
                        <img class="image" src="<?= $image ?>" alt="Post Image">
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="comment-section">
            <?php foreach ($post['comments'] as $comment): ?>
                <div class="comment">
                    <p class="comment-user">user3:</p>
                    <p class="comment-content">This is such an amazing breakdown of the anime industry!</p>
                    <p class="comment-info">Posted at: 2025-01-10 12:00</p>
                    <textarea class="reply-input" placeholder="Add a reply..."></textarea>
                    <button class="submit-reply">Send Reply</button>

                    <div class="comment-replies">
                        <div class="comment-reply">
                            <p class="comment-user">user2:</p>
                            <p class="comment-content">I appreciate your thoughts, it's a fascinating topic.</p>
                            <p class="comment-info">Posted at: 2025-01-10 12:30</p>
                        </div>
                        <div class="comment-reply">
                            <p class="comment-user">user2:</p>
                            <p class="comment-content">Another follow-up reply!</p>
                            <p class="comment-info">Posted at: 2025-01-10 13:00</p>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        </div>

        <form action="#">
            <textarea class="comment-input" placeholder="Add a comment..."></textarea>
            <button class="submit-comment">Send Comment</button>
        </form>
    </div>
<?php endforeach; ?>

</body>
</html>
