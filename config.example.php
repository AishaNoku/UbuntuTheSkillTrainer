<?php
/**
 * CONFIGURATION TEMPLATE
 * Copy this file to config.php and fill in your actual values
 * NEVER commit the real config.php file!
 */

// This is just a template - the real config.php has the actual implementation

/* 
STEP 1: Copy this file
    cp config.example.php config.php

STEP 2: Edit config.php and set:
    - DB_PASS (your database password)
    - CSRF_SECRET (random 32+ character string)
    - session.cookie_secure (0 for localhost, 1 for production HTTPS)

STEP 3: Never commit config.php to Git
    (it's already in .gitignore)

STEP 4: Follow SECURITY_GUIDE.md for complete setup

*/

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'ubuntu_db');
define('DB_USER', 'ubuntu_app_user');
define('DB_PASS', 'CHANGE_THIS_TO_YOUR_ACTUAL_PASSWORD');  // ⚠️ CHANGE THIS!

// Security Configuration
define('CSRF_SECRET', 'CHANGE_THIS_TO_RANDOM_STRING_32_CHARS');  // ⚠️ CHANGE THIS!

// For production with HTTPS, set to 1
// For localhost testing, set to 0
ini_set('session.cookie_secure', 0);  // ⚠️ SET TO 1 IN PRODUCTION!

?>
