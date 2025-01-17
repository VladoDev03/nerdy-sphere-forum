CREATE DATABASE nerdy_sphere_forum;

DROP DATABASE nerdy_sphere_forum;

USE nerdy_sphere_forum;

CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    username VARCHAR(255) NOT NULL UNIQUE,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE post (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    category ENUM(
        'Video Games',
        'Books',
        'Manga',
        'Comics',
        'Anime',
        'Cartoons',
        'Animations',
        'Board Games',
        'Music',
        'Puzzles',
        'Movies',
        'Card Games',
        'Series'
    ) NOT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE
);

CREATE TABLE comment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    content TEXT NOT NULL,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    parent_comment_id INT DEFAULT NULL,
    FOREIGN KEY (post_id) REFERENCES post(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE,
    FOREIGN KEY (parent_comment_id) REFERENCES comment(id) ON DELETE CASCADE
);

CREATE TABLE comment_tag (
    id INT AUTO_INCREMENT PRIMARY KEY,
    comment_id INT NOT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY (comment_id) REFERENCES comment(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE
);

CREATE TABLE post_image (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    FOREIGN KEY (post_id) REFERENCES post(id) ON DELETE CASCADE
);

INSERT INTO user (email, username, first_name, last_name, password_hash, created_at)
VALUES
    ('user1@example.com', 'user1', 'John', 'Doe', '$2y$10$EXAMPLESaltAndHash1', NOW()),
    ('user2@example.com', 'user2', 'Jane', 'Smith', '$2y$10$EXAMPLESaltAndHash2', NOW()),
    ('user3@example.com', 'user3', 'Alice', 'Johnson', '$2y$10$EXAMPLESaltAndHash3', NOW());

INSERT INTO post (title, content, category, user_id, created_at)
VALUES
    ('Exploring the Latest in Video Games', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce convallis justo eget orci efficitur, eget fermentum justo tristique. Sed ut placerat nunc. Quisque vel mauris felis.', 'Video Games', 1, NOW()),
    ('The Future of Manga: Trends and Predictions', 'Nullam vehicula, justo vel cursus convallis, leo nisl fermentum dui, in tincidunt ligula neque eget turpis.', 'Manga', 3, NOW()),
    ('The Evolution of Anime: A Visual Journey', 'Curabitur ultricies dolor ac velit malesuada, nec fermentum erat mollis. Phasellus sit amet augue vitae est eleifend pretium.', 'Anime', 2, NOW()),
    ('The Future of RTX requirement in Video Games: Is... Inevitable', 'Nullam vehicula, justo vel cursus convallis, leo nisl fermentum dui, in tincidunt ligula neque eget turpis.', 'Video Games', 4, NOW());

INSERT INTO post_image (post_id, image_url)
VALUES
    (2, 'https://res.cloudinary.com/dtqmlqc0d/image/upload/v1736643122/forum/cnqwbt1ckjclw9grgkle.jpg'),
    (3, 'https://res.cloudinary.com/dtqmlqc0d/image/upload/v1736643122/forum/cnqwbt1ckjclw9grgkle.jpg'),
    (3, 'https://res.cloudinary.com/dtqmlqc0d/image/upload/v1736643122/forum/cnqwbt1ckjclw9grgkle.jpg'),
    (3, 'https://res.cloudinary.com/dtqmlqc0d/image/upload/v1736643122/forum/cnqwbt1ckjclw9grgkle.jpg');

INSERT INTO comment (content, post_id, user_id, created_at)
VALUES
    ('Great insights! I agree with your perspective on the latest releases.', 1, 2, NOW()),
    ('I can\'t wait to see how these trends shape the industry!', 2, 1, NOW()),
    ('This is such an amazing breakdown of the anime industry!', 3, 3, NOW()),
    ('This is sad. My PC will not be able to run new games :(', 4, 1, NOW());

INSERT INTO comment (content, post_id, user_id, created_at, parent_comment_id)
VALUES
    ('Thanks! I\'m glad you liked the insights.', 1, 1, NOW(), 1),
    ('Thanks! I\'m glad you liked it.', 1, 1, NOW(), 1),
    ('Yes, it\'ll be interesting to see how the market reacts!', 2, 2, NOW(), 2),
    ('I appreciate your thoughts, it\'s a fascinating topic.', 3, 2, NOW(), 3);

INSERT INTO comment_tag (comment_id, user_id)
VALUES
    (1, 2),
    (2, 1),
    (3, 3);

SELECT * FROM user;
SELECT * FROM post;
SELECT * FROM comment;
SELECT * FROM comment_tag;
SELECT * FROM post_image;

SET SQL_SAFE_UPDATES = 0;
DELETE FROM user;
DELETE FROM post;
DELETE FROM comment;
DELETE FROM comment_tag;
DELETE FROM post_image;
SET SQL_SAFE_UPDATES = 1;

DELETE FROM user
WHERE username = 'vladsto';

DELETE p FROM post AS p
INNER JOIN user AS u ON u.id = p.user_id
WHERE u.username = 'vladsto';

SELECT * FROM comment AS c
INNER JOIN user AS u ON c.user_id = u.id
WHERE u.username = 'vladsto';

SELECT
	p.title AS 'Post Title',
    p.content AS 'Post Content',
    p.created_at AS 'Created At',
	p.category AS 'Post Category',
	u.username AS 'Post user',
    c.content AS 'Comment',
    c.created_at AS 'Comment Created At',
    uc.username AS 'Comment User',
    pi.image_url AS 'Post Image Url',
    r.content AS 'Reply Content',
    r.created_at AS 'Reply Created At',
    ur.username AS 'Reply User'
FROM post AS p
INNER JOIN user AS u ON u.id = p.user_id
INNER JOIN comment AS c ON c.post_id = p.id
INNER JOIN user AS uc ON uc.id = c.user_id
INNER JOIN post_image AS pi ON pi.post_id = p.id
INNER JOIN comment AS r ON c.id = r.parent_comment_id
INNER JOIN user AS ur ON ur.id = r.user_id;

# Get posts and their user
SELECT
	p.id,
	p.title,
    p.content,
    p.created_at,
    p.category,
    u.id AS 'user id',
	u.username
FROM post AS p
INNER JOIN user AS u ON u.id = p.user_id;

# Get posts and their user and comments count and replies count
SELECT
    p.id,
    p.title,
    p.content,
    p.category,
    p.created_at,
    u.id AS user_id,
    u.username,
    COUNT(DISTINCT c.id) AS comments_count,
    COUNT(DISTINCT r.id) AS replies_count
FROM post AS p
INNER JOIN user AS u ON u.id = p.user_id
LEFT JOIN comment AS c ON c.post_id = p.id AND c.parent_comment_id IS NULL
LEFT JOIN comment AS r ON r.post_id = p.id AND r.parent_comment_id IS NOT NULL
GROUP BY p.id;

# Get post images
SELECT
    id,
    image_url
FROM post_image
WHERE post_id = 3;

# Get post comments and their user
SELECT
    c.id,
    c.content,
    c.created_at,
    u.id AS 'user id',
    u.username
FROM comment AS c
INNER JOIN user AS u ON u.id = c.user_id
WHERE post_id = 3
AND parent_comment_id IS NULL;

# Get comment replies and their user
SELECT
	c.id AS 'reply id',
	c.content,
    c.created_at,
    u.id AS 'user id',
    u.username
FROM comment AS c
INNER JOIN user AS u ON u.id = c.user_id
WHERE c.parent_comment_id = '1';

# Get user posts and their comments count
SELECT
	p.id,
    p.title,
    p.created_at,
    count(*) AS comments_count
FROM post AS p
INNER JOIN comment AS c ON c.post_id = p.id
WHERE p.user_id = '4'
GROUP BY p.id;

# Get all posts and their comments count
SELECT
	p.id,
    p.title,
    p.created_at,
    count(*) AS comments_count
FROM post AS p
INNER JOIN comment AS c ON c.post_id = p.id
GROUP BY p.id;

# Get post by id
SELECT
	p.title,
    p.content,
    p.created_at
FROM post AS p
WHERE p.id = 4;