<?php
/**
 * SECURE LOGOUT HANDLER
 * Properly destroys session and clears all session data
 */

// Define this constant so config.php knows it's safe to load
define('SECURE_ACCESS', true);

// This file starts the session automatically because of your config.php logic
require_once 'config.php';

// 1. LOG THE ACTION (Best Effort)
// We try to save this to the database, but wrap it in try/catch
// so that if the database fails, the user is STILL logged out.
if (isset($_SESSION['user_id'])) {
    try {
        $pdo = getSecureDBConnection();
        $stmt = $pdo->prepare("
            INSERT INTO security_log 
            (user_id, username, action, ip_address, severity) 
            VALUES (?, ?, 'logout', ?, 'info')
        ");
        
        $stmt->execute([
            $_SESSION['user_id'],
            $_SESSION['username'] ?? 'Unknown',
            $_SERVER['REMOTE_ADDR']
        ]);
        
    } catch (Exception $e) {
        // Silently fail logging - getting the user logged out is more important!
    }
}

// 2. UNSET VARIABLES
// Clears the $_SESSION array in PHP's memory
$_SESSION = array();

// 3. DESTROY THE COOKIE
// This is the "Key" stored in the user's browser. We must delete it.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. DESTROY THE SESSION
// Deletes the file on the server
session_destroy();

// 5. REDIRECT
// Send them back to the homepage
header("Location: index.php");
exit();
?>