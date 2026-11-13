CREATE DATABASE IF NOT EXISTS artcraft CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE artcraft;
CREATE TABLE IF NOT EXISTS users (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 fullname VARCHAR(120) NOT NULL, studentid VARCHAR(32) NOT NULL UNIQUE,
 email VARCHAR(254) NOT NULL UNIQUE, password_hash VARCHAR(255) NOT NULL,
 role ENUM('student','admin') NOT NULL DEFAULT 'student',
 status ENUM('active','inactive') NOT NULL DEFAULT 'active',
 created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS categories (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, name VARCHAR(80) NOT NULL UNIQUE
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS artworks (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, user_id INT UNSIGNED NOT NULL, category_id INT UNSIGNED NOT NULL,
 title VARCHAR(150) NOT NULL, description TEXT NOT NULL, materials VARCHAR(180) NOT NULL DEFAULT '',
 mime VARCHAR(20) NOT NULL, image MEDIUMBLOB NOT NULL,
 status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
 featured BOOLEAN NOT NULL DEFAULT FALSE, created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY (user_id) REFERENCES users(id), FOREIGN KEY (category_id) REFERENCES categories(id),
 INDEX (status,featured), INDEX (created_at)
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS exhibitions (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, title VARCHAR(150) NOT NULL,
 event_date DATE NOT NULL, venue VARCHAR(150) NOT NULL, description TEXT NOT NULL
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS participation (
 exhibition_id INT UNSIGNED NOT NULL, user_id INT UNSIGNED NOT NULL,
 PRIMARY KEY (exhibition_id,user_id),
 FOREIGN KEY (exhibition_id) REFERENCES exhibitions(id) ON DELETE CASCADE,
 FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;
INSERT IGNORE INTO categories(name) VALUES ('Painting'),('Drawing'),('Handcrafts'),('Digital Art'),('Photography'),('Sculpture'),('Traditional Art');
CREATE TABLE IF NOT EXISTS login_attempts (
 scope_hash CHAR(64) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
 window_id INT UNSIGNED NOT NULL, attempts SMALLINT UNSIGNED NOT NULL,
 PRIMARY KEY (scope_hash,window_id), INDEX (window_id)
) ENGINE=InnoDB;

USE artcraft;
CREATE TABLE IF NOT EXISTS remember_tokens (
 selector CHAR(24) CHARACTER SET ascii COLLATE ascii_bin PRIMARY KEY,
 token_hash CHAR(64) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
 user_id INT UNSIGNED NOT NULL,
 password_stamp CHAR(64) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
 expires_at BIGINT UNSIGNED NOT NULL,
 FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
 INDEX (expires_at)
) ENGINE=InnoDB;
