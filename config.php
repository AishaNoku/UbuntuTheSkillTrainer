<?php

if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('Direct access not permitted');
}


define('DB_HOST', 'localhost');
<<<<<<< HEAD
define('DB_NAME', 'webtech_2025A_rachel_murambiwa');
define('DB_USER', 'rachel.murambiwa'); 
define('DB_PASS', 'Chacha@1583');     
=======
define('DB_NAME', 'ubuntu_db');
define('DB_USER', 'root');
define('DB_PASS', '');
>>>>>>> 0fe5762ef4889da9735ff9fa2e8ebb8fbe22474d
define('DB_CHARSET', 'utf8mb4');

function setSecurityHeaders() {
    header("X-XSS-Protection: 1; mode=block");
    header("X-Frame-Options: DENY");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: strict-origin-when-cross-origin");
}
setSecurityHeaders();


function initSecureSession() {

    if (session_status() === PHP_SESSION_NONE) {
        ini_set('session.use_strict_mode', 1);
        ini_set('session.use_only_cookies', 1);
        ini_set('session.cookie_httponly', 1);
        ini_set('session.cookie_secure', 0);
        ini_set('session.cookie_samesite', 'Strict');
        
        ini_set('session.gc_maxlifetime', 86400);
        ini_set('session.cookie_lifetime', 86400);
        
        session_name('UBUNTU_SECURE_SESSION');
        session_start();
    }

    if (!isset($_SESSION['initiated'])) {
        session_regenerate_id(true);
        $_SESSION['initiated'] = true;
    }

    // Session Timeout (30 mins)
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
        session_unset();
        session_destroy();
        // Redirect
        if (basename($_SERVER['PHP_SELF']) !== 'login.php') {
            header('Location: login.php?timeout=1');
            exit();
        }
    }
    $_SESSION['last_activity'] = time();
}
initSecureSession();

function getSecureDBConnection() {
    static $pdo = null;
    
    if ($pdo === null) {
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            // Attempt 3306
            $dsn = "mysql:host=" . DB_HOST . ";port=3306;dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            try {
                // Attempt3307
                $dsn = "mysql:host=" . DB_HOST . ";port=3307;dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
                $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
            } catch (PDOException $e2) {
                error_log('Database connection failed: ' . $e2->getMessage());
                die('System error: Unable to connect to database. Please contact support.');
            }
        }
    }
    return $pdo;
}

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

function validateUsername($username) {
    return preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username);
}
function validatePassword($password) {
    return strlen($password) >= 8 &&
           preg_match('/[A-Z]/', $password) && // At least one Uppercase
           preg_match('/[a-z]/', $password) && // At least one Lowercase
           preg_match('/[0-9]/', $password) && // At least one Number
           preg_match('/[\W]/', $password);    // At least one Special Symbol
}

function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCSRFToken($token) {
    if (!isset($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

function checkRateLimit($username) {
    try {
        $pdo = getSecureDBConnection();
        $ip = $_SERVER['REMOTE_ADDR'];
        
        // Count failures in last 15mins
        $stmt = $pdo->prepare("
            SELECT COUNT(*) FROM login_attempts 
            WHERE ip_address = ? AND success = 0 
            AND attempted_at > (NOW() - INTERVAL 15 MINUTE)
        ");
        $stmt->execute([$ip]);
        $count = $stmt->fetchColumn();

        return $count < 5; // Allow 5 attempts
    } catch (Exception $e) {
        return true; // Fail open if error
    }
}

function recordLoginAttempt($username, $success = false) {
    try {
        $pdo = getSecureDBConnection();
        $stmt = $pdo->prepare("
            INSERT INTO login_attempts (username, ip_address, success, attempted_at) 
            VALUES (?, ?, ?, NOW())
        ");
        $stmt->execute([$username, $_SERVER['REMOTE_ADDR'], $success ? 1 : 0]);
        
    } catch (Exception $e) {
    }
}

function secureLog($level, $message, $context = []) {
    try {
        $pdo = getSecureDBConnection();
        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'system';
        
        $stmt = $pdo->prepare("
            INSERT INTO security_log 
            (user_id, username, action, ip_address, details, severity) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $user_id,
            $username,
            $message,
            $_SERVER['REMOTE_ADDR'],
            json_encode($context),
            $level
        ]);
    } catch (Exception $e) {
        // Fallback to PHP error log if DB is down
        error_log("SECURE LOG [$level]: $message " . json_encode($context));
    }
}

function isLoggedIn() {
    return isset($_SESSION['username']);
}

function getUsername() {
    return isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
}

function getUserId() {
    return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
}
?>