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

    $email = Database::$connection->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    $query = "SELECT * FROM students WHERE email = '$email'";
    $result_set = Database::search($query);

    if ($result_set->num_rows == 1) {
        $student_data = $result_set->fetch_assoc();

        if (password_verify($password, $student_data['password'])) {

            if ($student_data['status_status_id'] == '1') {
                $_SESSION['student_email'] = $student_data['email'];
                $_SESSION['student_fname'] = $student_data['fname'];
                header("Location: index.php");
                exit();
            } else {
                $message = "<div style='color: red; text-align: center; margin-bottom: 10px;'>Your account has been deactivated by the Admin!</div>";
            }
        } else {
            $message = "<div style='color: red; text-align: center; margin-bottom: 10px;'>Incorrect Password!</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusHub - Login</title>
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
                <li><a href="register.php" class="login-btn">Register</a></li>
            </ul>
        </nav>
    </header>

    <!-- Login Form Section -->
    <main class="form-page">
        <div class="form-container" style="max-width: 400px;">
            <h2>Welcome Back!</h2>
            <p>Sign in to access your CampusHub account.</p>

            <?php echo $message; ?>

            <form action="login.php" method="POST">

                <div class="input-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required placeholder="Enter your email">
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required placeholder="Enter your password">
                </div>

                <button type="submit" class="btn btn-primary submit-btn">Sign In</button>

                <p class="form-footer">Don't have an account? <a href="register.php">Create one here</a></p>
            </form>
        </div>
    </main>

    <footer>
        <p>&copy; 2026 CampusHub Information System. All Rights Reserved.</p>
    </footer>

</body>

</html>