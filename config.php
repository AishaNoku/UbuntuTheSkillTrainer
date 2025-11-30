<?php

define('DB_HOST', 'localhost');
define('DB_NAME', 'ubuntu_db');
define('DB_USER', 'root'); 
define('DB_PASS', '');


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


function getSecureDBConnection() {
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        $dsn = "mysql:host=" . DB_HOST . ";port=3306;dbname=" . DB_NAME . ";charset=utf8mb4";
        return new PDO($dsn, DB_USER, DB_PASS, $options);

    } catch (PDOException $e) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";port=3307;dbname=" . DB_NAME . ";charset=utf8mb4";
            return new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e2) {
            die("Database connection error. Please contact support.");
        }
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