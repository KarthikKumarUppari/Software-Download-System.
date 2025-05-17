CREATE DATABASE IF NOT EXISTS software_db;
USE software_db;

CREATE TABLE IF NOT EXISTS software (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    description TEXT,
    file_path VARCHAR(255),
    version VARCHAR(50),
    platform VARCHAR(50),
    uploaded_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE,
    password VARCHAR(255)
);
