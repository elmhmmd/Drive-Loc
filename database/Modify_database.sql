CREATE TABLE Themes (
    theme_id INT PRIMARY KEY AUTO_INCREMENT,
    theme_name VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE Articles (
    article_id INT PRIMARY KEY AUTO_INCREMENT,
    article_title VARCHAR(255) NOT NULL,
    article_content TEXT NOT NULL,
    user_id INT,
    status ENUM("Pending", "Approved", "Rejected"),
    image VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE Tags (
    tag_id INT PRIMARY KEY AUTO_INCREMENT,
    tag_name VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE Comments (
    comment_id INT PRIMARY KEY AUTO_INCREMENT,
    comment_content TEXT NOT NULL,
    article_id INT,
    user_id INT,
    FOREIGN KEY (article_id) REFERENCES Articles(article_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE Article_Themes(
    article_id INT,
    theme_id INT,
    PRIMARY KEY (article_id, theme_id),
    FOREIGN KEY (article_id) REFERENCES Articles(article_id) ON DELETE CASCADE,
    FOREIGN KEY (theme_id) REFERENCES Themes(theme_id) ON DELETE CASCADE
);

CREATE TABLE Article_Tags(
    article_id INT,
    tag_id INT,
    PRIMARY KEY (article_id, tag_id),
    FOREIGN KEY (article_id) REFERENCES Articles(article_id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES Tags(tag_id) ON DELETE CASCADE
);

CREATE Table Favorites(
favorite_id INT AUTO_INCREMENT PRIMARY KEY,
user_id INT NOT NULL,
article_id INT NOT NULL,
FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
FOREIGN KEY (article_id) REFERENCES Articles(article_id) ON DELETE CASCADE,
UNIQUE KEY unique_user_article (user_id, article_id) 
);