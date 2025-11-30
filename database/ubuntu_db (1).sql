
SET FOREIGN_KEY_CHECKS = 0;

CREATE DATABASE IF NOT EXISTS ubuntu_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ubuntu_db;


-- 1. USERS
DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
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

-- 2. LOGIN ATTEMPTS
DROP TABLE IF EXISTS login_attempts;
CREATE TABLE login_attempts (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    success TINYINT(1) DEFAULT 0,
    attempted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_username_time (username, attempted_at),
    INDEX idx_ip_time (ip_address, attempted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. SESSIONS
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

-- 4. SECURITY LOG
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

-- 5. PASSWORD RESETS
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