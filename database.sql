-- database.sql - NSBM ArtCraft database
-- Import this file in phpMyAdmin (Import tab) to create everything.

CREATE DATABASE IF NOT EXISTS artcraft;
USE artcraft;

-- 1. users: students and the admin
CREATE TABLE IF NOT EXISTS users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    fullname   VARCHAR(100) NOT NULL,
    studentid  VARCHAR(30)  NOT NULL UNIQUE,
    email      VARCHAR(100) NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,              -- hashed password
    role       ENUM('student','admin') NOT NULL DEFAULT 'student',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. categories: Painting, Drawing, ...
CREATE TABLE IF NOT EXISTS categories (
    id   INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
);

-- 3. artworks: uploaded by students, approved by the admin
CREATE TABLE IF NOT EXISTS artworks (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT NOT NULL,
    category_id INT NOT NULL,
    title       VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    materials   VARCHAR(150),
    image       VARCHAR(255) NOT NULL,             -- file name inside the uploads/ folder
    status      ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id)     REFERENCES users(id) ON DELETE CASCADE,   -- delete user = delete their artworks
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- starting categories
INSERT IGNORE INTO categories (name) VALUES
('Painting'), ('Drawing'), ('Handcrafts'), ('Digital Art'), ('Photography'), ('Sculpture');
