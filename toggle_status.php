<?php
session_start();
require_once 'connection.php';

if (!isset($_SESSION['admin_email'])) {
    header("Location: admin_login.php");
    exit();
}

if (isset($_GET['email'])) {
    
    Database::setUpConnection();

    $student_email = Database::$connection->real_escape_string($_GET['email']);
    
    $query = "SELECT status_status_id FROM students WHERE email = '$student_email'";
    $rs = Database::search($query);
    
    if ($rs->num_rows == 1) {
        $data = $rs->fetch_assoc();
    
        if ($data['status_status_id'] == '1') {
            $new_status = '2'; 
        } else {
            $new_status = '1';
        }

        $update_query = "UPDATE students SET status_status_id = '$new_status' WHERE email = '$student_email'";
        Database::iud($update_query);
    }
}

header("Location: manage_students.php");
exit();
?>