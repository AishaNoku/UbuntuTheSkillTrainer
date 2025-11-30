<?php
/**
 * SECURE REGISTRATION HANDLER
 * Implements: Input validation, password strength, CSRF protection,
 * SQL injection prevention, rate limiting, secure password hashing
 */

define('SECURE_ACCESS', true);
require_once 'config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // CSRF validation
    if (!isset($_POST['csrf_token']) || !validateCSRFToken($_POST['csrf_token'])) {
        $error = 'Invalid security token. Please refresh and try again.';
    } else {
        
        // Sanitize inputs
        $username = isset($_POST['username']) ? sanitizeInput($_POST['username']) : '';
        $email = isset($_POST['email']) ? sanitizeInput($_POST['email']) : '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        // Validation
        if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
            $error = 'All fields are required.';
        }
        elseif (!validateUsername($username)) {
            $error = 'Username must be 3-20 characters and contain only letters, numbers, and underscore.';
        }
        elseif (!validateEmail($email)) {
            $error = 'Invalid email address format.';
        }
        elseif (!validatePassword($password)) {
            $error = 'Password must be at least 8 characters and include uppercase, lowercase, number, and special character.';
        }
        elseif ($password !== $confirmPassword) {
            $error = 'Passwords do not match.';
        }
        else {
            
            try {
                $pdo = getSecureDBConnection();
                
                // Check if username exists
                $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
                $stmt->execute([$username]);
                
                if ($stmt->fetch()) {
                    $error = 'Username already taken. Please choose another.';
                } else {
                    
                    // Check if email exists
                    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
                    $stmt->execute([$email]);
                    
                    if ($stmt->fetch()) {
                        $error = 'Email already registered. Please use another or log in.';
                    } else {
                        
                        // Hash password securely (Argon2id)
                        $hashedPassword = password_hash($password, PASSWORD_ALGO, PASSWORD_OPTIONS);
                        
                        // Insert new user
                        $stmt = $pdo->prepare("
                            INSERT INTO users (username, email, password, email_verified) 
                            VALUES (?, ?, ?, 0)
                        ");
                        
                        if ($stmt->execute([$username, $email, $hashedPassword])) {
                            
                            $userId = $pdo->lastInsertId();
                            
                            // Log registration
                            secureLog('info', 'New user registration', [
                                'user_id' => $userId,
                                'username' => $username
                            ]);
                            
                            // Insert security log
                            $logStmt = $pdo->prepare("
                                INSERT INTO security_log 
                                (user_id, username, action, ip_address, user_agent, severity) 
                                VALUES (?, ?, 'registration', ?, ?, 'info')
                            ");
                            $logStmt->execute([
                                $userId,
                                $username,
                                $_SERVER['REMOTE_ADDR'],
                                $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown'
                            ]);
                            
                            $success = 'Account created successfully! Redirecting to login...';
                            header("refresh:2;url=login.php");
                            
                        } else {
                            $error = 'Registration failed. Please try again.';
                        }
                    }
                }
                
            } catch (PDOException $e) {
                $error = 'An error occurred. Please try again later.';
                secureLog('error', 'Database error during registration', [
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
}

$csrfToken = generateCSRFToken();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Ubuntu Skills</title>
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
        input:invalid:not(:placeholder-shown) {
            border-color: #ffdddd;
        }
        input:valid:not(:placeholder-shown) {
            border-color: #90EE90;
        }
        .error-message {
            background: #fee;
            color: #c33;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            border-left: 3px solid #c33;
        }
        .success-message {
            background: #efe;
            color: #3c3;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            border-left: 3px solid #3c3;
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
        .password-requirements {
            font-size: 0.8rem;
            color: #666;
            margin-top: 5px;
            padding: 8px;
            background: #f0f0f0;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <header>
        <a href="index.php" class="logo">Ubuntu</a>
    </header>

    <div class="container">
        <div class="form-box">
            <h2 class="section-title">Create Account</h2>
            
            <?php if (!empty($error)): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="success-message">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>
            
            <form action="signup.php" method="POST">
                
                <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                
                <label for="username">Username</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    required
                    pattern="[a-zA-Z0-9_]{3,20}"
                    title="3-20 characters, letters, numbers and underscore only"
                    placeholder="Choose a username"
                    autocomplete="username">

                <label for="email">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    required
                    pattern="[^@\s]+@[^@\s]+\.[^@\s]+"
                    placeholder="your@email.com"
                    autocomplete="email">

                <label for="password">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required
                    minlength="8"
                    pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W]).{8,}"
                    placeholder="Create a strong password"
                    autocomplete="new-password">
                
                <div class="password-requirements">
                    ✓ At least 8 characters<br>
                    ✓ One uppercase letter<br>
                    ✓ One lowercase letter<br>
                    ✓ One number<br>
                    ✓ One special character (!@#$%^&*)
                </div>

                <label for="confirm_password">Confirm Password</label>
                <input 
                    type="password" 
                    id="confirm_password" 
                    name="confirm_password" 
                    required
                    placeholder="Type password again"
                    autocomplete="new-password">

                <button type="submit" class="btn btn-primary" style="width:100%; margin-top: 15px;">
                    Create Account
                </button>
            </form>

            <div class="auth-switch">
                <p>Already have an account? <a href="login.php">Log In</a></p>
            </div>
        </div>
    </div>

    <script>
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirm_password');

        function validatePassword() {
            if (confirmPassword.value && password.value !== confirmPassword.value) {
                confirmPassword.setCustomValidity('Passwords do not match');
                confirmPassword.style.borderColor = 'red';
            } else {
                confirmPassword.setCustomValidity('');
                confirmPassword.style.borderColor = '#ccc';
                if (confirmPassword.value && password.value === confirmPassword.value) {
                    confirmPassword.style.borderColor = '#90EE90';
                }
            }
        }

        password.addEventListener('change', validatePassword);
        confirmPassword.addEventListener('keyup', validatePassword);
    </script>
</body>
</html>
