<?php
session_start();
require_once 'connection.php';

// Session Protection
if (!isset($_SESSION['student_email'])) {
    header("Location: login.php");
    exit(); 
}

Database::setUpConnection();

$query = "SELECT events.*, activity_categories.category_name 
          FROM events 
          INNER JOIN activity_categories ON events.activity_categories_category_id = activity_categories.category_id 
          ORDER BY events.event_date ASC";
$result_set = Database::search($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusHub - Upcoming Events</title>
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
            <h2>Upcoming Events & Activities</h2>
            <p>Discover and register for the latest happenings across all institutes.</p>
        </div>

        <div class="events-grid">
            
            <?php
            if ($result_set->num_rows > 0) {
                while ($event = $result_set->fetch_assoc()) {
                    $formatted_date = date('dth M Y', strtotime($event['event_date']));
                    $formatted_date = date('jS M Y', strtotime($event['event_date'])); 
                    
                    echo '<div class="event-card">';
                    echo '    <span class="category-badge">' . htmlspecialchars($event['category_name']) . '</span>';
                    echo '    <h3>' . htmlspecialchars($event['title']) . '</h3>';
                    echo '    <div class="event-meta">📅 ' . $formatted_date . '</div>';
                    echo '    <p class="event-desc">' . htmlspecialchars($event['description']) . '</p>';
                    if ($event['status'] == 'Upcoming') {
                        echo '    <div class="event-meta status-open">🟢 Registration Open</div>';
                        echo '    <a href="event_register.php?id=' . $event['event_id'] . '" class="btn btn-primary card-btn">Register Now</a>';
                    } else {
                        echo '    <div class="event-meta status-closed">🔴 Fully Booked</div>';
                        echo '    <button class="btn card-btn" disabled style="background-color: #d1d5db; color: #6b7280; cursor: not-allowed;">Closed</button>';
                    }
                    
                    echo '</div>';
                }
            } else {
                echo '<div style="grid-column: 1 / -1; text-align: center; padding: 20px; color: #6b7280;">No upcoming events found.</div>';
            }
            ?>

        </div>
    </main>

    <footer>
        <p>&copy; 2026 CampusHub Information System. All Rights Reserved.</p>
    </footer>

</body>
</html>