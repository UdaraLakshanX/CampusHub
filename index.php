<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusHub - Home</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <div class="logo">CampusHub</div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="events.php">Events</a></li>
                <li><a href="announcements.php">Announcements</a></li>
                <li><a href="media.php">Media</a></li>

                <?php if (isset($_SESSION['student_email'])): ?>
                    <li><a href="profile.php">My Profile</a></li>
                    <li><a href="logout.php" class="login-btn" style="background-color: #ef4444;">Logout</a></li>

                <?php else: ?>
                    <li><a href="register.php">Register</a></li>
                    <li><a href="login.php" class="login-btn">Login</a></li>

                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>
        <section class="hero">
            <h1>Welcome to CampusHub</h1>
            <p>Explore upcoming activities, manage your profile, and stay updated with campus news.</p>
            <div class="hero-actions">
                <a href="register.php" class="btn btn-primary">Register Now</a>
                <a href="events.php" class="btn btn-secondary">Explore Events</a>
            </div>
        </section>

        <!-- Highlights Section -->
        <section class="highlights">
            <div class="card">
                <h3>📅 Upcoming Events</h3>
                <ul>
                    <li>Tech Workshop 2026 - 15th Sept</li>
                    <li>Annual Sports Meet - 20th Oct</li>
                </ul>
                <a href="events.php" class="view-more">View All Events &rarr;</a>
            </div>

            <div class="card">
                <h3>📢 Latest Notice</h3>
                <p><strong>Registration Extended!</strong><br>Deadline for the sports meet is now 27th Aug.</p>
                <a href="announcements.php" class="view-more">View All Notices &rarr;</a>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer>
        <p>&copy; 2026 CampusHub Information System. All Rights Reserved.</p>
    </footer>

</body>

</html>