<?php
session_start();
require_once 'connection.php';

if (!isset($_SESSION['student_email'])) {
    header("Location: login.php");
    exit(); 
}

Database::setUpConnection();

$query = "SELECT media.*, events.title, events.event_date 
          FROM media 
          INNER JOIN events ON media.events_event_id = events.event_id 
          ORDER BY media.media_id DESC";
$result_set = Database::search($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Media Gallery - CampusHub</title>
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
                <li><a href="media.php" style="color: #3b82f6; font-weight: bold;">Media</a></li>

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

    <section class="page-container" style="padding-bottom: 0;">
        <div class="admin-header" style="text-align: center;">
            <h1>Campus Media Gallery</h1>
            <p>Explore moments, highlights, and resources from our recent campus events.</p>
        </div>
    </section>

    <section class="page-container">
        <div class="filter-buttons" style="text-align: center; margin-bottom: 2rem;">
            <button id="btn-all" class="btn filter-btn" onclick="filterMedia('all')" style="margin: 0 5px; background: #2563eb; color: white; border: 1px solid #2563eb;">All</button>
            <button id="btn-image" class="btn filter-btn" onclick="filterMedia('image')" style="margin: 0 5px; background: transparent; color: #2563eb; border: 1px solid #2563eb;">Photos</button>
            <button id="btn-video" class="btn filter-btn" onclick="filterMedia('video')" style="margin: 0 5px; background: transparent; color: #2563eb; border: 1px solid #2563eb;">Videos</button>
        </div>

        <div class="media-grid">
            
            <?php
            if ($result_set->num_rows > 0) {
                while ($media = $result_set->fetch_assoc()) {
                    
                    $file_path = $media['file_path'];
                    if (!file_exists($file_path) && file_exists('admin/' . $file_path)) {
                        $file_path = 'admin/' . $file_path;
                    }

                    $type = strtolower(trim($media['media_type']));
                    $category = ($type === 'video') ? 'video' : 'image';

                    echo '<div class="media-card" data-category="' . $category . '">';
                    echo '    <div class="media-thumbnail" style="position: relative; overflow: hidden; border-radius: 8px 8px 0 0;">';
                    
                    if ($category === 'video') {
                        echo '        <video src="' . htmlspecialchars($file_path) . '" style="width: 100%; height: 200px; object-fit: cover; display: block;" controls></video>';
                        echo '        <span class="media-badge video-badge" style="position: absolute; top: 10px; right: 10px; background: rgba(255,255,255,0.9); color: #ef4444; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">🎥 Video</span>';
                    } else {
                        echo '        <img src="' . htmlspecialchars($file_path) . '" alt="Event Image" style="width: 100%; height: 200px; object-fit: cover; display: block;">';
                        echo '        <span class="media-badge image-badge" style="position: absolute; top: 10px; right: 10px; background: rgba(255,255,255,0.9); color: #2563eb; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">📷 Image</span>';
                    }
                    
                    echo '    </div>';
                    
                    echo '    <div class="media-info" style="padding: 15px; border-top: 1px solid #e5e7eb;">';
                    echo '        <h4 style="margin: 0 0 5px 0; font-size: 16px; color: #1f2937;">' . htmlspecialchars($media['title']) . '</h4>';
                    echo '        <p style="margin: 0 0 15px 0; font-size: 13px; color: #6b7280;">📅 Event Date: ' . date('Y-m-d', strtotime($media['event_date'])) . '</p>';
                    
                    if ($category === 'video') {
                        echo '        <a href="' . htmlspecialchars($file_path) . '" target="_blank" class="btn btn-small" style="display: block; text-align: center; background-color: #fee2e2; color: #991b1b; padding: 8px; border-radius: 4px; text-decoration: none; font-weight: bold; font-size: 14px;">Watch Video</a>';
                    } else {
                        echo '        <a href="' . htmlspecialchars($file_path) . '" target="_blank" class="btn btn-small" style="display: block; text-align: center; background-color: #f3f4f6; color: #374151; padding: 8px; border-radius: 4px; text-decoration: none; font-weight: bold; font-size: 14px;">View Full Image</a>';
                    }
                    
                    echo '    </div>';
                    echo '</div>';
                }
            } else {
                echo '<div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #6b7280;">No media available yet.</div>';
            }
            ?>

        </div>
    </section>

    <script>
        function filterMedia(filterType) {
            const buttons = document.querySelectorAll('.filter-btn');
            buttons.forEach(btn => {
                btn.style.background = 'transparent';
                btn.style.color = '#2563eb';
            });

            const activeBtn = document.getElementById('btn-' + filterType);
            activeBtn.style.background = '#2563eb';
            activeBtn.style.color = 'white';

            const cards = document.querySelectorAll('.media-card');
            cards.forEach(card => {
                const category = card.getAttribute('data-category');
                
                if (filterType === 'all' || category === filterType) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }
    </script>

</body>
</html>