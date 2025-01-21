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

CREATE TABLE user_reaction (
	id INT AUTO_INCREMENT PRIMARY KEY,
    reaction ENUM(
        'Like',
        'Dislike'
    ) NOT NULL,
    user_id INT NOT NULL,
    post_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_reaction (post_id, user_id),
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE,
    FOREIGN KEY (post_id) REFERENCES post(id) ON DELETE CASCADE
);

INSERT INTO user (email, username, first_name, last_name, password_hash, created_at)
VALUES
    ('user1@example.com', 'user1', 'John', 'Doe', '$2y$10$EXAMPLESaltAndHash1', NOW()),
    ('user2@example.com', 'user2', 'Jane', 'Smith', '$2y$10$EXAMPLESaltAndHash2', NOW()),
    ('user3@example.com', 'user3', 'Alice', 'Johnson', '$2y$10$EXAMPLESaltAndHash3', NOW());
    
# I must register a new user before continuing with the insertions
INSERT INTO post (title, content, category, user_id, created_at)
VALUES
    ('Exploring the Latest in Video Games', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce convallis justo eget orci efficitur, eget fermentum justo tristique. Sed ut placerat nunc. Quisque vel mauris felis.', 'Video Games', 1, NOW()),
    ('The Future of Manga: Trends and Predictions', 'Nullam vehicula, justo vel cursus convallis, leo nisl fermentum dui, in tincidunt ligula neque eget turpis.', 'Manga', 3, NOW()),
    ('The Evolution of Anime: A Visual Journey', 'Curabitur ultricies dolor ac velit malesuada, nec fermentum erat mollis. Phasellus sit amet augue vitae est eleifend pretium.', 'Anime', 2, NOW()),
    ('The Future of RTX requirement in Video Games: Is... Inevitable', 'Nullam vehicula, justo vel cursus convallis, leo nisl fermentum dui, in tincidunt ligula neque eget turpis.', 'Video Games', 4, NOW());

INSERT INTO user_reaction (reaction, user_id, post_id)
VALUES
	('Like', 1, 2),
	('Dislike', 1, 3),
	('Like', 2, 1),
	('Dislike', 2, 3),
	('Like', 3, 2),
	('Dislike', 3, 1);

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
SELECT * FROM user_reaction;

SET SQL_SAFE_UPDATES = 0;
DELETE FROM user;
DELETE FROM post;
DELETE FROM comment;
DELETE FROM comment_tag;
DELETE FROM user_reaction;
SET SQL_SAFE_UPDATES = 1;

SELECT * FROM user
WHERE username = 'vladsto';

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

# Get posts and their user and comments count
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
LEFT JOIN comment AS c ON c.post_id = p.id
WHERE p.user_id = '4'
GROUP BY p.id;

# Get all posts and their comments count
SELECT
	p.id,
    p.title,
    p.created_at,
    count(*) AS comments_count
FROM post AS p
LEFT JOIN comment AS c ON c.post_id = p.id
GROUP BY p.id;

# Get post by id
SELECT
	p.title,
    p.content,
    p.created_at
FROM post AS p
WHERE p.id = 4;

DELETE FROM post
WHERE id = 23;

SELECT * FROM post AS p
INNER JOIN comment AS c ON p.id = c.post_id
WHERE p.id = 23;

SELECT * FROM comment AS c
INNER JOIN post AS p ON p.id = c.post_id
WHERE c.post_id = 23;

# Get all posts but those of the current logged in user
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
WHERE u.id <> '4'
GROUP BY p.id;

SELECT * FROM post
WHERE id = 47;

UPDATE post
SET 
    title = 'Updated Title',
    content = 'Updated content goes here.',
    category = 'Books'
WHERE 
    id = 47;
    
UPDATE user_reaction
SET reaction = 'Like'
WHERE user_id = 6 AND post_id = 1;
    
UPDATE user_reaction
SET reaction = 'Dislike'
WHERE user_id = 6 AND post_id = 1;

SELECT * FROM user_reaction
WHERE user_id = 6 AND post_id = 1;

SELECT 
	SUM(CASE WHEN reaction = 'Like' THEN 1 ELSE 0 END) AS likeCount,
	SUM(CASE WHEN reaction = 'Dislike' THEN 1 ELSE 0 END) AS dislikeCount
FROM user_reaction 
WHERE post_id = 1;

# Demo
INSERT INTO user (email, username, first_name, last_name, password_hash) VALUES
('alice@example.com', 'alice123', 'Alice', 'Smith', 'hashedpassword1'),
('bob@example.com', 'bobby', 'Bob', 'Johnson', 'hashedpassword2'),
('charlie@example.com', 'charlie88', 'Charlie', 'Brown', 'hashedpassword3'),
('dave@example.com', 'davey', 'Dave', 'Williams', 'hashedpassword4'),
('eve@example.com', 'eve234', 'Eve', 'Davis', 'hashedpassword5'),
('frank@example.com', 'frankly', 'Frank', 'Garcia', 'hashedpassword6'),
('grace@example.com', 'graceful', 'Grace', 'Martinez', 'hashedpassword7'),
('hank@example.com', 'hankster', 'Hank', 'Rodriguez', 'hashedpassword8'),
('iris@example.com', 'iris98', 'Iris', 'Lewis', 'hashedpassword9'),
('jack@example.com', 'jacky', 'Jack', 'Walker', 'hashedpassword10');

INSERT INTO post (title, content, category, user_id) VALUES
('Top 10 Video Games of 2024', 'A list of the best games of the year.', 'Video Games', 1),
('Best Fantasy Books', 'Explore the best fantasy novels of the decade.', 'Books', 2),
('Manga Recommendations', 'Must-read manga for newcomers.', 'Manga', 3),
('Superhero Comics You Should Read', 'A guide to the best superhero comics.', 'Comics', 4),
('Upcoming Anime in 2025', 'The most anticipated anime series.', 'Anime', 5),
('Cartoon Classics', 'The timeless cartoons everyone should watch.', 'Cartoons', 6),
('The Art of Animation', 'The evolution of animation over the years.', 'Animations', 7),
('Best Board Games for Families', 'Family-friendly board games.', 'Board Games', 8),
('Top Music Albums of 2024', 'The best albums released this year.', 'Music', 9),
('Challenging Puzzles', 'Brain-teasers that will test your limits.', 'Puzzles', 10),
('Must-Watch Movies', 'Movies you should add to your watchlist.', 'Movies', 1),
('Card Games for Parties', 'The best card games for social gatherings.', 'Card Games', 2),
('TV Series to Binge', 'Series that you won’t be able to stop watching.', 'Series', 3);

INSERT INTO comment (content, post_id, user_id, parent_comment_id) VALUES
('Great list! I agree with your top pick.', 1, 2, NULL),
('I love fantasy books, thanks for the recommendations!', 2, 3, NULL),
('This is so helpful for a manga newbie like me!', 3, 4, NULL),
('Superheroes are my favorite! Great picks.', 4, 5, NULL),
('Can’t wait for these anime shows!', 5, 6, NULL),
('Nostalgia! Cartoons from my childhood.', 6, 7, NULL),
('Animation is truly an art form.', 7, 8, NULL),
('Our family loves board games! Thanks for the tips.', 8, 9, NULL),
('This music list is spot on!', 9, 10, NULL),
('I’ve been looking for challenging puzzles, thanks!', 10, 1, NULL),
('Adding these movies to my watchlist!', 11, 2, NULL),
('Card games are a hit at our parties.', 12, 3, NULL),
('Binge-worthy series! Just what I needed.', 13, 4, NULL),
('I think this game should’ve been number one.', 1, 3, 1),
('Do you have more fantasy book suggestions?', 2, 4, 2);

INSERT INTO post_image (post_id, image_url) VALUES
(1, 'https://res.cloudinary.com/dtqmlqc0d/image/upload/v1737494576/forum/doom_zh2zkd.webp'),
(1, 'https://res.cloudinary.com/dtqmlqc0d/image/upload/v1737493390/forum/gta_uwi4gk.jpg'),
(1, 'https://res.cloudinary.com/dtqmlqc0d/image/upload/v1737493390/forum/elden_ring_ebtzlx.jpg'),
(2, 'https://res.cloudinary.com/dtqmlqc0d/image/upload/v1737493390/forum/hail_mary_o8hp5j.jpg'),
(2, 'https://res.cloudinary.com/dtqmlqc0d/image/upload/v1737493390/forum/i_robot_skdelp.jpg'),
(3, 'https://res.cloudinary.com/dtqmlqc0d/image/upload/v1737493390/forum/fullmetal_alchemist_t5jggw.jpg'),
(3, 'https://res.cloudinary.com/dtqmlqc0d/image/upload/v1737493389/forum/demon_slayer_yppkiq.webp'),
(4, 'https://res.cloudinary.com/dtqmlqc0d/image/upload/v1737495232/forum/superman_l3jmhg.jpg'),
(5, 'https://res.cloudinary.com/dtqmlqc0d/image/upload/v1737494897/forum/AttackOnTitan_Anime_ColossusTitan_Eren_fixed_wqxiuv.jpg'),
(6, 'https://res.cloudinary.com/dtqmlqc0d/image/upload/v1737493391/forum/Tom-and-jerry-1-_mmj6af.webp'),
(6, 'https://res.cloudinary.com/dtqmlqc0d/image/upload/v1737493391/forum/mickey_mouse_fik11i.jpg'),
(7, 'https://res.cloudinary.com/dtqmlqc0d/image/upload/v1737493390/forum/spiderman-marvel-i41725_wrxoaq.jpg'),
(8, 'https://res.cloudinary.com/dtqmlqc0d/image/upload/v1737493392/forum/dnd_vcreg9.webp'),
(9, 'https://res.cloudinary.com/dtqmlqc0d/image/upload/v1737493391/forum/queen_ithzud.jpg'),
(9, 'https://res.cloudinary.com/dtqmlqc0d/image/upload/v1737493391/forum/metallica_ev4ola.webp'),
(10, 'https://res.cloudinary.com/dtqmlqc0d/image/upload/v1737493391/forum/rubik_kqsvjb.jpg'),
(11, 'https://res.cloudinary.com/dtqmlqc0d/image/upload/v1737493391/forum/interstellar_vc5elr.jpg'),
(11, 'https://res.cloudinary.com/dtqmlqc0d/image/upload/v1737493389/forum/spiderman_el1cia.webp'),
(11, 'https://res.cloudinary.com/dtqmlqc0d/image/upload/v1737493389/forum/MCDDEAN_WD040_e4rhxe.webp'),
(11, 'https://res.cloudinary.com/dtqmlqc0d/image/upload/v1737493389/forum/Iron_Man_Infobox_hyz5gp.webp'),
(11, 'https://res.cloudinary.com/dtqmlqc0d/image/upload/v1737493389/forum/Deadpool__2016_poster_rjz2d6.png'),
(11, 'https://res.cloudinary.com/dtqmlqc0d/image/upload/v1737493389/forum/Thor_in_LoveAndThunder_Poster_je9do2.webp'),
(11, 'https://res.cloudinary.com/dtqmlqc0d/image/upload/v1737495233/forum/topgun_oqasqo.jpg'),
(12, 'https://res.cloudinary.com/dtqmlqc0d/image/upload/v1737493391/forum/uno_h4jmyt.jpg'),
(13, 'https://res.cloudinary.com/dtqmlqc0d/image/upload/v1737493391/forum/Winter-is-coming-Wallpaper_gdsmch.jpg');

INSERT INTO user_reaction (reaction, user_id, post_id) VALUES
('Like', 1, 1),
('Like', 2, 2),
('Dislike', 3, 3),
('Like', 4, 4),
('Like', 5, 5),
('Dislike', 6, 6),
('Like', 7, 7),
('Like', 8, 8),
('Dislike', 9, 9),
('Like', 10, 10),
('Like', 1, 11),
('Dislike', 2, 12),
('Like', 3, 13),
('Like', 4, 1),
('Dislike', 5, 2);
