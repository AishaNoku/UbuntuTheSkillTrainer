<?php
/**
 * SECURE CONFIGURATION FILE
 * This file contains all security-critical configuration
 * Never commit this file to version control - add to .gitignore
 */

// Prevent direct access
if (!defined('SECURE_ACCESS')) {
    die('Direct access not permitted');
}

// ============================================
// 1. SESSION SECURITY CONFIGURATION
// ============================================

// Start secure session with strict settings
function initSecureSession() {
    // Prevent session fixation attacks
    ini_set('session.use_strict_mode', 1);
    
    // Use only cookies for session (not URL)
    ini_set('session.use_only_cookies', 1);
    
    // Set secure cookie parameters
    ini_set('session.cookie_httponly', 1); // Prevent XSS access to session cookie
    ini_set('session.cookie_secure', 1);   // Only send over HTTPS (set to 0 for localhost testing)
    ini_set('session.cookie_samesite', 'Strict'); // CSRF protection
    
    // Session cookie lifetime (24 hours)
    ini_set('session.cookie_lifetime', 86400);
    
    // Regenerate session ID periodically
    ini_set('session.gc_maxlifetime', 86400);
    
    // Use strong session name
    session_name('UBUNTU_SECURE_SESSION');
    
    // Start session
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Regenerate session ID on login to prevent fixation
    if (!isset($_SESSION['initiated'])) {
        session_regenerate_id(true);
        $_SESSION['initiated'] = true;
        $_SESSION['created_at'] = time();
    }
    
    // Session timeout (30 minutes of inactivity)
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
        session_unset();
        session_destroy();
        header('Location: login.php?timeout=1');
        exit();
    }
    $_SESSION['last_activity'] = time();
    
    // Validate session integrity
    validateSession();
}

// ============================================
// 2. SESSION VALIDATION
// ============================================

function validateSession() {
    // Check if session has valid user agent and IP
    if (!isset($_SESSION['user_agent'])) {
        $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];
    }
    
    // Detect session hijacking attempts
    if ($_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
        session_unset();
        session_destroy();
        die('Session validation failed');
    }
    
    // Optional: Also check IP (may cause issues with mobile networks)
    // Uncomment if needed:
    // if ($_SESSION['ip_address'] !== $_SERVER['REMOTE_ADDR']) {
    //     session_unset();
    //     session_destroy();
    //     die('Session validation failed');
    // }
}

// ============================================
// 3. DATABASE CONFIGURATION (SECURE)
// ============================================

define('DB_HOST', 'localhost');
define('DB_PORT', '3306'); // Change to 3307 if using alternate port
define('DB_NAME', 'ubuntu_db');
define('DB_USER', 'ubuntu_app_user'); // DO NOT use 'root' in production
define('DB_PASS', 'Change_This_Strong_Password_123!'); // CHANGE THIS!
define('DB_CHARSET', 'utf8mb4');

// ============================================
// 4. SECURITY CONSTANTS
// ============================================

// CSRF Token secret (change this to a random string)
define('CSRF_SECRET', 'your_random_secret_key_here_change_this_123456789');

// Password hashing settings
define('PASSWORD_ALGO', PASSWORD_ARGON2ID); // Most secure
define('PASSWORD_OPTIONS', [
    'memory_cost' => 65536,  // 64 MB
    'time_cost' => 4,        // 4 iterations
    'threads' => 3           // 3 parallel threads
]);

// Rate limiting (login attempts)
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_LOCKOUT_TIME', 900); // 15 minutes

// ============================================
// 5. SECURE DATABASE CONNECTION
// ============================================

function getSecureDBConnection() {
    static $pdo = null;
    
    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false, // Use real prepared statements
                PDO::ATTR_PERSISTENT => false,       // Don't reuse connections
            ];
            
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
            
        } catch (PDOException $e) {
            // Log error securely (never display to user)
            error_log('Database connection failed: ' . $e->getMessage());
            die('Database connection error. Please contact support.');
        }
    }
    
    return $pdo;
}

// ============================================
// 6. INPUT SANITIZATION & VALIDATION
// ============================================

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function validateUsername($username) {
    // Only alphanumeric and underscore, 3-20 characters
    return preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username);
}

function validatePassword($password) {
    // At least 8 chars, 1 uppercase, 1 lowercase, 1 number, 1 special char
    return strlen($password) >= 8 &&
           preg_match('/[A-Z]/', $password) &&
           preg_match('/[a-z]/', $password) &&
           preg_match('/[0-9]/', $password) &&
           preg_match('/[\W]/', $password);
}

// ============================================
// 7. CSRF PROTECTION
// ============================================

function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $_SESSION['csrf_token_time'] = time();
    }
    return $_SESSION['csrf_token'];
}

function validateCSRFToken($token) {
    if (!isset($_SESSION['csrf_token']) || !isset($_SESSION['csrf_token_time'])) {
        return false;
    }
    
    // Token expires after 1 hour
    if (time() - $_SESSION['csrf_token_time'] > 3600) {
        unset($_SESSION['csrf_token']);
        unset($_SESSION['csrf_token_time']);
        return false;
    }
    
    return hash_equals($_SESSION['csrf_token'], $token);
}

// ============================================
// 8. AUTHENTICATION HELPERS
// ============================================

function isLoggedIn() {
    return isset($_SESSION['username']);
}

function getUsername() {
    return isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
}

function recordLoginAttempt($username, $success = false) {
    $pdo = getSecureDBConnection();
    
    $stmt = $pdo->prepare("
        INSERT INTO login_attempts (username, ip_address, success, attempted_at) 
        VALUES (?, ?, ?, NOW())
    ");
    $stmt->execute([
        $username,
        $_SERVER['REMOTE_ADDR'],
        $success ? 1 : 0
    ]);
    
    // If successful, clear failed attempts
    if ($success) {
        $stmt = $pdo->prepare("
            DELETE FROM login_attempts 
            WHERE username = ? AND success = 0
        ");
        $stmt->execute([$username]);
    }
}

// ============================================
// 10. SECURE LOGGING
// ============================================

function secureLog($level, $message, $context = []) {
    $logFile = __DIR__ . '/logs/security.log';
    $logDir = dirname($logFile);
    
    // Create logs directory if it doesn't exist
    if (!is_dir($logDir)) {
        mkdir($logDir, 0750, true);
    }
    
    $timestamp = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $user = $_SESSION['username'] ?? 'anonymous';
    
    $logEntry = sprintf(
        "[%s] [%s] [User: %s] [IP: %s] %s %s\n",
        $timestamp,
        strtoupper($level),
        $user,
        $ip,
        $message,
        !empty($context) ? json_encode($context) : ''
    );
    
    error_log($logEntry, 3, $logFile);
}

// ============================================
// 11. SECURITY HEADERS
// ============================================

function setSecurityHeaders() {
    // Prevent XSS attacks
    header("X-XSS-Protection: 1; mode=block");
    
    // Prevent clickjacking
    header("X-Frame-Options: DENY");
    
    // Prevent MIME sniffing
    header("X-Content-Type-Options: nosniff");
    
    // Strict Transport Security (HTTPS only)
    header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
    
    // Content Security Policy (adjust as needed)
    header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data: https:; frame-src 'self' https://www.youtube.com;");
    
    // Referrer Policy
    header("Referrer-Policy: strict-origin-when-cross-origin");
    
    // Permissions Policy
    header("Permissions-Policy: geolocation=(), microphone=(), camera=()");
}

// ============================================
// 12. ERROR HANDLING (PRODUCTION)
// ============================================

function setupProductionErrorHandling() {
    // Don't display errors to users
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(E_ALL);
    
    // Log errors instead
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/logs/php-errors.log');
}

// ============================================
// INITIALIZATION
// ============================================

// Set security headers on every request
setSecurityHeaders();

// Initialize secure session
initSecureSession();

// Setup error handling (comment out for development)
// setupProductionErrorHandling();

?>
