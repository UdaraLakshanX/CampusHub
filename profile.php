<?php
session_start();
if (!isset($_SESSION['student_email'])) {
    header("Location: login.php");
    exit(); 
}

require_once 'connection.php';
$message = "";
$email = $_SESSION['student_email'];
Database::setUpConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = Database::$connection->real_escape_string($_POST['fname']);
    $lname = Database::$connection->real_escape_string($_POST['lname']);
    $address = Database::$connection->real_escape_string($_POST['address']);
    
    $profile_pic_query_part = ""; 

    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        if (!is_dir('uploads')) mkdir('uploads', 0777, true);
        
        $file_name = uniqid() . "_" . basename($_FILES["profile_pic"]["name"]);
        $target_file = "uploads/" . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        if($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg") {
            if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
                $pic_insert = "INSERT INTO profile_pic (path) VALUES ('$target_file')";
                Database::iud($pic_insert);
                $new_pic_id = Database::$connection->insert_id; 
                $profile_pic_query_part = ", profile_pic_id = '$new_pic_id'";
            }
        } else {
            $message = "<div style='color: #ef4444; text-align: center;'>Only JPG & PNG allowed.</div>";
        }
    }

    if (empty($message)) {
        $update_query = "UPDATE students SET 
                         fname = '$fname', 
                         lname = '$lname', 
                         address = '$address' 
                         $profile_pic_query_part 
                         WHERE email = '$email'";
        
        Database::iud($update_query);
        $_SESSION['student_fname'] = $fname; 
        $message = "<div style='background-color: #d1fae5; color: #065f46; padding: 10px; text-align: center;'>Profile updated successfully!</div>";
    }
}

$query = "SELECT students.*, profile_pic.path AS pic_path 
          FROM students 
          LEFT JOIN profile_pic ON students.profile_pic_id = profile_pic.id 
          WHERE students.email = '$email'";
$result_set = Database::search($query);
$student_data = $result_set->fetch_assoc();

$display_pic = "assets/avatar.png"; 
if (!empty($student_data['pic_path']) && file_exists($student_data['pic_path'])) {
    $display_pic = $student_data['pic_path'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusHub - My Profile</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="logo">CampusHub</div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="events.php">Events</a></li>
                <li><a href="profile.php">My Profile</a></li>
                <li><a href="logout.php" class="login-btn" style="background-color: #ef4444;">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main class="form-page">
        <div class="form-container">
            <h2>Manage Profile</h2>
            <?php echo $message; ?>
            <form action="profile.php" method="POST" enctype="multipart/form-data">
                <div class="profile-pic-section">
                    <img src="<?php echo $display_pic; ?>" class="profile-preview" style="width: 120px; height: 120px; border-radius: 50%;">
                    <div class="input-group">
                        <label>Change Profile Photo</label>
                        <input type="file" name="profile_pic" accept="image/*">
                    </div>
                </div>

                <div class="input-row">
                    <div class="input-group">
                        <label>First Name</label>
                        <input type="text" name="fname" value="<?php echo $student_data['fname']; ?>" required>
                    </div>
                    <div class="input-group">
                        <label>Last Name</label>
                        <input type="text" name="lname" value="<?php echo $student_data['lname']; ?>" required>
                    </div>
                </div>
                
                <div class="input-group">
                    <label>Email Address</label>
                    <input type="email" name="email" value="<?php echo $student_data['email']; ?>" readonly style="background-color: #f3f4f6;">
                </div>

                <div class="input-group">
                    <label>Home Address</label>
                    <input type="text" name="address" value="<?php echo $student_data['address']; ?>" required>
                </div>

                <button type="submit" class="btn btn-primary submit-btn">Save Changes</button>
            </form>
        </div>
    </main>
</body>
</html>