<?php

define('SECURE_ACCESS', true);
require_once 'config.php';

$error = '';
$success = '';


if (isLoggedIn()) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (!isset($_POST['csrf_token']) || !validateCSRFToken($_POST['csrf_token'])) {
        $error = 'Invalid security token. Please refresh and try again.';
    } else {
        
        $username = isset($_POST['username']) ? sanitizeInput($_POST['username']) : '';
        $email = isset($_POST['email']) ? sanitizeInput($_POST['email']) : '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
            $error = 'All fields are required.';
        }
        elseif (!validateUsername($username)) {
            $error = 'Username must be 3-20 characters and contain only letters, numbers, and underscores.';
        }
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) { // Built-in PHP filter
            $error = 'Invalid email address format.';
        }
        elseif (!validatePassword($password)) {
            $error = 'Password must be at least 8 characters and include an uppercase letter, lowercase letter, number, and special character.';
        }
        elseif ($password !== $confirmPassword) {
            $error = 'Passwords do not match.';
        }
        else {
            
            try {
                $pdo = getSecureDBConnection();
                
                $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
                $stmt->execute([$username]);
                
                if ($stmt->fetch()) {
                    $error = 'Username already taken. Please choose another.';
                } else {
                    
                    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
                    $stmt->execute([$email]);
                    
                    if ($stmt->fetch()) {
                        $error = 'Email already registered. Please use another or log in.';
                    } else {
                        
                        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                        
                        $stmt = $pdo->prepare("
                            INSERT INTO users (username, email, password, email_verified, account_status) 
                            VALUES (?, ?, ?, 1, 'active')
                        ");
                        
                        if ($stmt->execute([$username, $email, $hashedPassword])) {
                            
                            $userId = $pdo->lastInsertId();
                            
                            secureLog('info', 'New user registration', [
                                'user_id' => $userId,
                                'username' => $username
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
                
                error_log('Registration DB Error: ' . $e->getMessage());
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
        /* Visual Feedback for Valid/Invalid fields */
        input:not(:placeholder-shown):valid {
            border-color: #28a745;
        }
        input:not(:placeholder-shown):invalid {
            border-color: #dc3545;
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
            padding: 10px;
            background: #f0f0f0;
            border-radius: 4px;
        }
        .background
        {
            background: url("elegant-modern-vase-design.jpg") no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
        }
    </style>
</head>
<body class ="background">
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
                    autocomplete="username"
                    value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">

                <label for="email">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    required
                    placeholder="your@email.com"
                    autocomplete="email"
                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">

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
                    ✓ One uppercase & lowercase letter<br>
                    ✓ One number & one special character
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

        function validatePasswordMatch() {
            if (confirmPassword.value && password.value !== confirmPassword.value) {
                confirmPassword.setCustomValidity('Passwords do not match');
                confirmPassword.style.borderColor = '#dc3545'; // Red
            } else {
                confirmPassword.setCustomValidity('');
                confirmPassword.style.borderColor = '#ccc'; // Reset
                
                if (confirmPassword.value && password.value === confirmPassword.value) {
                    confirmPassword.style.borderColor = '#28a745'; // Green
                }
            }
        }

        password.addEventListener('change', validatePasswordMatch);
        confirmPassword.addEventListener('keyup', validatePasswordMatch);
    </script>
</body>
</html>