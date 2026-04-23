<?php
session_start();
include "connection.php";
include "student_navbar.php";

// 1. SECURITY CHECK
if(!isset($_SESSION['studentid'])) {
    echo "<script>window.location='student.php';</script>";
    exit();
}

$studentid = $_SESSION['studentid'];
$message = "";
$error = "";

// 2. HANDLE NEW REQUEST (WITH AUTO-DECREMENT LOGIC)
if(isset($_GET['req'])) {
    $bookid = mysqli_real_escape_string($db, $_GET['req']);
    
    // FIRST: Check current stock and status of the book
    $stock_q = mysqli_query($db, "SELECT quantity, status FROM books WHERE bookid = '$bookid'");
    $book_data = mysqli_fetch_assoc($stock_q);

    if($book_data['quantity'] <= 0 || $book_data['status'] != 'Available') {
        $error = "Sorry, this book is currently out of stock or unavailable.";
    } else {
        // SECOND: Check if student already has a pending or active request for this specific book
        $check_dup = mysqli_query($db, "SELECT * FROM issueinfo WHERE studentid = '$studentid' AND bookid = '$bookid' AND (approve = 'no' OR approve = 'yes' OR approve = 'approved')");

        if(mysqli_num_rows($check_dup) > 0) {
            $error = "You already have an active request or possession of this book.";
        } else {
            // THIRD: Start updating the inventory
            // Decrease quantity by 1
            $update_qty = mysqli_query($db, "UPDATE books SET quantity = quantity - 1 WHERE bookid = '$bookid'");
            
            // If quantity reached 0, mark as Not Available
            mysqli_query($db, "UPDATE books SET status = 'Not Available' WHERE bookid = '$bookid' AND quantity <= 0");

            // FOURTH: Insert the request into issueinfo
            $insert = mysqli_query($db, "INSERT INTO issueinfo (studentid, bookid, issuedate, returndate, approve, fine) VALUES ('$studentid', '$bookid', NULL, NULL, 'no', 0)");
            
            if($insert) {
                $message = "Book requested successfully! Quantity updated in library.";
            } else {
                $error = "Error sending request: " . mysqli_error($db);
            }
        }
    }
}

// 3. FETCH DATA (STRICT USER FILTER)
$requests_query = mysqli_query($db, "
    SELECT issueinfo.*, books.bookname, books.bookpic, books.price
    FROM issueinfo
    JOIN books ON issueinfo.bookid = books.bookid
    WHERE issueinfo.studentid = '$studentid'
    ORDER BY issueinfo.id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Requests | ShelfNova</title>
    <link rel="stylesheet" href="student_auth.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap" rel="stylesheet">
    
    <style>
        .request-outer-wrapper { padding: 50px 0; background-color: #f4f7f5; min-height: 90vh; }
        .container-slim { width: 100%; max-width: 1200px; margin: 0 auto; padding: 0 30px; }
        .page-title-main { color: #2d6a4f; font-size: 26px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 30px; }
        .table-card { background: #ffffff; border-radius: 18px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); overflow: hidden; border: 1px solid #eef2f0; }
        table { width: 100%; border-collapse: collapse; }
        th { background-color: #f8fafc; padding: 18px 20px; text-align: left; font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; border-bottom: 2px solid #f1f5f9; }
        td { padding: 18px 20px; border-bottom: 1px solid #f8fafc; font-size: 14px; color: #1e293b; vertical-align: middle; }
        tr:hover td { background-color: #f9fbf9; }
        .book-thumb-mini { width: 38px; height: 50px; object-fit: cover; border-radius: 4px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .status-pill { font-weight: 700; font-size: 12px; display: flex; align-items: center; gap: 8px; }
        .status-pill::before { content: ''; width: 8px; height: 8px; border-radius: 50%; }
        .st-no { color: #808847; } .st-no::before { background: #808847; } 
        .st-yes { color: #2d6a4f; } .st-yes::before { background: #2d6a4f; } 
        .st-returned { color: #64748b; } .st-returned::before { background: #64748b; }
        .sn-alert { padding: 15px; border-radius: 10px; margin-bottom: 25px; text-align: center; font-size: 14px; font-weight: 600; }
        .alert-success { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
        .alert-error { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
        .back-link-olive { display: inline-block; margin-top: 30px; color: #808847; text-decoration: none; font-weight: 700; font-size: 14px; transition: 0.3s; }
        .back-link-olive:hover { color: #2d6a4f; transform: translateX(-5px); }
    </style>
</head>
<body>

<div class="request-outer-wrapper">
    <div class="container-slim">
        <h2 class="page-title-main">Request History</h2>

        <?php if($message): ?>
            <div class="sn-alert alert-success"><i class="fa-solid fa-circle-check"></i> <?php echo $message; ?></div>
        <?php endif; ?>
        <?php if($error): ?>
            <div class="sn-alert alert-error"><i class="fa-solid fa-circle-exclamation"></i> <?php echo $error; ?></div>
        <?php endif; ?>

        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>Book</th>
                        <th>Title</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Expected Return</th>
                        <th>Fine</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($requests_query) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($requests_query)): ?>
                            <tr>
                                <td><img src="images/<?php echo $row['bookpic']; ?>" class="book-thumb-mini"></td>
                                <td><b style="color:#1e293b;"><?php echo htmlspecialchars($row['bookname']); ?></b></td>
                                <td style="color:#2d6a4f; font-weight:700;">Rs. <?php echo $row['price']; ?></td>
                                <td>
                                    <?php 
                                        $s = $row['approve'];
                                        $text = ($s == 'no') ? 'Pending' : (($s == 'yes' || $s == 'approved') ? 'Approved' : ucfirst($s));
                                        $cls = 'st-'.$s;
                                    ?>
                                    <span class="status-pill <?php echo $cls; ?>"><?php echo $text; ?></span>
                                </td>
                                <td style="color:#64748b; font-size:13px;">
                                    <?php echo ($row['returndate']) ? date('M d, Y', strtotime($row['returndate'])) : '<span style="opacity:0.5">Not Issued</span>'; ?>
                                </td>
                                <td style="color:#ef4444; font-weight:700;">Rs. <?php echo $row['fine']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6" style="text-align:center; padding:60px; color:#94a3b8;">You haven't requested any books yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <a href="student_books.php" class="back-link-olive">
            <i class="fa-solid fa-arrow-left"></i> Browse more books
        </a>
    </div>
</div>

</body>
</html>