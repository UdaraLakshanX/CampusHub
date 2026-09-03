<?php
session_start();
require_once 'connection.php';

if (!isset($_SESSION['admin_email'])) {
    header("Location: admin_login.php");
    exit();
}

Database::setUpConnection();

$student_rs = Database::search("SELECT COUNT(*) AS total FROM students");
$total_students = $student_rs->fetch_assoc()['total'];

$events_rs = Database::search("SELECT COUNT(*) AS total FROM events WHERE status = 'Upcoming'");
$total_events = $events_rs->fetch_assoc()['total'];

$insti_rs = Database::search("SELECT COUNT(*) AS total FROM institutes");
$total_insti = $insti_rs->fetch_assoc()['total'];

$ann_rs = Database::search("SELECT COUNT(*) AS total FROM announcements");
$total_ann = $ann_rs->fetch_assoc()['total'];

$media_rs = Database::search("SELECT COUNT(*) AS total FROM media");
$total_media = $media_rs->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusHub - Admin Dashboard</title>
    <link rel="stylesheet" href="admin_style.css">
    <style>
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat-card h3 { margin: 0 0 10px 0; color: #475569; font-size: 16px; }
        .stat-card .number { margin: 0; font-size: 32px; font-weight: bold; color: #1e293b; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>⚙️ Admin Panel</h2>
        <a href="admin_dashboard.php" class="active">📊 Dashboard</a>
        <a href="manage_students.php">👥 Manage Students</a>
        <a href="manage_institutes.php">🏢 Manage Institutes</a>
        <a href="manage_events.php">📅 Manage Events</a>
        <a href="manage_announcements.php">📢 Manage Announcements</a>
        <a href="manage_media.php">📸 Manage Media</a>
    </div>

    <div class="content">
        <div class="header">
            <h2>Dashboard Overview</h2>
            <a href="admin_logout.php" class="logout-btn">Logout</a>
        </div>
        
        <p>Welcome back, Administrator! Here is the current status of the CampusHub system.</p>
        
        <div class="dashboard-grid">
            
            <div class="stat-card" style="border-top: 4px solid #3b82f6;">
                <h3>Total Students</h3>
                <p class="number"><?php echo $total_students; ?></p>
            </div>
            
            <div class="stat-card" style="border-top: 4px solid #10b981;">
                <h3>Upcoming Events</h3>
                <p class="number"><?php echo $total_events; ?></p>
            </div>

            <div class="stat-card" style="border-top: 4px solid #0ea5e9;">
                <h3>Institutes</h3>
                <p class="number"><?php echo $total_insti; ?></p>
            </div>

            <div class="stat-card" style="border-top: 4px solid #f59e0b;">
                <h3>Announcements</h3>
                <p class="number"><?php echo $total_ann; ?></p>
            </div>

            <div class="stat-card" style="border-top: 4px solid #8b5cf6;">
                <h3>Media Uploads</h3>
                <p class="number"><?php echo $total_media; ?></p>
            </div>

        </div>
    </div>

</body>
</html>