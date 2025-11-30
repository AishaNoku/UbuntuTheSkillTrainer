<?php

if (!defined('SECURE_ACCESS')) {
    die('Direct access not permitted');
}

function initSecureSession() {
    ini_set('session.use_strict_mode', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_httponly', 1); 
    ini_set('session.cookie_secure', 1);   
    ini_set('session.cookie_samesite', 'Strict'); 
    ini_set('session.cookie_lifetime', 86400);
    ini_set('session.gc_maxlifetime', 86400);
    session_name('UBUNTU_SECURE_SESSION');
    
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isset($_SESSION['initiated'])) {
        session_regenerate_id(true);
        $_SESSION['initiated'] = true;
        $_SESSION['created_at'] = time();
    }
    
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
        session_unset();
        session_destroy();
        header('Location: login.php?timeout=1');
        exit();
    }
    $_SESSION['last_activity'] = time();
    
    validateSession();
}


function validateSession() {
    if (!isset($_SESSION['user_agent'])) {
        $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];
    }
    
    if ($_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
        session_unset();
        session_destroy();
        die('Session validation failed');
    }
    
}


define('DB_HOST', 'localhost');
define('DB_PORT', '3306'); 
define('DB_NAME', 'ubuntu_db');
define('DB_USER', 'ubuntu_app_user'); 
define('DB_PASS', 'Change_This_Strong_Password_123!'); 
define('DB_CHARSET', 'utf8mb4');


define('CSRF_SECRET', 'your_random_secret_key_here_change_this_123456789');

define('PASSWORD_ALGO', PASSWORD_ARGON2ID);
define('PASSWORD_OPTIONS', [
    'memory_cost' => 65536,  
    'time_cost' => 4,       
    'threads' => 3           
]);


define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_LOCKOUT_TIME', 900); 


function getSecureDBConnection() {
    static $pdo = null;
    
    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false, 
                PDO::ATTR_PERSISTENT => false,       
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
    return preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username);
}

function validatePassword($password) {
    return strlen($password) >= 8 &&
           preg_match('/[A-Z]/', $password) &&
           preg_match('/[a-z]/', $password) &&
           preg_match('/[0-9]/', $password) &&
           preg_match('/[\W]/', $password);
}


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
    
    if (time() - $_SESSION['csrf_token_time'] > 3600) {
        unset($_SESSION['csrf_token']);
        unset($_SESSION['csrf_token_time']);
        return false;
    }
    
    return hash_equals($_SESSION['csrf_token'], $token);
}

function isLoggedIn() {
    return isset($_SESSION['user_id']) && isset($_SESSION['username']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
        exit();
    }
}

function getUserId() {
    return $_SESSION['user_id'] ?? null;
}

function getUsername() {
    return $_SESSION['username'] ?? null;
}

// ============================================
// 9. RATE LIMITING (LOGIN ATTEMPTS)
// ============================================

function checkRateLimit($username) {
    $pdo = getSecureDBConnection();
    
    // Clean old attempts (older than lockout time)
    $stmt = $pdo->prepare("
        DELETE FROM login_attempts 
        WHERE attempted_at < DATE_SUB(NOW(), INTERVAL ? SECOND)
    ");
    $stmt->execute([LOGIN_LOCKOUT_TIME]);
    
    // Check recent attempts
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as attempts 
        FROM login_attempts 
        WHERE username = ? 
        AND attempted_at > DATE_SUB(NOW(), INTERVAL ? SECOND)
    ");
    $stmt->execute([$username, LOGIN_LOCKOUT_TIME]);
    $result = $stmt->fetch();
    
    return $result['attempts'] < MAX_LOGIN_ATTEMPTS;
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


?>
