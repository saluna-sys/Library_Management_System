<?php
session_start();

// 1. Check if student is logged in
if(!isset($_SESSION['login_student_username']) && !isset($_SESSION['studentid'])) {
    echo "<script>alert('Please login first'); window.location='student.php';</script>";
    exit();
}

include "connection.php";

// 2. Get student ID from session
$student_id = $_SESSION['studentid'];

if(isset($_GET['bookid'])) {
    $bookid = mysqli_real_escape_string($db, $_GET['bookid']);
    $return_date = date("Y-m-d");
    
    // This is the exact string your system uses for expired books
    $expired_html = '<p style="color:yellow; background-color:red;">EXPIRED</p>';
    
    // Start transaction to ensure data integrity
    mysqli_begin_transaction($db);
    
    try {
        // A. Update issueinfo: Set status to 'returned'
        // We look for 'yes', 'approved', or the HTML 'EXPIRED' tag
        $update_issue = "UPDATE issueinfo 
                         SET returndate = '$return_date', 
                             approve = 'returned' 
                         WHERE studentid = '$student_id' 
                         AND bookid = '$bookid' 
                         AND (approve = 'yes' OR approve = 'approved' OR approve = '$expired_html')";
        
        $res = mysqli_query($db, $update_issue);
        
        if($res && mysqli_affected_rows($db) > 0) {
            
            // B. Update book quantity (increase by 1)
            mysqli_query($db, "UPDATE books SET quantity = quantity + 1 WHERE bookid = '$bookid'");
            
            // C. Ensure status is set to Available
            mysqli_query($db, "UPDATE books SET status = 'Available' WHERE bookid = '$bookid'");
            
            // D. Delete from timer table (stop the countdown)
            mysqli_query($db, "DELETE FROM timer WHERE stdid = '$student_id' AND bid = '$bookid'");
            
            mysqli_commit($db);
            echo "<script>
                alert('Book returned successfully!');
                window.location = 'student_issue_info.php';
            </script>";
            exit();
            
        } else {
            throw new Exception("No active issued record found for this book.");
        }
        
    } catch (Exception $e) {
        mysqli_rollback($db);
        echo "<script>
            alert('Error: " . $e->getMessage() . "');
            window.location = 'student_issue_info.php';
        </script>";
        exit();
    }
} else {
    header("location:student_issue_info.php");
}
?>