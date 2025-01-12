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
