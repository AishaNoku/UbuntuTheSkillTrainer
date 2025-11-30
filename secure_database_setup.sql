-- =====================================================
-- SECURE DATABASE SETUP FOR UBUNTU SKILLS PLATFORM
-- =====================================================
-- Run this script to create a secure database structure
-- with proper user privileges and security tables

-- =====================================================
-- 1. CREATE DATABASE
-- =====================================================

CREATE DATABASE IF NOT EXISTS ubuntu_db 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE ubuntu_db;

-- =====================================================
-- 2. CREATE USERS TABLE (WITH SECURITY ENHANCEMENTS)
-- =====================================================

DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,  -- Stores hashed password (Argon2id)
    email_verified TINYINT(1) DEFAULT 0,
    account_status ENUM('active', 'suspended', 'locked') DEFAULT 'active',
    failed_login_attempts INT DEFAULT 0,
    last_login DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_email (email),
    INDEX idx_status (account_status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 3. CREATE LOGIN ATTEMPTS TABLE (RATE LIMITING)
-- =====================================================

DROP TABLE IF EXISTS login_attempts;

CREATE TABLE login_attempts (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,  -- Supports IPv6
    success TINYINT(1) DEFAULT 0,
    attempted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_username_time (username, attempted_at),
    INDEX idx_ip_time (ip_address, attempted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 4. CREATE SESSIONS TABLE (SERVER-SIDE SESSION STORAGE)
-- =====================================================

DROP TABLE IF EXISTS sessions;

CREATE TABLE sessions (
    id VARCHAR(128) NOT NULL PRIMARY KEY,
    user_id INT(11) UNSIGNED NULL,
    ip_address VARCHAR(45) NOT NULL,
    user_agent VARCHAR(255) NOT NULL,
    payload TEXT NOT NULL,
    last_activity INT(11) UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user_id (user_id),
    INDEX idx_last_activity (last_activity),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 5. CREATE COURSES TABLE
-- =====================================================

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

-- =====================================================
-- 6. INSERT COURSES DATA
-- =====================================================

INSERT INTO courses (title, description, icon, folder_name) VALUES
('Crocheting', 'Learn how to turn simple yarn into beautiful, handmade creations while building patience, creativity, and fine motor skills.', '🧶', 'crochet'),
('Bead Making', 'Create stunning handmade beaded jewellery, learn the art of jewellery design and level up your craft into a business.', '🎨', 'beads'),
('Mbira Tutorials', 'Learn to compose, mix, and produce music using the Zimbabwean instrument called mbira.', '🎵', 'mbira'),
('Henna Designs', 'Master the art of beautiful henna designs, learn the cultural relevance of the craft and how to monetize it.', '📷', 'henna');

-- =====================================================
-- 7. CREATE USER PROGRESS TABLE
-- =====================================================

DROP TABLE IF EXISTS user_progress;

CREATE TABLE user_progress (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) UNSIGNED NOT NULL,
    course_id INT(11) UNSIGNED NOT NULL,
    module_id VARCHAR(50) NOT NULL,
    completed TINYINT(1) DEFAULT 0,
    completed_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_progress (user_id, course_id, module_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    INDEX idx_user_course (user_id, course_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 8. CREATE SECURITY AUDIT LOG TABLE
-- =====================================================

DROP TABLE IF EXISTS security_log;

CREATE TABLE security_log (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) UNSIGNED NULL,
    username VARCHAR(50) NULL,
    action VARCHAR(100) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    user_agent VARCHAR(255) NULL,
    details TEXT NULL,
    severity ENUM('info', 'warning', 'error', 'critical') DEFAULT 'info',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user_id (user_id),
    INDEX idx_action (action),
    INDEX idx_severity (severity),
    INDEX idx_created (created_at),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 9. CREATE PASSWORD RESET TOKENS TABLE
-- =====================================================

DROP TABLE IF EXISTS password_resets;

CREATE TABLE password_resets (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) UNSIGNED NOT NULL,
    token VARCHAR(64) NOT NULL UNIQUE,
    expires_at DATETIME NOT NULL,
    used TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_token (token),
    INDEX idx_expires (expires_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 10. CREATE APPLICATION USER (LEAST PRIVILEGE)
-- =====================================================
-- This user has ONLY the permissions needed for the app
-- Replace 'your_secure_password_here' with a strong password

-- Drop user if exists (MySQL 8.0+)
DROP USER IF EXISTS 'ubuntu_app_user'@'localhost';

-- Create limited privilege user
CREATE USER 'ubuntu_app_user'@'localhost' 
IDENTIFIED BY 'Change_This_Strong_Password_123!';

-- Grant only necessary permissions
GRANT SELECT, INSERT, UPDATE ON ubuntu_db.users TO 'ubuntu_app_user'@'localhost';
GRANT SELECT, INSERT, DELETE ON ubuntu_db.login_attempts TO 'ubuntu_app_user'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON ubuntu_db.sessions TO 'ubuntu_app_user'@'localhost';
GRANT SELECT ON ubuntu_db.courses TO 'ubuntu_app_user'@'localhost';
GRANT SELECT, INSERT, UPDATE ON ubuntu_db.user_progress TO 'ubuntu_app_user'@'localhost';
GRANT INSERT ON ubuntu_db.security_log TO 'ubuntu_app_user'@'localhost';
GRANT SELECT, INSERT, UPDATE ON ubuntu_db.password_resets TO 'ubuntu_app_user'@'localhost';

-- Apply privileges
FLUSH PRIVILEGES;

-- =====================================================
-- 11. INSERT SAMPLE SECURE USER (FOR TESTING)
-- =====================================================
-- Password: TestUser123!
-- This uses Argon2id hashing (most secure)

INSERT INTO users (username, email, password, email_verified, account_status) 
VALUES (
    'testuser',
    'test@ubuntuskills.com',
    '$argon2id$v=19$m=65536,t=4,p=3$VGVzdFNhbHQxMjM0NTY3OA$+xJcH8Y0Q7RxQc9ZGzN5JrPKmJ0sWq8LGy0qR3pN7Xw',
    1,
    'active'
);

-- =====================================================
-- 12. CREATE CLEANUP EVENTS (AUTO-DELETE OLD DATA)
-- =====================================================

-- Enable event scheduler
SET GLOBAL event_scheduler = ON;

-- Delete old login attempts (older than 7 days)
DROP EVENT IF EXISTS cleanup_old_login_attempts;
CREATE EVENT cleanup_old_login_attempts
ON SCHEDULE EVERY 1 DAY
DO
DELETE FROM login_attempts 
WHERE attempted_at < DATE_SUB(NOW(), INTERVAL 7 DAY);

-- Delete expired sessions (older than 1 day)
DROP EVENT IF EXISTS cleanup_expired_sessions;
CREATE EVENT cleanup_expired_sessions
ON SCHEDULE EVERY 1 HOUR
DO
DELETE FROM sessions 
WHERE last_activity < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 1 DAY));

-- Delete expired password reset tokens
DROP EVENT IF EXISTS cleanup_expired_reset_tokens;
CREATE EVENT cleanup_expired_reset_tokens
ON SCHEDULE EVERY 6 HOUR
DO
DELETE FROM password_resets 
WHERE expires_at < NOW() OR used = 1;

-- =====================================================
-- SETUP COMPLETE
-- =====================================================

SELECT 'Database setup completed successfully!' AS status;
SELECT '⚠️  IMPORTANT: Change the default password for ubuntu_app_user!' AS warning;
SELECT 'Update the password in config.php file' AS action_required;
