<?php
session_start();
require_once 'connection.php';
$message = "";

if (isset($_SESSION['student_email'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    Database::setUpConnection();

    $fname = Database::$connection->real_escape_string($_POST['fname']);
    $lname = Database::$connection->real_escape_string($_POST['lname']);
    $email = Database::$connection->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $address = Database::$connection->real_escape_string($_POST['address']);
    $gender_id = Database::$connection->real_escape_string($_POST['gender_gender_id']);
    $institute_id = Database::$connection->real_escape_string($_POST['institutes_insti_id']);

    $check_query = "SELECT * FROM students WHERE email = '$email'";
    $result_set = Database::search($check_query);

    if ($result_set->num_rows > 0) {
        $message = "<div style='background-color: #fee2e2; color: #991b1b; padding: 10px; border-radius: 4px; margin-bottom: 15px; text-align: center; border: 1px solid #f87171;'>Error: This Email is already registered.</div>";
    } else {
        
    $insert_query = "INSERT INTO students (fname, lname, email, password, address, gender_gender_id, institutes_insti_id, status_status_id) 
                     VALUES ('$fname', '$lname', '$email', '$password', '$address', '$gender_id', '$institute_id', '1')";

        Database::iud($insert_query);

        $message = "<div style='background-color: #d1fae5; color: #065f46; padding: 10px; border-radius: 4px; margin-bottom: 15px; text-align: center; border: 1px solid #34d399;'>Registration successful! You can now <a href='login.php' style='color: #047857; font-weight: bold; text-decoration: underline;'>Login</a>.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusHub - Student Registration</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <!-- Header / Navbar -->
    <header>
        <div class="logo">CampusHub</div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="events.php">Events</a></li>
                <li><a href="login.php" class="login-btn">Login</a></li>
            </ul>
        </nav>
    </header>

    <!-- Registration Form Section -->
    <main class="form-page">
        <div class="form-container" style="max-width: 500px;">
            <h2>Create Student Account</h2>
            <p>Join CampusHub to participate in events and activities.</p>

            <?php echo $message; ?>

            <form action="register.php" method="POST">

                <div class="input-row">
                    <div class="input-group">
                        <label for="fname">First Name</label>
                        <input type="text" id="fname" name="fname" required>
                    </div>
                    <div class="input-group">
                        <label for="lname">Last Name</label>
                        <input type="text" id="lname" name="lname" required>
                    </div>
                </div>

                <div class="input-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="input-group">
                    <label for="address">Home Address</label>
                    <input type="text" id="address" name="address" required>
                </div>

                <div class="input-row">
                    <div class="input-group">
                        <div class="input-group">
                            <label for="gender">Gender</label>
                            <select id="gender" name="gender_gender_id" required>
                                <option value="">-- Select Gender --</option>

                                <?php
                                $gender_rs = Database::search("SELECT * FROM gender");

                                while ($gender_data = $gender_rs->fetch_assoc()) {
                                    echo "<option value='" . $gender_data['gender_id'] . "'>" . $gender_data['gender_name'] . "</option>";
                                }
                                ?>

                            </select>
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="institute">Institute</label>
                        <div class="input-group">
                            <select id="institute" name="institutes_insti_id" required>
                                <option value="">-- Select Institute --</option>

                                <?php
                                $institute_rs = Database::search("SELECT * FROM institutes");

                                while ($institute_data = $institute_rs->fetch_assoc()) {
                                    echo "<option value='" . $institute_data['insti_id'] . "'>" . $institute_data['insti_name'] . "</option>";
                                }
                                ?>

                            </select>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary submit-btn">Register Now</button>
                <p class="form-footer">Already have an account? <a href="login.php">Sign in here</a></p>
            </form>
        </div>
    </main>

    <footer>
        <p>&copy; 2026 CampusHub Information System. All Rights Reserved.</p>
    </footer>

</body>

</html>