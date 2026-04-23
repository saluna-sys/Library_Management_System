<?php
session_start();

// 1. ADMIN SESSION CHECK
if(!isset($_SESSION['login_admin_username'])) {
    echo "<script>alert('Please login as admin first'); window.location='admin.php';</script>";
    exit();
}

include "connection.php";
include "admin_navbar.php";

// 2. GET IDs FROM URL
$studentid = isset($_GET['ed']) ? mysqli_real_escape_string($db, $_GET['ed']) : 0;
$bookid = isset($_GET['ed1']) ? mysqli_real_escape_string($db, $_GET['ed1']) : 0;

// 3. HANDLE FORM SUBMISSION (YOUR ORIGINAL LOGIC)
if(isset($_POST['submit'])) {
    $issuedate = mysqli_real_escape_string($db, $_POST['issuedate']);
    $returndate = mysqli_real_escape_string($db, $_POST['returndate']);
    $datetime = date('Y-m-d H:i:s', strtotime($_POST['returndatetime']));
    
    mysqli_begin_transaction($db);
    
    try {
        $book_query = mysqli_query($db, "SELECT quantity FROM books WHERE bookid = '$bookid'");
        $book = mysqli_fetch_assoc($book_query);
        
        if($book['quantity'] > 0) {
            $new_quantity = $book['quantity'] - 1;
            mysqli_query($db, "UPDATE books SET quantity = '$new_quantity' WHERE bookid = '$bookid'");
            
            if($new_quantity == 0) {
                mysqli_query($db, "UPDATE books SET status = 'Not Available' WHERE bookid = '$bookid'");
            }
            
            mysqli_query($db, "INSERT INTO timer (stdid, bid, date) VALUES ('$studentid', '$bookid', '$datetime')");
            
            // UPDATED: Set approve to 'yes' (Matches your return/expired logic)
            $update = mysqli_query($db, "UPDATE issueinfo 
                                        SET issuedate = '$issuedate',
                                            returndate = '$returndate',
                                            approve = 'yes'
                                        WHERE studentid = '$studentid' 
                                        AND bookid = '$bookid' 
                                        AND approve = 'no'");
            
            if($update) {
                mysqli_commit($db);
                echo "<script>alert('Book Issued Successfully!'); window.location='request_info.php';</script>";
            } else {
                throw new Exception("Failed to update issueinfo");
            }
        } else {
            throw new Exception("Book is out of stock!");
        }
    } catch (Exception $e) {
        mysqli_rollback($db);
        echo "<script>alert('Error: " . $e->getMessage() . "'); window.location='request_info.php';</script>";
    }
}

// 4. FETCH PENDING REQUEST DATA (Updated to look for 'no')
$q = "SELECT student.studentid, FullName, studentpic, issueinfo.bookid, books.bookname, 
             ISBN, price, bookpic, authors.authorname, category.categoryname 
      FROM issueinfo 
      INNER JOIN student ON issueinfo.studentid = student.studentid 
      INNER JOIN books ON issueinfo.bookid = books.bookid 
      LEFT JOIN authors ON authors.authorid = books.authorid 
      LEFT JOIN category ON category.categoryid = books.categoryid 
      WHERE student.studentid = '$studentid' 
      AND approve = 'no' 
      AND issueinfo.bookid = '$bookid'";

$res = mysqli_query($db, $q);

if(mysqli_num_rows($res) == 0) {
    echo "<script>alert('No pending request found!'); window.location='request_info.php';</script>";
    exit();
}

$row = mysqli_fetch_assoc($res);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Issue Book | ShelfNova</title>
    <link rel="stylesheet" href="admin.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        .issue-body {
            padding: 60px 20px;
            background-color: #f4f7f5;
            min-height: 100vh;
        }

        .issue-container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.05);
            overflow: hidden;
            display: flex;
        }

        /* Left Side: Info */
        .info-panel {
            flex: 1.2;
            padding: 40px;
            background-color: #fcfdfc;
            border-right: 1px solid #f1f5f9;
        }

        /* Right Side: Form */
        .form-panel {
            flex: 1;
            padding: 40px;
        }

        .section-header {
            font-size: 11px;
            font-weight: 800;
            color: #808847; /* Olive Gold */
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
            display: block;
        }

        .item-row {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
        }

        .item-row img {
            width: 50px; height: 50px; border-radius: 50%; object-fit: cover; border: 2px solid #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .book-img-large {
            width: 45px !important; height: 65px !important; border-radius: 4px !important;
        }

        .item-text b { display: block; font-size: 15px; color: #1e293b; }
        .item-text span { font-size: 12px; color: #64748b; }

        /* Form Inputs */
        .sn-field { margin-bottom: 20px; }
        .sn-field label { display: block; font-size: 12px; font-weight: 700; color: #2d6a4f; margin-bottom: 8px; }
        .sn-input {
            width: 100%; height: 45px; border-radius: 8px; border: 1.5px solid #e2e8f0;
            padding: 0 15px; font-family: inherit; background: #f8fafc; font-size: 13px;
        }

        .btn-issue {
            width: 100%; background-color: #2d6a4f; color: white !important;
            padding: 14px; border-radius: 10px; font-weight: 700; border: none;
            cursor: pointer; margin-top: 20px; text-transform: uppercase; letter-spacing: 1px;
        }
        .btn-issue:hover { background-color: #808847; }
    </style>
</head>
<body>

<div class="issue-body">
    <div class="issue-container">
        
        <!-- INFO PANEL -->
        <div class="info-panel">
            <span class="section-header">Student Profile</span>
            <div class="item-row">
                <div class="item-text">
                    <b><?php echo $row['FullName']; ?></b>
                    <span>ID: #<?php echo $row['studentid']; ?></span>
                </div>
            </div>

            <span class="section-header" style="margin-top:40px;">Book Selection</span>
            <div class="item-row">
                <img src="images/<?php echo $row['bookpic']; ?>" class="book-img-large">
                <div class="item-text">
                    <b><?php echo $row['bookname']; ?></b>
                    <span>ISBN: <?php echo $row['ISBN']; ?></span>
                </div>
            </div>
            
            <div style="margin-top:30px; padding:20px; background:#f0fdf4; border-radius:12px;">
                <p style="margin:0; font-size:12px; color:#166534; font-weight:600;">
                    <i class="fa-solid fa-circle-info"></i> Standard loan period is 14 days. Fine is calculated automatically after expiry.
                </p>
            </div>
        </div>

        <!-- FORM PANEL -->
        <div class="form-panel">
            <h2 style="color:#2d6a4f; font-size:22px; margin-bottom:30px;">Finalize Issue</h2>
            
            <form action="" method="post">
                <div class="sn-field">
                    <label>Issue Date</label>
                    <input type="text" name="issuedate" class="sn-input" value="<?php echo date('Y-m-d'); ?>" readonly>
                </div>

                <div class="sn-field">
                    <label>Simple Return Date</label>
                    <input type="date" name="returndate" class="sn-input" value="<?php echo date('Y-m-d', strtotime('+14 days')); ?>" required>
                </div>

                <div class="sn-field">
                    <label>Countdown Timer End</label>
                    <input type="datetime-local" name="returndatetime" class="sn-input" value="<?php echo date('Y-m-d\TH:i', strtotime('+14 days')); ?>" required>
                </div>

                <button type="submit" name="submit" class="btn-issue">Authorize Release</button>
                <p style="text-align:center; margin-top:20px;">
                    <a href="request_info.php" style="color:#94a3b8; font-size:12px; text-decoration:none;">Cancel Request</a>
                </p>
            </form>
        </div>

    </div>
</div>

<?php include "footer.php"; ?>

</body>
</html>