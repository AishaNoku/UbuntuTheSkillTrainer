<?php session_start();

$isLoggedIn = isset($_SESSION['username']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubuntu Skills</title>
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
            
            <?php if($isLoggedIn): ?>
                <span style="font-weight: bold; color: var(--accent);">
                    Hi, <?php echo htmlspecialchars($_SESSION['username']); ?>
                </span>
                <a href="login.html" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
                    Log Out
                </a>
            <?php else: ?>
                <a href="login.html" class="btn btn-primary">Login</a>
            <?php endif; ?>

            <button class="menu-toggle" id="menu-toggle" style="margin-left: 10px;">&#9776;</button>
        </div>
    </header>
    <!--Hero Section-->
    <section class="hero">
    <div class="hero-content">
        <h1>Learn Any Skill</h1>
        <p>Learn from craftmasters and unlock your potential</p>
        <div class="hero-buttons">
            <button class = "btn btn-primary">Start Learning</button>
            <button class ="btn btn-primary">Explore Courses</button>
        </div>
        <div class=" hero-stats">
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
    <!--Featured Skills-->
    <section class="featured skills" id="skills">
        <div class="container">
            <h2 class="section-title">Skills to Learn</h2>
            <p class="section-subtitle">Explore in-demand skills available on this platform</p>
            
            <div class="skills-grid">
                <?php
                include 'db_connect.php';
                $sql = "SELECT * FROM courses";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $folder = $row['folder_name'];
                        $link = "#";

                        if ($isLoggedIn) {
                            if ($folder == 'crochet') {
                                $link = "crochet/crochetHome.html";
                            } elseif ($folder == 'beads') {
                                $link = "beads/index.php";
                            } elseif ($folder == 'henna') {
                                $link = "Henna/henna.html";
                            } elseif ($folder == 'mbira') {
                                $link = "#"; 
                            }
                        } else {
                            $link = "login.html"; 
                        }

                        echo '
                        <a href="' . $link . '" style="text-decoration: none; color: inherit;">
                            <div class="skill-card">
                                <div class="skill-icon">' . $row['icon'] . '</div>
                                <h3>' . $row['title'] . '</h3>
                                <p>' . $row['description'] . '</p>
                            </div>
                        </a>
                        ';
                    }
                } else {
                    echo "<p>No courses found in database.</p>";
                }
                ?>
            </div>
        </div>
    </section>
    <!--Getting Started-->
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
    <!--Testimonials-->
    <section class="testimonials">
        <div class="container">
            <h2 class="section-title">Success Stories</h2>
            <p class="section-subtitle">See how Ubuntu Skills changed their careers and lives</p>
            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <div class="stars">⭐⭐⭐⭐⭐</div>
                    <p class="testimonial-text">I really learnt a lot from Ubuntu skills and I keep learning regularly!</p>
                    <div class="testimonial-author">
                        <div class="author-avatar">TM</div>
                        <div>
                            <div class="author-name">Tanya M.</div>
                            <div class="author-role">UI/UX Specialist</div>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card">
                    <div class="stars">⭐⭐⭐⭐⭐</div>
                    <p class="testimonial-text">The bead making course helped me start my own business in Harare!</p>
                    <div class="testimonial-author">
                        <div class="author-avatar">SJ</div>
                        <div>
                            <div class="author-name">Sarah J.</div>
                            <div class="author-role">Entrepreneur</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--CTA-->
    <section class="cta">
        <div class="container">
            <h2>Ready to Learn a New Skill?</h2>
            <p>Start your upskilling today for free!</p>
            <button class="btn btn-primary">Get Started</button>
        </div>
    </section>
    <!--Footer-->
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
                <a href="#">browse Skills</a>
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
                <a href="#">About us</a>
                <a href="#">Blog</a>
                <a href="#">Press</a>
            </div>
        </div>
    </footer>
    <script src="script.js"></script>

</body>
</html>