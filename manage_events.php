<?php
session_start();
require_once 'connection.php';

if (!isset($_SESSION['admin_email'])) {
    header("Location: admin_login.php");
    exit();
}

Database::setUpConnection();
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = Database::$connection->real_escape_string($_POST['title']);
    $date = Database::$connection->real_escape_string($_POST['event_date']);
    $category_id = Database::$connection->real_escape_string($_POST['category_id']);
    $status = Database::$connection->real_escape_string($_POST['status']);
    $desc = Database::$connection->real_escape_string($_POST['description']);
    
    if (!empty($title) && !empty($date) && !empty($category_id) && !empty($status) && !empty($desc)) {

        $insert_query = "INSERT INTO events (title, description, event_date, status, activity_categories_category_id) 
                         VALUES ('$title', '$desc', '$date', '$status', '$category_id')";
        Database::iud($insert_query);
        
        $message = "<div style='background-color: #d1fae5; color: #065f46; padding: 10px; border-radius: 4px; margin-bottom: 15px;'>🎉 Event Added Successfully!</div>";
    } else {
        $message = "<div style='background-color: #fee2e2; color: #991b1b; padding: 10px; border-radius: 4px; margin-bottom: 15px;'>Please fill all fields!</div>";
    }
}

$cat_query = "SELECT * FROM activity_categories";
$cat_rs = Database::search($cat_query);

$event_query = "SELECT events.*, activity_categories.category_name 
                FROM events 
                INNER JOIN activity_categories ON events.activity_categories_category_id = activity_categories.category_id 
                ORDER BY events.event_date DESC";
$event_rs = Database::search($event_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events - Admin Panel</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>

    <div class="sidebar">
        <h2>⚙️ Admin Panel</h2>
        <a href="admin_dashboard.php">📊 Dashboard</a>
        <a href="manage_students.php">👥 Manage Students</a>
        <a href="manage_institutes.php">🏢 Manage Institutes</a>
        <a href="manage_events.php" class="active">📅 Manage Events</a>
        <a href="manage_announcements.php">📢 Manage Announcements</a>
        <a href="manage_media.php">📸 Manage Media</a>
    </div>

    <div class="content">
        <div class="header">
            <h2>Manage Campus Events</h2>
            <a href="admin_logout.php" class="logout-btn">Logout</a>
        </div>
        
        <?php echo $message; ?>


        <div class="event-form-card">
            <h3 style="margin-top: 0;">Add New Event</h3>
            <form action="manage_events.php" method="POST">
                <div class="input-group">
                    <label>Event Title</label>
                    <input type="text" name="title" required placeholder="e.g. Annual IT Conference">
                </div>
   
                <div style="display: flex; gap: 15px; margin-bottom: 15px;">
                    <div style="flex: 1;">
                        <label style="display: block; font-weight: bold; margin-bottom: 5px; color: #334155;">Event Date</label>
                        <input type="date" name="event_date" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 4px;">
                    </div>
                    
                    <div style="flex: 1;">
                        <label style="display: block; font-weight: bold; margin-bottom: 5px; color: #334155;">Category</label>
                        <select name="category_id" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 4px;">
                            <option value="">Select Category</option>
                            <?php
                            if ($cat_rs->num_rows > 0) {
                                while ($cat = $cat_rs->fetch_assoc()) {
                                    echo "<option value='".$cat['category_id']."'>".$cat['category_name']."</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div style="flex: 1;">
                        <label style="display: block; font-weight: bold; margin-bottom: 5px; color: #334155;">Status</label>
                        <select name="status" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 4px;">
                            <option value="Upcoming">Upcoming</option>
                            <option value="Completed">Completed</option>
                        </select>
                    </div>
                </div>

                <div class="input-group">
                    <label>Event Description</label>
                    <textarea name="description" rows="3" required placeholder="Enter event details here..."></textarea>
                </div>

                <button type="submit" class="btn-success">Publish Event</button>
            </form>
        </div>

        <h3 style="margin-bottom: 10px;">Published Events</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 15%;">Date</th>
                    <th style="width: 25%;">Title</th>
                    <th style="width: 20%;">Category</th>
                    <th style="width: 15%;">Status</th>
                    <th style="width: 25%;">Description</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($event_rs->num_rows > 0) {
                    while ($event_data = $event_rs->fetch_assoc()) {
                        $status_color = ($event_data['status'] == 'Upcoming') ? '#2563eb' : '#10b981';
                        
                        echo "<tr>";
                        echo "<td><strong>" . date('M d, Y', strtotime($event_data['event_date'])) . "</strong></td>";
                        echo "<td>" . $event_data['title'] . "</td>";
                        echo "<td>" . $event_data['category_name'] . "</td>";
                        echo "<td><span style='background-color: {$status_color}; color: white; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: bold;'>" . $event_data['status'] . "</span></td>";
                        echo "<td>" . substr($event_data['description'], 0, 50) . "...</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' style='text-align: center; padding: 20px;'>No events found.</td></tr>";
                }
                ?>
            </tbody>
        </table>

    </div>
</body>
</html>