<?php
session_start();

// Check if student is logged in
if(!isset($_SESSION['login_student_username']) && !isset($_SESSION['studentid'])) {
    echo "<script>alert('Please login first'); window.location='student.php';</script>";
    exit();
}

include "connection.php";
include "student_navbar.php";

// Get student ID
$student_id = isset($_SESSION['studentid']) ? $_SESSION['studentid'] : 0;

if($student_id == 0 && isset($_SESSION['login_student_username'])) {
    $student_username = mysqli_real_escape_string($db, $_SESSION['login_student_username']);
    $student_query = mysqli_query($db, "SELECT studentid FROM student WHERE student_username = '$student_username'");
    $student_data = mysqli_fetch_assoc($student_query);
    $student_id = $student_data['studentid'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Issued Books | ShelfNova</title>
    <link rel="stylesheet" href="student_auth.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    
    <style>
        /* --- MINIMALIST DESIGN SYNCED WITH REQUESTS PAGE --- */
        .issue-page {
            padding: 60px 20px;
            background-color: #ffffff; /* Clean white */
            min-height: 100vh;
        }

        .container-slim {
            max-width: 1200px;
            margin: 0 auto;
        }

        .page-title {
            color: #2d6a4f;
            font-size: 24px;
            font-weight: 800;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 1.5px solid #f1f5f9;
            padding-bottom: 15px;
        }

        /* Fine Alert Bar */
        .fine-warning {
            background-color: #fef2f2;
            border-left: 4px solid #ef4444;
            padding: 15px 20px;
            color: #991b1b;
            font-weight: 700;
            border-radius: 6px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* Minimal Table */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            padding: 15px;
            font-size: 11px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 1.5px solid #f1f5f9;
        }

        td {
            padding: 18px 15px;
            border-bottom: 1px solid #f8fafc;
            font-size: 14px;
            color: #334155;
            vertical-align: middle;
        }

        tr:hover td { background-color: #f9fbf9; }

        .book-meta {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .book-img {
            width: 35px;
            height: 50px;
            object-fit: cover;
            border-radius: 3px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        /* Status Dot Logic */
        .status-dot {
            font-weight: 700;
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .status-dot::before {
            content: '';
            width: 7px;
            height: 7px;
            border-radius: 50%;
        }
        .st-approved { color: #2d6a4f; } .st-approved::before { background: #2d6a4f; }
        .st-expired { color: #ef4444; } .st-expired::before { background: #ef4444; }

        /* Return Button (Olive Gold) */
        .btn-action-return {
            background-color: #808847;
            color: white !important;
            padding: 6px 16px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            transition: 0.3s;
        }
        .btn-action-return:hover { background-color: #2d6a4f; }

        .back-link {
            display: inline-block;
            margin-top: 40px;
            color: #808847;
            text-decoration: none;
            font-weight: 700;
            font-size: 13px;
        }
    </style>
</head>
<body>

<div class="issue-page">
    <div class="container-slim">
        
        <h2 class="page-title">Issued Books List</h2>

        <?php
        // YOUR ORIGINAL PHP: Fetching data
        $q = mysqli_query($db, "SELECT books.bookid, books.bookname, books.ISBN, books.bookpic, books.price,
                                issueinfo.issuedate, issueinfo.returndate, issueinfo.approve, issueinfo.fine,
                                authors.authorname, category.categoryname 
                                FROM issueinfo 
                                JOIN books ON issueinfo.bookid = books.bookid 
                                JOIN authors ON authors.authorid = books.authorid 
                                JOIN category ON category.categoryid = books.categoryid 
                                WHERE issueinfo.studentid = '$student_id' 
                                AND (issueinfo.approve = 'approved' OR issueinfo.approve = 'expired' OR issueinfo.approve = 'yes')
                                ORDER BY issueinfo.returndate ASC");

        // YOUR ORIGINAL PHP: Calculate total fine
        $fine_query = mysqli_query($db, "SELECT SUM(fine) as total_fine FROM issueinfo WHERE studentid = '$student_id' AND approve = 'expired'");
        $fine_data = mysqli_fetch_assoc($fine_query);
        $total_fine = $fine_data['total_fine'] ? $fine_data['total_fine'] : 0;

        if($total_fine > 0): ?>
            <div class="fine-warning">
                <i class="fas fa-circle-exclamation"></i>
                Attention: You have an outstanding fine of Rs. <?php echo $total_fine; ?>
            </div>
        <?php endif; ?>

        <?php if(mysqli_num_rows($q) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Book Information</th>
                        <th>ISBN</th>
                        <th>Issue Date</th>
                        <th>Return Date</th>
                        <th>Status</th>
                        <th>Fine</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                while($row = mysqli_fetch_assoc($q)) {
                    // YOUR ORIGINAL PHP: Expired date logic
                    $return_date = strtotime($row['returndate']);
                    $current_date = strtotime(date("Y-m-d"));
                    $diff = $current_date - $return_date;
                    
                    if($diff > 0 && ($row['approve'] == 'approved' || $row['approve'] == 'yes')) {
                        $day = floor($diff/(60*60*24));
                        $fine = $day * 10;
                        mysqli_query($db, "UPDATE issueinfo SET approve = 'expired', fine = $fine 
                                          WHERE studentid = '$student_id' AND bookid = '{$row['bookid']}'");
                        $row['approve'] = 'expired';
                        $row['fine'] = $fine;
                    }
                    
                    $is_expired = ($row['approve'] == 'expired');
                    ?>
                    <tr>
                        <td>
                            <div class="book-meta">
                                <img src="images/<?php echo $row['bookpic']; ?>" class="book-img">
                                <div>
                                    <strong><?php echo htmlspecialchars($row['bookname']); ?></strong><br>
                                    <small style="color:#94a3b8;"><?php echo htmlspecialchars($row['authorname']); ?></small>
                                </div>
                            </div>
                        </td>
                        <td style="font-family: monospace;"><?php echo $row['ISBN']; ?></td>
                        <td><?php echo date('M d, Y', strtotime($row['issuedate'])); ?></td>
                        <td style="<?php echo $is_expired ? 'color:#ef4444; font-weight:700;' : ''; ?>">
                            <?php echo date('M d, Y', strtotime($row['returndate'])); ?>
                        </td>
                        <td>
                            <span class="status-dot <?php echo $is_expired ? 'st-expired' : 'st-approved'; ?>">
                                <?php echo $is_expired ? 'Overdue' : 'Issued'; ?>
                            </span>
                        </td>
                        <td style="color:<?php echo $row['fine'] > 0 ? '#ef4444' : '#2d6a4f'; ?>; font-weight:700;">
                            Rs. <?php echo $row['fine']; ?>
                        </td>
                        <td>
                            <?php if(!$is_expired): ?>
                                <a href="return_book.php?bookid=<?php echo $row['bookid']; ?>" 
                                   class="btn-action-return"
                                   onclick="return confirm('Confirm book return?')">Return</a>
                            <?php else: ?>
                                <span style="font-size: 11px; color: #ef4444; font-weight: 700;">PAY FINE</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        <?php else: ?>
            <div style="text-align:center; padding:80px; color:#94a3b8;">
                <i class="fas fa-book-open" style="font-size:40px; margin-bottom:15px; opacity:0.3; display:block;"></i>
                No books currently issued to your account.
            </div>
        <?php endif; ?>

        <a href="student_dashboard.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>
</div>

</body>
</html>