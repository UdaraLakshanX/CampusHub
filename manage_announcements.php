<?php
session_start();
require_once 'connection.php';

if (!isset($_SESSION['admin_email'])) {
    header("Location: admin_login.php");
    exit();
}

Database::setUpConnection();
$message = "";
$admin_email = $_SESSION['admin_email'];

$admin_query = "SELECT admin_id FROM admin WHERE email = '$admin_email'";
$admin_rs = Database::search($admin_query);
$admin_data = $admin_rs->fetch_assoc();
$admin_id = $admin_data['admin_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = Database::$connection->real_escape_string($_POST['title']);
    $content = Database::$connection->real_escape_string($_POST['content']);
    $post_date = date('Y-m-d'); 

    if (!empty($title) && !empty($content)) {
        $insert_query = "INSERT INTO announcements (title, content, post_date, admin_admin_id) 
                         VALUES ('$title', '$content', '$post_date', '$admin_id')";
        Database::iud($insert_query);
        
        $message = "<div style='background-color: #d1fae5; color: #065f46; padding: 10px; border-radius: 4px; margin-bottom: 15px;'>📢 Announcement Published Successfully!</div>";
    } else {
        $message = "<div style='background-color: #fee2e2; color: #991b1b; padding: 10px; border-radius: 4px; margin-bottom: 15px;'>Please fill all fields!</div>";
    }
}

$ann_query = "SELECT announcements.*, admin.fname, admin.lname 
              FROM announcements 
              INNER JOIN admin ON announcements.admin_admin_id = admin.admin_id 
              ORDER BY announcements.post_date DESC";
$ann_rs = Database::search($ann_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Announcements - Admin Panel</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>

    <div class="sidebar">
        <h2>⚙️ Admin Panel</h2>
        <a href="admin_dashboard.php">📊 Dashboard</a>
        <a href="manage_students.php">👥 Manage Students</a>
        <a href="manage_institutes.php">🏢 Manage Institutes</a>
        <a href="manage_events.php">📅 Manage Events</a>
        <a href="manage_announcements.php" class="active">📢 Manage Announcements</a>
        <a href="manage_media.php">📸 Manage Media</a>
    </div>

    <div class="content">
        <div class="header">
            <h2>Manage Campus Announcements</h2>
            <a href="admin_logout.php" class="logout-btn">Logout</a>
        </div>
        
        <?php echo $message; ?>

        <div class="event-form-card" style="border-top-color: #f59e0b;">
            <h3 style="margin-top: 0;">Publish New Announcement</h3>
            <form action="manage_announcements.php" method="POST">
                <div class="input-group">
                    <label>Title / Subject</label>
                    <input type="text" name="title" required placeholder="e.g. Exam Timetable Update">
                </div>

                <div class="input-group">
                    <label>Announcement Details</label>
                    <textarea name="content" rows="4" required placeholder="Type the announcement here..."></textarea>
                </div>

                <button type="submit" class="btn-success" style="background-color: #f59e0b;">Broadcast Announcement</button>
            </form>
        </div>

        <h3 style="margin-bottom: 10px;">Broadcasted Announcements</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 15%;">Date</th>
                    <th style="width: 25%;">Title</th>
                    <th style="width: 45%;">Content</th>
                    <th style="width: 15%;">Posted By</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($ann_rs->num_rows > 0) {
                    while ($ann_data = $ann_rs->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td><strong>" . date('M d, Y', strtotime($ann_data['post_date'])) . "</strong></td>";
                        echo "<td>" . $ann_data['title'] . "</td>";
                        echo "<td>" . $ann_data['content'] . "</td>";
                        echo "<td><span style='background: #e2e8f0; padding: 4px 8px; border-radius: 4px; font-size: 12px;'>" . $ann_data['fname'] . " " . $ann_data['lname'] . "</span></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' style='text-align: center; padding: 20px;'>No announcements published yet.</td></tr>";
                }
                ?>
            </tbody>
        </table>

    </div>
</body>
</html>