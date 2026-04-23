<?php
	
	include "connection.php";
	session_start();

?>
<!DOCTYPE html>
<html>
<head>
</head>
<body>
<?php
session_start();
include "connection.php";

if(!isset($_SESSION['studentid']))
{
    echo "<script>alert('Login first'); window.location='student.php';</script>";
    exit();
}

if(isset($_GET['req']))
{
    $studentid = $_SESSION['studentid'];
    $bookid = $_GET['req'];

    // 🔒 CHECK MAX 3 BOOKS
    $count = mysqli_query($db,"
        SELECT * FROM issueinfo 
        WHERE studentid='$studentid' 
        AND approve IN ('pending','approved')
    ");

    if(mysqli_num_rows($count) >= 3)
    {
        echo "<script>alert('Max 3 books allowed'); window.location='student_books.php';</script>";
        exit();
    }

    // 🔒 CHECK ALREADY REQUESTED
    $check = mysqli_query($db,"
        SELECT * FROM issueinfo 
        WHERE studentid='$studentid' 
        AND bookid='$bookid' 
        AND approve IN ('pending','approved')
    ");

    if(mysqli_num_rows($check) > 0)
    {
        echo "<script>alert('Already requested'); window.location='student_books.php';</script>";
        exit();
    }

    // 🔒 CHECK QUANTITY
    $q = mysqli_query($db,"SELECT quantity FROM books WHERE bookid='$bookid'");
    $row = mysqli_fetch_assoc($q);

    if($row['quantity'] <= 0)
    {
        echo "<script>alert('Book not available'); window.location='student_books.php';</script>";
        exit();
    }

    // ✅ INSERT
    mysqli_query($db,"
        INSERT INTO issueinfo (studentid, bookid, issuedate, returndate, approve, fine)
        VALUES ('$studentid','$bookid','','','pending',0)
    ");

    // ✅ DECREASE QUANTITY
    mysqli_query($db,"
        UPDATE books 
        SET quantity = quantity - 1 
        WHERE bookid='$bookid'
    ");

    // ✅ UPDATE STATUS IF ZERO
    mysqli_query($db,"
        UPDATE books 
        SET status='Not Available' 
        WHERE bookid='$bookid' AND quantity=0
    ");

    echo "<script>alert('Book requested'); window.location='student_books.php';</script>";
}
?>
</body>
</html>
