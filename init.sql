-- Create database
CREATE DATABASE IF NOT EXISTS lostfound_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE lostfound_db;

-- Create items table
CREATE TABLE IF NOT EXISTS items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    type ENUM('lost', 'found') NOT NULL,
    description TEXT,
    location VARCHAR(255) NOT NULL,
    contact VARCHAR(255) NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Optional: Create admin users table (for future use)
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample data into items table
INSERT INTO items (title, type, description, location, contact, image) VALUES
('Black Wallet', 'lost', 'Black leather wallet with ID and credit cards.', 'Library', 'john.doe@example.com', NULL),
('Set of Keys', 'found', 'Bunch of keys with a red keychain.', 'Cafeteria', 'cafeteria.staff@example.com', NULL),
('Blue Backpack', 'lost', 'Blue backpack with laptop inside.', 'Lecture Hall 3', 'alice@example.com', NULL),
('Silver Ring', 'found', 'Silver ring with engraving found near parking lot.', 'Parking Lot', 'security@example.com', NULL);