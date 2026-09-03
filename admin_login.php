<?php
session_start();
require_once 'connection.php';
$message = "";

if (isset($_SESSION['admin_email'])) {
    header("Location: admin_dashboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    Database::setUpConnection();
    
    $email = Database::$connection->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    $query = "SELECT * FROM `admin` WHERE email = '$email'";
    $result_set = Database::search($query);

    if ($result_set->num_rows == 1) {
        $admin_data = $result_set->fetch_assoc();

        if ($password == $admin_data['password']) {
            $_SESSION['admin_email'] = $admin_data['email'];
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $message = "<div style='color: red; text-align: center; margin-bottom: 10px;'>Incorrect Password!</div>";
        }
    } else {
        $message = "<div style='color: red; text-align: center; margin-bottom: 10px;'>Invalid Admin Email!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Login</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { background-color: #1e293b; }
        .admin-form-container { background: white; padding: 30px; border-radius: 8px; max-width: 400px; margin: 100px auto; box-shadow: 0 10px 15px rgba(0,0,0,0.5); }
    </style>
</head>
<body>

    <div class="admin-form-container">
        <h2 style="text-align: center; color: #0f172a; margin-bottom: 20px;">⚙️ Admin Control Panel</h2>
        
        <?php echo $message; ?>
        
        <form action="admin_login.php" method="POST">
            <div class="input-group" style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Admin Email</label>
                <input type="email" name="email" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div class="input-group" style="margin-bottom: 20px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Password</label>
                <input type="password" name="password" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 10px; background-color: #0f172a; color: white; border: none; border-radius: 4px; font-size: 16px; cursor: pointer;">Secure Login</button>
            <br><br>
            <a href="index.php" style="display: block; text-align: center; text-decoration: none; color: #2563eb;">&larr; Back to Main Website</a>
        </form>
    </div>

</body>
</html>