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
    public_id VARCHAR(255) NOT NULL,
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
    ('The Evolution of Anime: A Visual Journey', 'Curabitur ultricies dolor ac velit malesuada, nec fermentum erat mollis. Phasellus sit amet augue vitae est eleifend pretium.', 'Anime', 2, NOW());

INSERT INTO post_image (post_id, image_url, public_id)
VALUES
    (2, 'https://res.cloudinary.com/dtqmlqc0d/image/upload/v1736643122/forum/cnqwbt1ckjclw9grgkle.jpg', 'image_1_public_id'),
    (3, 'https://res.cloudinary.com/dtqmlqc0d/image/upload/v1736643122/forum/cnqwbt1ckjclw9grgkle.jpg', 'image_2_public_id'),
    (3, 'https://res.cloudinary.com/dtqmlqc0d/image/upload/v1736643122/forum/cnqwbt1ckjclw9grgkle.jpg', 'image_3_public_id'),
    (3, 'https://res.cloudinary.com/dtqmlqc0d/image/upload/v1736643122/forum/cnqwbt1ckjclw9grgkle.jpg', 'image_4_public_id');

INSERT INTO comment (content, post_id, user_id, created_at)
VALUES
    ('Great insights! I agree with your perspective on the latest releases.', 1, 2, NOW()),
    ('I can\'t wait to see how these trends shape the industry!', 2, 1, NOW()),
    ('This is such an amazing breakdown of the anime industry!', 3, 3, NOW());

INSERT INTO comment (content, post_id, user_id, created_at, parent_comment_id)
VALUES
    ('Thanks! I\'m glad you liked the insights.', 1, 1, NOW(), 1),
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
DELETE FROM post;
DELETE FROM comment;
DELETE FROM comment_tag;
DELETE FROM post_image;
SET SQL_SAFE_UPDATES = 1;

DELETE FROM user
WHERE username = 'vladsto';