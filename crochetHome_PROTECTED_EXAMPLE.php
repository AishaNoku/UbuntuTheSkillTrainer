<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Crocheting Skill – Home</title>
    <link rel="stylesheet" href="../crochet/assets/css/crochet.css" />
</head>
<body>

<?php

define('SECURE_ACCESS', true);
require_once '../config.php';

// Require user to be logged in to access this page
requireLogin();

// Get current user info
$userId = getUserId();
$username = getUsername();


secureLog('info', 'User accessed Crochet course', [
    'user_id' => $userId,
    'username' => $username,
    'course' => 'crochet'
]);


try {
    $pdo = getSecureDBConnection();
    

    $stmt = $pdo->prepare("SELECT id FROM courses WHERE folder_name = 'crochet' LIMIT 1");
    $stmt->execute();
    $course = $stmt->fetch();
    $courseId = $course['id'] ?? null;
    
    if ($courseId) {
        $stmt = $pdo->prepare("
            SELECT module_id, completed 
            FROM user_progress 
            WHERE user_id = ? AND course_id = ?
        ");
        $stmt->execute([$userId, $courseId]);
        $progress = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    }
} catch (PDOException $e) {
    secureLog('error', 'Error loading progress', ['error' => $e->getMessage()]);
    $progress = [];
}

?>

    <header>
        <h1>Crocheting Skill</h1>
        <p class="subtitle">Learn the art of crocheting from beginner to pro.</p>
        <div style="text-align: right; margin: 10px 20px;">
            <span style="color: #6B2F14; font-weight: bold;">
                Welcome, <?php echo htmlspecialchars($username); ?>!
            </span>
            <a href="../logout.php" style="margin-left: 15px; color: #A8603A;">Logout</a>
        </div>
    </header>

    <section class="progress-section">
        <label>Overall Progress</label>
        <div class="progress-bar">
            <div id="progress-fill" style="width: <?php 
                $totalModules = 6;
                $completedModules = count($progress);
                $percentage = ($completedModules / $totalModules) * 100;
                echo round($percentage);
            ?>%;"></div>
        </div>
        <p id="progress-text"><?php echo round($percentage); ?>% Completed</p>
    </section>

    <section class="modules">
        <h2>Modules</h2>

        <a href="../crochet/view/module1.php" class="module-card">
            <h3>Module 1: Introduction to Crocheting</h3>
            <p>Learn basics, tools & the slipknot.</p>
            <?php if (isset($progress['module1']) && $progress['module1']): ?>
                <span style="color: #22c55e; font-weight: bold;">✓ Completed</span>
            <?php endif; ?>
        </a>

        <a href="../crochet/view/module2.php" class="module-card">
            <h3>Module 2: Basic Stitches</h3>
            <p>Master chain, single crochet, double crochet, and more.</p>
            <?php if (isset($progress['module2']) && $progress['module2']): ?>
                <span style="color: #22c55e; font-weight: bold;">✓ Completed</span>
            <?php endif; ?>
        </a>

        <a href="../crochet/view/module3.php" class="module-card">
            <h3>Module 3: Essential Techniques</h3>
            <p>Learn tension, increases, decreases & reading patterns.</p>
            <?php if (isset($progress['module3']) && $progress['module3']): ?>
                <span style="color: #22c55e; font-weight: bold;">✓ Completed</span>
            <?php endif; ?>
        </a>

        <a href="../crochet/view/module4.php" class="module-card">
            <h3>Module 4: Beginner Projects</h3>
            <p>Create bracelets, coasters, headbands & small scarves.</p>
            <?php if (isset($progress['module4']) && $progress['module4']): ?>
                <span style="color: #22c55e; font-weight: bold;">✓ Completed</span>
            <?php endif; ?>
        </a>

        <a href="../crochet/view/module5.php" class="module-card">
            <h3>Module 5: Intermediate Patterns</h3>
            <p>Magic ring, stitching in the round, color changes.</p>
            <?php if (isset($progress['module5']) && $progress['module5']): ?>
                <span style="color: #22c55e; font-weight: bold;">✓ Completed</span>
            <?php endif; ?>
        </a>

        <a href="../crochet/view/module6.php" class="module-card">
            <h3>Module 6: Final Project</h3>
            <p>Complete a full piece & earn your certificate.</p>
            <?php if (isset($progress['module6']) && $progress['module6']): ?>
                <span style="color: #22c55e; font-weight: bold;">✓ Completed</span>
            <?php endif; ?>
        </a>

    </section>

    <script src="../crochet/assets/js/crochet.js"></script>
    
    <div class="next-btn-container">
        <button onclick="window.location.href='../index.php'">⬅ Back to Home Page</button>
    </div>

</body>
</html>

<?php
?>
