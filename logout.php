<?php

define('SECURE_ACCESS', true);

require_once 'config.php';

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
        
    }
}

$_SESSION = array();


if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();

header("Location: index.php");
exit();
?>