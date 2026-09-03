<?php
session_start();
require_once 'connection.php';

if (!isset($_SESSION['student_email'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $event_id = $_GET['id'];
    $email = $_SESSION['student_email'];
    $current_date = date('Y-m-d'); 

    Database::setUpConnection();
    
    $event_id = Database::$connection->real_escape_string($event_id);

    $student_query = "SELECT student_id FROM students WHERE email = '$email'";
    $student_rs = Database::search($student_query);
    
    if ($student_rs->num_rows == 1) {
        $student_data = $student_rs->fetch_assoc();
        $student_id = $student_data['student_id'];

        $check_query = "SELECT * FROM registrations WHERE student_id = '$student_id' AND event_id = '$event_id'";
        $check_rs = Database::search($check_query);

        if ($check_rs->num_rows > 0) {
            echo "<script>
                    alert('You are already registered for this event!'); 
                    window.location.href='events.php';
                  </script>";
        } else {
            $insert_query = "INSERT INTO registrations (event_id, student_id, reg_date) 
                             VALUES ('$event_id', '$student_id', '$current_date')";
            Database::iud($insert_query);
            
            echo "<script>
                    alert('🎉 Registration Successful!'); 
                    window.location.href='events.php';
                  </script>";
        }
    } else {
        echo "<script>alert('Error: Student profile not found.'); window.location.href='events.php';</script>";
    }
} else {
    header("Location: events.php");
    exit();
}
?>