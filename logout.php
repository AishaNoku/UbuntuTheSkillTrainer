<?php
/**
 * SECURE LOGOUT HANDLER
 * Properly destroys session and clears all session data
 */

define('SECURE_ACCESS', true);
require_once 'config.php';

// Log the logout action
if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
    secureLog('info', 'User logged out', [
        'user_id' => $_SESSION['user_id'],
        'username' => $_SESSION['username']
    ]);
    
    try {
        $pdo = getSecureDBConnection();
        $stmt = $pdo->prepare("
            INSERT INTO security_log 
            (user_id, username, action, ip_address, user_agent, severity) 
            VALUES (?, ?, 'logout', ?, ?, 'info')
        ");
        $stmt->execute([
            $_SESSION['user_id'],
            $_SESSION['username'],
            $_SERVER['REMOTE_ADDR'],
            $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown'
        ]);
    } catch (PDOException $e) {
        // Log error but don't stop logout
        error_log('Logout logging failed: ' . $e->getMessage());
    }
}

// Unset all session variables
$_SESSION = array();

// Delete session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy the session
session_destroy();

// Redirect to home page
header('Location: index.php');
exit();
?>
