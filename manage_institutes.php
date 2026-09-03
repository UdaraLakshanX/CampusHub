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
    $insti_name = Database::$connection->real_escape_string($_POST['insti_name']);
    $location = Database::$connection->real_escape_string($_POST['location']);
    
    if (!empty($insti_name) && !empty($location)) {

        $insert_query = "INSERT INTO institutes (insti_name, location) 
                         VALUES ('$insti_name', '$location')";
        Database::iud($insert_query);
        
        $message = "<div style='background-color: #d1fae5; color: #065f46; padding: 10px; border-radius: 4px; margin-bottom: 15px;'>🏢 Institute Added Successfully!</div>";
    } else {
        $message = "<div style='background-color: #fee2e2; color: #991b1b; padding: 10px; border-radius: 4px; margin-bottom: 15px;'>Please fill all fields!</div>";
    }
}

$insti_query = "SELECT * FROM institutes ORDER BY insti_id ASC";
$insti_rs = Database::search($insti_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Institutes - Admin Panel</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>

    <div class="sidebar">
        <h2>⚙️ Admin Panel</h2>
        <a href="admin_dashboard.php">📊 Dashboard</a>
        <a href="manage_students.php">👥 Manage Students</a>
        <a href="manage_institutes.php" class="active">🏢 Manage Institutes</a>
        <a href="manage_events.php">📅 Manage Events</a>
        <a href="manage_announcements.php">📢 Manage Announcements</a>
        <a href="manage_media.php">📸 Manage Media</a>
    </div>

    <div class="content">
        <div class="header">
            <h2>Manage Campus Institutes</h2>
            <a href="admin_logout.php" class="logout-btn">Logout</a>
        </div>
        
        <?php echo $message; ?>

        <div class="event-form-card" style="border-top-color: #0ea5e9;">
            <h3 style="margin-top: 0;">Add New Institute</h3>
            <form action="manage_institutes.php" method="POST">
                
                <div style="display: flex; gap: 15px; margin-bottom: 15px;">
                    <div style="flex: 1;">
                        <label style="display: block; font-weight: bold; margin-bottom: 5px; color: #334155;">Institute Name</label>
                        <input type="text" name="insti_name" required placeholder="e.g. Faculty of Computing" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 4px;">
                    </div>
                    
                    <div style="flex: 1;">
                        <label style="display: block; font-weight: bold; margin-bottom: 5px; color: #334155;">Location / Branch</label>
                        <input type="text" name="location" required placeholder="e.g. Colombo 03" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 4px;">
                    </div>
                </div>

                <button type="submit" class="btn-success" style="background-color: #0ea5e9;">Add Institute</button>
            </form>
        </div>

        <h3 style="margin-bottom: 10px;">Registered Institutes</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 15%;">Institute ID</th>
                    <th style="width: 45%;">Institute Name</th>
                    <th style="width: 40%;">Location</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($insti_rs->num_rows > 0) {
                    while ($insti_data = $insti_rs->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td><strong>#" . $insti_data['insti_id'] . "</strong></td>";
                        echo "<td>" . $insti_data['insti_name'] . "</td>";
                        echo "<td>📍 " . $insti_data['location'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3' style='text-align: center; padding: 20px;'>No institutes added yet.</td></tr>";
                }
                ?>
            </tbody>
        </table>

    </div>
</body>
</html>