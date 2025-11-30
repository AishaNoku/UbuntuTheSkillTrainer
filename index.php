<?php


define('SECURE_ACCESS', true);
require_once 'config.php'; 

$isLoggedIn = isLoggedIn();
$username = getUsername();
$userId = getUserId();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubuntu Skills - Learn Any Skill</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <a href="index.php" class="logo">Ubuntu</a>

        <nav id="nav-menu">
            <a href="#skills">Skills</a>
            <a href="#how">Getting Started</a>
            <a href="#footer">Contact Us</a>
        </nav>
        
        <div class="auth-buttons" style="display: flex; align-items: center; gap: 1rem;">
            
            <?php if ($isLoggedIn): ?>
                <span style="font-weight: bold; color: var(--accent);">
                    Hi, <?php echo htmlspecialchars($username); ?>
                </span>
                <a href="logout.php" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
                    Log Out
                </a>
            <?php else: ?>
                <a href="login.html" class="btn btn-primary">Login</a>
            <?php endif; ?>

            <button class="menu-toggle" id="menu-toggle" style="margin-left: 10px;">&#9776;</button>
        </div>
    </header>

    <section class="hero">
        <div class="hero-content">
            <h1>Learn Any Skill</h1>
            <p>Learn from craftmasters and unlock your potential</p>
            <div class="hero-buttons">
                <?php if ($isLoggedIn): ?>
                    <a href="#skills" class="btn btn-primary">Browse Courses</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-primary">Start Learning</a>
                    <a href="#skills" class="btn btn-secondary">Explore Courses</a>
                <?php endif; ?>
            </div>
            <div class="hero-stats">
                <div class="stat">
                    <span class="stat-number">5K+</span>
                    <span class="stat-label">Learners</span>
                </div>
                <div class="stat">
                    <span class="stat-number">4</span>
                    <span class="stat-label">Skills</span>
                </div>
                <div class="stat">
                    <span class="stat-number">4</span>
                    <span class="stat-label">Expert Instructors</span>
                </div>
            </div>
        </div>
    </section>

    <section class="featured-skills" id="skills">
        <div class="container">
            <h2 class="section-title">Skills to Learn</h2>
            <p class="section-subtitle">Explore in-demand skills available on this platform</p>
            
            <div class="skills-grid">
                <?php
                try {
                    $pdo = getSecureDBConnection();
                    $stmt = $pdo->prepare("SELECT * FROM courses WHERE is_active = 1 ORDER BY id");
                    $stmt->execute();
                    $courses = $stmt->fetchAll();

                    if (count($courses) > 0) {
                        foreach ($courses as $course) {
                            $folder = htmlspecialchars($course['folder_name']);
                            $link = "#";

                            if ($isLoggedIn) {
                                // Map folders topages
                                $courseLinks = [
                                    'crochet' => 'crochet/crochetHome.html',
                                    'beads' => 'beads/index.php',
                                    'henna' => 'Henna/henna.html',
                                    'mbira' => 'mbira/index.html'
                                ];
                                
                                $link = isset($courseLinks[$folder]) ? $courseLinks[$folder] : '#';
                            } else {
                                // Redirect to login
                                $link = 'login.php';
                            }

                            echo '
                            <a href="' . htmlspecialchars($link) . '" style="text-decoration: none; color: inherit;">
                                <div class="skill-card">
                                    <div class="skill-icon">' . htmlspecialchars($course['icon']) . '</div>
                                    <h3>' . htmlspecialchars($course['title']) . '</h3>
                                    <p>' . htmlspecialchars($course['description']) . '</p>
                                </div>
                            </a>
                            ';
                        }
                    } else {
                        echo '<p>No courses available at the moment.</p>';
                    }
                } catch (PDOException $e) {
                    echo '<p>Unable to load courses. Please try again later.</p>';
                }
                ?>
            </div>
        </div>
    </section>

    <section class="how-it-works" id="how">
        <div class="container">
            <h2 class="section-title">Getting Started with Ubuntu Skills</h2>
            <p class="section-subtitle">Your journey from beginner to expert in 4 simple steps</p>
            <div class="steps">
                <div class="step">
                    <div class="step-number">1</div>
                    <h3>Choose Your Skill</h3>
                    <p>Browse our extensive catalog and pick any skill you would like to master</p>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <h3>Learn at Your Pace</h3>
                    <p>Follow structured courses with video lessons and hands on projects</p>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <h3>Build Real Projects</h3>
                    <p>Create real-world projects and portfolios to showcase your skills</p>
                </div>
                <div class="step">
                    <div class="step-number">4</div>
                    <h3>Get Certified</h3>
                    <p>Earn certificates and boost your professional profile</p>
                </div>
            </div>
        </div>
    </section>

    <section class="testimonials">
        <div class="container">
            <h2 class="section-title">Success Stories</h2>
            <p class="section-subtitle">See how Ubuntu Skills changed their careers and lives</p>
            
            <div class="testimonials-grid">
                <?php
                try {
                    // We reuse the $pdo connection from above
                    $stmt_testi = $pdo->prepare("SELECT * FROM testimonials ORDER BY created_at DESC LIMIT 3");
                    $stmt_testi->execute();
                    $testimonials = $stmt_testi->fetchAll();

                    if (count($testimonials) > 0) {
                        foreach ($testimonials as $row) {
                            
                            // Generate Stars (e.g. 5 -> ⭐⭐⭐⭐⭐)
                            $stars = str_repeat("⭐", (int)$row['rating']);

                            echo '
                            <div class="testimonial-card">
                                <div class="stars">' . $stars . '</div>
                                <p class="testimonial-text">"' . htmlspecialchars($row['content']) . '"</p>
                                <div class="testimonial-author">
                                    <div class="author-avatar">' . htmlspecialchars($row['initials']) . '</div>
                                    <div>
                                        <div class="author-name">' . htmlspecialchars($row['name']) . '</div>
                                        <div class="author-role">' . htmlspecialchars($row['role']) . '</div>
                                    </div>
                                </div>
                            </div>
                            ';
                        }
                    } else {
                        echo '<p style="text-align:center; width:100%;">No testimonials yet. Be the first to share your story!</p>';
                    }
                } catch (PDOException $e) {
                    echo '<p>Unable to load success stories.</p>';
                }
                ?>
            </div>
        </div>
    </section>

    <section class="cta">
        <div class="container">
            <h2>Ready to Learn a New Skill?</h2>
            <p>Start your upskilling today for free!</p>
            <?php if ($isLoggedIn): ?>
                <a href="#skills" class="btn btn-primary">Browse Courses</a>
            <?php else: ?>
                <a href="signup.php" class="btn btn-primary">Get Started</a>
            <?php endif; ?>
        </div>
    </section>

    <footer>
        <div class="footer-content" id="footer">
            <div class="footer-section">
                <h4>Ubuntu Skills</h4>
                <a href="#">About us</a>
                <a href="#">Blog</a>
                <a href="#">Press</a>
            </div>
            <div class="footer-section">
                <h4>Learning</h4>
                <a href="#">Browse Skills</a>
                <a href="#">Paths</a>
                <a href="#">Certificates</a>
            </div>
            <div class="footer-section">
                <h4>Community</h4>
                <a href="#">Events</a>
                <a href="#">Forums</a>
                <a href="#">Opportunities</a>
            </div>
            <div class="footer-section">
                <h4>Support</h4>
                <a href="#">Contact</a>
                <a href="#">Help Center</a>
                <a href="#">Privacy</a>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Ubuntu Skills. All rights reserved. Empowering learners worldwide</p>
            </div>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>