<?php
// 1. START SESSION to check for user login
session_start();
$isLoggedIn = isset($_SESSION['username']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bead Making Course</title>
    <link rel="stylesheet" href="../styles.css">
    <style>
        /* --- PROGRESS BAR STYLES --- */
        .progress-section {
            background: #fff;
            padding: 2rem;
            margin: 0 auto 2rem auto;
            max-width: 1200px; /* Matches container */
            border-bottom: 1px solid #eee;
            text-align: center;
        }
        
        .progress-track {
            background-color: #e2e8f0;
            height: 25px;
            border-radius: 15px;
            width: 100%;
            max-width: 600px; /* Keep bar from getting too wide */
            margin: 1rem auto;
            overflow: hidden;
        }
        
        .progress-fill {
            background-color: var(--accent); /* Rust color */
            height: 100%;
            width: 0%; /* JS will change this */
            transition: width 0.5s ease-in-out;
            border-radius: 15px;
        }

        /* --- CHECK BUTTON STYLES --- */
        .card-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
            margin-top: 1rem;
        }

        .check-btn {
            background: none;
            border: 2px solid var(--accent);
            color: var(--accent);
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s;
        }

        .check-btn:hover {
            background: var(--light);
        }

        .check-btn.completed {
            background-color: green;
            border-color: green;
            color: white;
        }
        
        /* Certificate Button (Hidden by default) */
        #cert-btn {
            display: none;
            margin-top: 1rem;
            background-color: green;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <header>
        <a href="../index.php" class="logo">Ubuntu</a>
        <nav id="nav-menu">
            <a href="../index.php#skills">Back to Skills</a>
            <a href="#syllabus">Syllabus</a>
            <a href="#footer">Contact Us</a>
            
            <?php if($isLoggedIn): ?>
                <span style="font-weight: bold; color: var(--accent); margin-left: 15px;">
                    Hi, <?php echo htmlspecialchars($_SESSION['username']); ?>
                </span>
                <a href="../logout.php" class="btn btn-secondary" style="margin-left: 10px;">Log Out</a>
            <?php else: ?>
                <a href="../login.html" class="btn btn-primary" style="margin-left: 10px;">Sign In</a>
            <?php endif; ?>
        </nav>
        <button class="menu-toggle" id="menu-toggle">&#9776;</button>
    </header>

    <div class="progress-section">
        <h2>Your Course Progress</h2>
        
        <div class="progress-track">
            <div class="progress-fill" id="progress-bar"></div>
        </div>
        <p id="progress-text">0% Complete</p>
        
        <a href="certificate.html" id="cert-btn">🏆 Get Your Certificate</a>
    </div>

    <section id="syllabus" class="course-content">
        <h1>Bead-making Course</h1>
        <p>Welcome to the Ubuntu Beadmaking Course! Select a module below to begin learning.</p>
        
        <div class="modules">
            
            <div class="module-card">
                <h2>Module 1: Introduction to Beadmaking & Materials</h2>
                <p>Learn the foundations of beadmaking, including materials, tools, safety, and the cultural history of beads.</p>
                <div class="card-actions">
                    <a href="module1.html" class="btn btn-primary">Start Module</a>
                    <button class="check-btn" id="btn-mod1" onclick="toggleModule('mod1')">
                        ⭕ Mark Done
                    </button>
                </div>
            </div>

            <div class="module-card">
                <h2>Module 2: Designing & Creating Beaded Accessories</h2>
                <p>Practice making earrings, bracelets, necklaces, and waist beads while learning design principles.</p>
                <div class="card-actions">
                    <a href="module2.html" class="btn btn-primary">Start Module</a>
                    <button class="check-btn" id="btn-mod2" onclick="toggleModule('mod2')">
                        ⭕ Mark Done
                    </button>
                </div>
            </div>

            <div class="module-card">
                <h2>Module 3: Finishing, Pricing & Selling</h2>
                <p>Learn how to finish your products professionally and sell them online or locally with pricing skills.</p>
                <div class="card-actions">
                    <a href="module3.html" class="btn btn-primary">Start Module</a>
                    <button class="check-btn" id="btn-mod3" onclick="toggleModule('mod3')">
                        ⭕ Mark Done
                    </button>
                </div>
            </div>
            
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
                <p>&copy; 2025 Ubuntu Skills. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        const totalModules = 3; // You have 3 cards

        // Run this when page loads
        document.addEventListener("DOMContentLoaded", () => {
            loadProgress();
        });

        function toggleModule(moduleId) {
            // Check if it's currently marked as done
            let currentStatus = localStorage.getItem(moduleId);

            if (currentStatus === "done") {
                localStorage.removeItem(moduleId); // Uncheck it
            } else {
                localStorage.setItem(moduleId, "done"); // Check it
            }
            loadProgress(); // Refresh UI
        }

        function loadProgress() {
            let completedCount = 0;
            const modules = ['mod1', 'mod2', 'mod3'];

            modules.forEach(id => {
                const btn = document.getElementById('btn-' + id);
                
                if (localStorage.getItem(id) === "done") {
                    completedCount++;
                    btn.innerHTML = "✅ Completed";
                    btn.classList.add("completed");
                } else {
                    btn.innerHTML = "⭕ Mark Done";
                    btn.classList.remove("completed");
                }
            });

            // Calculate Percentage
            const percentage = Math.round((completedCount / totalModules) * 100);

            // Update Bar
            document.getElementById('progress-bar').style.width = percentage + "%";
            document.getElementById('progress-text').innerText = percentage + "% Complete";

            // Show Certificate if 100%
            if (percentage === 100) {
                document.getElementById('cert-btn').style.display = "inline-block";
            } else {
                document.getElementById('cert-btn').style.display = "none";
            }
        }
    </script>
</body>
</html>