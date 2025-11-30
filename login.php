<?php

// Define secure access constant
define('SECURE_ACCESS', true);

// Include secure configuration
require_once 'config.php';

// Initialize response
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    
    if (!isset($_POST['csrf_token']) || !validateCSRFToken($_POST['csrf_token'])) {
        $error = 'Invalid security token. Please refresh and try again.';
        secureLog('warning', 'CSRF token validation failed', [
            'ip' => $_SERVER['REMOTE_ADDR']
        ]);
    } else {
        
        $username = isset($_POST['username']) ? sanitizeInput($_POST['username']) : '';
        $password = $_POST['password'] ?? '';
        
        // Validate inputs
        if (empty($username) || empty($password)) {
            $error = 'Username and password are required.';
        } 
        elseif (!validateUsername($username)) {
            $error = 'Invalid username format.';
        } 
        else {

            
            if (!checkRateLimit($username)) {
                $error = 'Too many login attempts. Please try again in 15 minutes.';
                
                secureLog('warning', 'Rate limit exceeded for login', [
                    'username' => $username,
                    'ip' => $_SERVER['REMOTE_ADDR']
                ]);
            } 
            else {

                
                try {
                    $pdo = getSecureDBConnection();
                    
                    // Use prepared statement to prevent SQL injection
                    $stmt = $pdo->prepare("
                        SELECT id, username, password, account_status, email_verified 
                        FROM users 
                        WHERE username = ? 
                        LIMIT 1
                    ");
                    
                    $stmt->execute([$username]);
                    $user = $stmt->fetch();
                    
                    
                    if ($user && password_verify($password, $user['password'])) {
                        
                        // Check account status
                        if ($user['account_status'] !== 'active') {
                            $error = 'Your account has been suspended. Contact support.';
                            
                            secureLog('warning', 'Login attempt on suspended account', [
                                'username' => $username
                            ]);
                            
                        } elseif ($user['email_verified'] == 0) {
                            $error = 'Please verify your email before logging in.';
                            
                        } else {
                            
                            session_regenerate_id(true);
                            
                            // Set session variables
                            $_SESSION['user_id'] = $user['id'];
                            $_SESSION['username'] = $user['username'];
                            $_SESSION['logged_in'] = true;
                            $_SESSION['login_time'] = time();
                            
                            // Update last login time
                            $updateStmt = $pdo->prepare("
                                UPDATE users 
                                SET last_login = NOW(), 
                                    failed_login_attempts = 0 
                                WHERE id = ?
                            ");
                            $updateStmt->execute([$user['id']]);
                            
                            // Record successful login attempt
                            recordLoginAttempt($username, true);
                            
                            // Log successful login
                            secureLog('info', 'Successful login', [
                                'user_id' => $user['id'],
                                'username' => $username
                            ]);
                            
                            // Insert into security log
                            $logStmt = $pdo->prepare("
                                INSERT INTO security_log 
                                (user_id, username, action, ip_address, user_agent, severity) 
                                VALUES (?, ?, 'login', ?, ?, 'info')
                            ");
                            $logStmt->execute([
                                $user['id'],
                                $username,
                                $_SERVER['REMOTE_ADDR'],
                                $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown'
                            ]);
                            
                            // Redirect to home
                            $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'index.php';
                            
                            // Sanitize redirect URL 
                            if (!preg_match('/^[a-zA-Z0-9_\-\.\/]+\.php$/', $redirect)) {
                                $redirect = 'index.php';
                            }
                            
                            header('Location: ' . $redirect);
                            exit();
                        }
                        
                    } else {
                        
                        $error = 'Invalid username or password.';
                        
                        // Record failed login attempt
                        recordLoginAttempt($username, false);
                        
                        // Update failed attempts counter if user exists
                        if ($user) {
                            $failStmt = $pdo->prepare("
                                UPDATE users
                                SET failed_login_attempts = failed_login_attempts + 1 
                                WHERE id = ?
                            ");
                            $failStmt->execute([$user['id']]);
                            
                            // Lock account after 10 failed attempts
                            $checkStmt = $pdo->prepare("
                                SELECT failed_login_attempts FROM users WHERE id = ?
                            ");
                            $checkStmt->execute([$user['id']]);
                            $attempts = $checkStmt->fetchColumn();
                            
                            if ($attempts >= 10) {
                                $lockStmt = $pdo->prepare("
                                    UPDATE users 
                                    SET account_status = 'locked' 
                                    WHERE id = ?
                                ");
                                $lockStmt->execute([$user['id']]);
                                
                                $error = 'Account locked due to too many failed attempts. Contact support.';
                                
                                secureLog('error', 'Account locked due to failed attempts', [
                                    'username' => $username
                                ]);
                            }
                        }
                        
                        secureLog('warning', 'Failed login attempt', [
                            'username' => $username,
                            'ip' => $_SERVER['REMOTE_ADDR']
                        ]);
                    }
                    
                } catch (PDOException $e) {
                    $error = 'An error occurred. Please try again later.';
                    secureLog('error', 'Database error during login', [
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }
    }
}


$csrfToken = generateCSRFToken();

$timedOut = isset($_GET['timeout']) ? true : false;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Ubuntu Skills</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .form-box {
            max-width: 400px;
            margin: 50px auto;
            padding: 30px;
            background: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .error-message {
            background: #fee;
            color: #c33;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            border-left: 3px solid #c33;
        }
        .info-message {
            background: #eff6ff;
            color: #1e40af;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            border-left: 3px solid #3b82f6;
        }
        .auth-switch {
            margin-top: 20px;
            text-align: center;
            font-size: 0.9rem;
        }
        .auth-switch a {
            color: var(--accent);
            font-weight: bold;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <header>
        <a href="index.php" class="logo">Ubuntu</a>
    </header>

    <div class="container">
        <div class="form-box">
            <h2 class="section-title">Welcome Back</h2>
            
            <?php if ($timedOut): ?>
                <div class="info-message">
                    Your session has expired. Please log in again.
                </div>
            <?php endif; ?>
            
            <?php if (!empty($error)): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <form action="login.php<?php echo isset($_GET['redirect']) ? '?redirect=' . urlencode($_GET['redirect']) : ''; ?>" method="POST">
                
               
                <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                
                <label for="username">Username</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    required 
                    autocomplete="username"
                    pattern="[a-zA-Z0-9_]{3,20}"
                    title="3-20 characters, letters, numbers, and underscore only"
                    value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">

                <label for="password">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required
                    autocomplete="current-password">

                <button type="submit" class="btn btn-primary" style="width:100%; margin-top: 15px;">
                    Log In
                </button>
            </form>

            <div class="auth-switch">
                <p>New here? <a href="signup.php">Create an Account</a></p>
                <p><a href="forgot-password.php">Forgot Password?</a></p>
            </div>
        </div>
    </div>
</body>
</html>
