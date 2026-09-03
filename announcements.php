<?php
session_start();
require_once 'connection.php';

if (!isset($_SESSION['student_email'])) {
    header("Location: login.php");
    exit(); 
}

Database::setUpConnection();

$query = "SELECT announcements.*, admin.fname, admin.lname 
          FROM announcements 
          INNER JOIN admin ON announcements.admin_admin_id = admin.admin_id 
          ORDER BY announcements.post_date DESC";
$result_set = Database::search($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusHub - Announcements</title>
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

    <main class="page-container">
        <div class="page-header">
            <h2>Campus Announcements</h2>
            <p>Stay updated with the latest official notices and updates from the administration.</p>
        </div>

        <div class="events-grid">
            
            <?php
            if ($result_set->num_rows > 0) {
                while ($ann = $result_set->fetch_assoc()) {
                    
                    $formatted_date = date('jS M Y', strtotime($ann['post_date']));
                    
                    echo '<div class="event-card">';
                    
                    echo '    <span class="category-badge" style="background-color: #fef3c7; color: #d97706;">📢 Official Notice</span>';
                    
                    echo '    <h3>' . htmlspecialchars($ann['title']) . '</h3>';
                    
                    echo '    <div class="event-meta">📅 ' . $formatted_date . '</div>';
                    
                    echo '    <p class="event-desc">' . htmlspecialchars($ann['content']) . '</p>';
                    
                    echo '    <div class="event-meta status-open" style="background-color: #f1f5f9; color: #475569; border-left: 3px solid #cbd5e1; padding-left: 8px;">';
                    echo '        ✍️ Posted by: ' . htmlspecialchars($ann['fname'] . ' ' . $ann['lname']);
                    echo '    </div>';
                    
                    echo '</div>';
                }
            } else {
                echo '<div style="grid-column: 1 / -1; text-align: center; padding: 20px; color: #6b7280;">No announcements found.</div>';
            }
            ?>

        </div>
    </main>

    <footer>
        <p>&copy; 2026 CampusHub Information System. All Rights Reserved.</p>
    </footer>

</body>
</html>