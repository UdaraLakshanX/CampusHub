<?php
session_start();
require_once 'connection.php';

if (!isset($_SESSION['admin_email'])) {
    header("Location: admin_login.php");
    exit();
}

Database::setUpConnection();

$query = "SELECT students.*, gender.gender_name, institutes.insti_name 
          FROM students 
          INNER JOIN gender ON students.gender_gender_id = gender.gender_id 
          INNER JOIN institutes ON students.institutes_insti_id = institutes.insti_id 
          ORDER BY student_id ASC"; 

$result_set = Database::search($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students - Admin Panel</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>\

    <div class="sidebar">
        <h2>⚙️ Admin Panel</h2>
        <a href="admin_dashboard.php">📊 Dashboard</a>
        <a href="manage_students.php" class="active">👥 Manage Students</a>
        <a href="manage_institutes.php">🏢 Manage Institutes</a>
        <a href="manage_events.php">📅 Manage Events</a>
        <a href="manage_announcements.php">📢 Manage Announcements</a>
        <a href="manage_media.php">📸 Manage Media</a>
    </div>

    <div class="content">
        <div class="header">
            <h2>Manage Registered Students</h2>
            <a href="admin_logout.php" class="logout-btn">Logout</a>
        </div>
        
        <p>Here you can view and manage the access of all registered students.</p>
        
        <table class="data-table">
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Institute</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                
                <?php
                if ($result_set->num_rows > 0) {
                    while ($student_data = $result_set->fetch_assoc()) {
                        
                        if ($student_data['status_status_id'] == '1') {
                            $status_text = "Active";
                            $badge_class = "status-active"; 
                        } else {
                            $status_text = "Inactive";
                            $badge_class = "status-inactive"; 
                        }

                        echo "<tr>";
                        echo "<td>" . $student_data['fname'] . "</td>";
                        echo "<td>" . $student_data['lname'] . "</td>";
                        echo "<td>" . $student_data['email'] . "</td>";
                        echo "<td>" . $student_data['insti_name'] . "</td>";
                        
                        echo "<td><span class='status-badge {$badge_class}'>" . $status_text . "</span></td>";
                        
                        echo "<td>";
                        if ($student_data['status_status_id'] == '1') {
                            echo "<a href='toggle_status.php?email=" . $student_data['email'] . "' class='action-btn btn-deactivate'>Deactivate</a>";
                        } else {
                            echo "<a href='toggle_status.php?email=" . $student_data['email'] . "' class='action-btn btn-activate'>Activate</a>";
                        }
                        echo "</td>";
                        
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' style='text-align: center; padding: 20px;'>No students registered yet.</td></tr>";
                }
                ?>
                
            </tbody>
        </table>

    </div>

</body>
</html>