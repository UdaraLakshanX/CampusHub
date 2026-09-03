<?php
session_start();
require_once 'connection.php';

if (!isset($_SESSION['admin_email'])) {
    header("Location: admin_login.php");
    exit();
}

Database::setUpConnection();
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["media_file"])) {
    $event_id = Database::$connection->real_escape_string($_POST['event_id']);
    $media_type = Database::$connection->real_escape_string($_POST['media_type']); // ENUM ('Image', 'Video')

    $file_name = $_FILES["media_file"]["name"];
    $file_tmp = $_FILES["media_file"]["tmp_name"];

    $unique_file_name = uniqid() . "_" . basename($file_name);
    $upload_path = "uploads/" . $unique_file_name;

    if (move_uploaded_file($file_tmp, $upload_path)) {

        $insert_query = "INSERT INTO media (file_path, media_type, events_event_id) 
                         VALUES ('$upload_path', '$media_type', '$event_id')";
        Database::iud($insert_query);
        $message = "<div style='background-color: #d1fae5; color: #065f46; padding: 10px; border-radius: 4px; margin-bottom: 15px;'>📸 Media Uploaded Successfully!</div>";
    } else {
        $message = "<div style='background-color: #fee2e2; color: #991b1b; padding: 10px; border-radius: 4px; margin-bottom: 15px;'>Error uploading file.</div>";
    }
}

$events_query = "SELECT event_id, title FROM events ORDER BY event_date ASC";
$events_rs = Database::search($events_query);

$media_query = "SELECT media.*, events.title 
                FROM media 
                INNER JOIN events ON media.events_event_id = events.event_id 
                ORDER BY media.media_id DESC";
$media_rs = Database::search($media_query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Media - Admin Panel</title>
    <link rel="stylesheet" href="admin_style.css">
</head>

<body>

    <div class="sidebar">
        <h2>⚙️ Admin Panel</h2>
        <a href="admin_dashboard.php">📊 Dashboard</a>
        <a href="manage_students.php">👥 Manage Students</a>
        <a href="manage_institutes.php">🏢 Manage Institutes</a>
        <a href="manage_events.php">📅 Manage Events</a>
        <a href="manage_announcements.php">📢 Manage Announcements</a>
        <a href="manage_media.php" class="active">📸 Manage Media</a>
    </div>

    <div class="content">
        <div class="header">
            <h2>Event Media Gallery</h2>
            <a href="admin_logout.php" class="logout-btn">Logout</a>
        </div>

        <?php echo $message; ?>

        <div class="event-form-card" style="border-top-color: #8b5cf6;">
            <h3 style="margin-top: 0;">Upload Event Media</h3>
            <form action="manage_media.php" method="POST" enctype="multipart/form-data">

                <div class="input-group">
                    <label>Select Event</label>
                    <select name="event_id" required>
                        <option value="">-- Choose an Event --</option>
                        <?php
                        if ($events_rs->num_rows > 0) {
                            while ($event = $events_rs->fetch_assoc()) {
                                echo "<option value='" . $event['event_id'] . "'>" . $event['title'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="input-group">
                    <label>Media Type</label>
                    <select name="media_type" required>
                        <option value="Image">Image (JPG/PNG)</option>
                        <option value="Video">Video (MP4)</option>
                    </select>
                </div>

                <div class="input-group">
                    <label>Choose File</label>
                    <input type="file" name="media_file" required style="padding: 5px;">
                </div>

                <button type="submit" class="btn-success" style="background-color: #8b5cf6;">Upload Media</button>
            </form>
        </div>

        <!-- Uploaded Media Table -->
        <h3 style="margin-bottom: 10px;">Uploaded Media Files</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 20%;">Preview</th>
                    <th style="width: 40%;">Event Title</th>
                    <th style="width: 20%;">Type</th>
                    <th style="width: 20%;">File Path</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($media_rs->num_rows > 0) {
                    while ($media = $media_rs->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td><img src='" . $media['file_path'] . "' style='width: 80px; height: 50px; object-fit: cover; border-radius: 4px;'></td>";
                        echo "<td>" . $media['title'] . "</td>";
                        echo "<td><span style='background: #ede9fe; color: #7c3aed; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;'>" . $media['media_type'] . "</span></td>";
                        echo "<td style='font-size: 11px; word-break: break-all;'>" . $media['file_path'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' style='text-align: center; padding: 20px;'>No media uploaded yet.</td></tr>";
                }
                ?>
            </tbody>
        </table>

    </div>
</body>

</html>