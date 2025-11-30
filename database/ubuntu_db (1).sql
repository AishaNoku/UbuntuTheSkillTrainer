-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Nov 30, 2025 at 06:39 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- =============================================
-- SECTION B: CONTENT TABLES (Added for Website Features)
-- =============================================

-- 6. COURSES (Updated to match your HTML content)
DROP TABLE IF EXISTS courses;
CREATE TABLE courses (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    icon VARCHAR(50) NOT NULL,
    folder_name VARCHAR(50) NOT NULL UNIQUE,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_folder (folder_name),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO courses (title, description, icon, folder_name) VALUES
('Crocheting', 'Learn how to turn simple yarn into beautiful, handmade creations while building patience, creativity, and fine motor skills.', '🧶', 'crochet'),
('Bead Making', 'Create stunning handmade beaded jewellery, learn the art of jewellery design and level up your craft into a business.', '🎨', 'beads'),
('Mbira Tutorials', 'Learn to compose, mix, and produce music using the Zimbabwean instrument called mbira.', '🎵', 'mbira'),
('Henna Designs', 'Master the art of beautiful henna designs, learn the cultural relevance of the craft and how to monetize it.', '📷', 'henna');

-- 7. MODULES (New! Needed for Progress Tracking)
DROP TABLE IF EXISTS modules;
CREATE TABLE modules (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    course_id INT(11) UNSIGNED NOT NULL,
    title VARCHAR(100) NOT NULL,
    module_order INT(11) NOT NULL,
    video_url VARCHAR(255),
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert Bead Modules (Course ID 2)
INSERT INTO modules (course_id, title, module_order) VALUES
(2, 'Introduction to Beadmaking & Materials', 1),
(2, 'Designing & Creating Beaded Accessories', 2),
(2, 'Finishing, Pricing & Selling', 3);

-- 8. TESTIMONIALS (New! Needed for Homepage)
DROP TABLE IF EXISTS testimonials;
CREATE TABLE testimonials (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    role VARCHAR(100) NOT NULL,
    content TEXT NOT NULL,
    rating INT(1) NOT NULL DEFAULT 5,
    initials VARCHAR(5) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO testimonials (name, role, content, rating, initials) VALUES 
('Tanya M.', 'UI/UX Specialist', 'I really learnt a lot from Ubuntu skills and I keep learning regularly!', 5, 'TM'),
('Sarah J.', 'Entrepreneur', 'The bead making course helped me start my own business in Harare!', 5, 'SJ');

-- 9. USER PROGRESS (Updated to link to Modules table)
DROP TABLE IF EXISTS user_progress;
CREATE TABLE user_progress (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) UNSIGNED NOT NULL,
    course_id INT(11) UNSIGNED NOT NULL,
    module_id INT(11) UNSIGNED NULL, -- Changed to INT to link to modules table
    completed TINYINT(1) DEFAULT 0,
    completed_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    -- We allow module_id to be NULL if you just want to track overall course completion
    FOREIGN KEY (module_id) REFERENCES modules(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- SECTION C: USER & CLEANUP
-- =============================================

-- Insert Test User (Password: "password123")
INSERT INTO users (username, email, password, email_verified, account_status) 
VALUES (
    'testuser',
    'test@ubuntuskills.com',
    '$2y$10$tH.8v4l0k8h5.n5.d5.d5.u5.u5.u5.u5.u5.u5.u5.u5.u5.u5', 
    1,
    'active'
);

-- Re-enable safety checks
SET FOREIGN_KEY_CHECKS = 1;

SELECT 'Database updated successfully! Security + Content tables are ready.' AS status;