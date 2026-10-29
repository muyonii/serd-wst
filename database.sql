CREATE DATABASE IF NOT EXISTS serds_demo;
USE serds_demo;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS incidents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    incident_type VARCHAR(20) NOT NULL,
    location VARCHAR(150) NOT NULL,
    description TEXT,
    status VARCHAR(20) NOT NULL DEFAULT 'Pending',
    reported_by VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- demo login: admin / admin123
INSERT INTO users (username, password) VALUES
('admin', '$2y$10$8Zuyop2OYlWi72cPMD1Tee2wdreIdHwxFHObTNZbmhNiLYdL/bYu.');
