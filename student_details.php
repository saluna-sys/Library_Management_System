<?php
    include "connection.php";
    include "admin_navbar.php";

    // 1. Get Student ID from URL
    if(!isset($_GET['id'])) {
        echo "<script>window.location='student_info.php';</script>";
        exit();
    }
    
    $id = mysqli_real_escape_string($db, $_GET['id']);

    // 2. Fetch Student Profile
    $q = mysqli_query($db, "SELECT * FROM student WHERE studentid = '$id'");
    if(mysqli_num_rows($q) == 0) {
        echo "<script>alert('Student not found'); window.location='student_info.php';</script>";
        exit();
    }
    $row = mysqli_fetch_assoc($q);
    $initial = strtoupper(substr($row['FullName'], 0, 1));
    
    // Define the image path for the fallback logic
    $pic_path = "images/" . $row['studentpic'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Details | ShelfNova</title>
    <link rel="stylesheet" href="admin.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        .details-wrapper {
            padding: 60px 0;
            background-color: #f4f7f5;
            min-height: 100vh;
        }

        .container-slim {
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 30px;
        }

        /* --- PROFILE HEADER CARD --- */
        .profile-header-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            display: flex;
            align-items: center;
            gap: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            margin-bottom: 40px;
            border: 1px solid #eef2f0;
        }

        /* Large Header Avatar */
        .header-avatar-circle {
            width: 100px;
            height: 100px;
            background-color: #2d6a4f;
            color: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 40px;
            box-shadow: 0 4px 10px rgba(45, 106, 79, 0.15);
            border: 3px solid #ffffff;
            flex-shrink: 0;
            overflow: hidden;
        }

        .header-img-large {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .student-main-info h1 {
            font-size: 28px;
            color: #1e293b;
            margin: 0 0 5px 0;
        }

        .student-main-info p {
            color: #808847;
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #f1f5f9;
        }

        .contact-item { font-size: 14px; color: #64748b; }
        .contact-item i { color: #2d6a4f; margin-right: 8px; width: 15px; }

        /* --- BORROWING HISTORY TABLE --- */
        .history-section-title {
            color: #2d6a4f;
            font-weight: 800;
            font-size: 18px;
            margin-bottom: 20px;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .table-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.03);
            border: 1px solid #eef2f0;
        }

        table { width: 100%; border-collapse: collapse; }
        th {
            background-color: #f8fafc;
            padding: 15px 20px;
            text-align: left;
            font-size: 11px;
            color: #94a3b8;
            text-transform: uppercase;
            border-bottom: 1px solid #f1f5f9;
        }
        td { padding: 15px 20px; border-bottom: 1px solid #f8fafc; font-size: 14px; color: #334155; }
        
        .book-thumb {
            width: 30px; height: 40px; object-fit: cover; border-radius: 3px;
        }

        .badge-status {
            padding: 4px 10px;
            border-radius: 50px;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
        }
        .bg-issued { background: #f0fdf4; color: #166534; }
        .bg-returned { background: #f1f5f9; color: #475569; }
        .bg-expired { background: #fef2f2; color: #991b1b; }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #64748b;
            text-decoration: none;
            font-weight: 700;
            font-size: 13px;
            transition: 0.3s;
        }
        .back-link:hover { color: #2d6a4f; transform: translateX(-5px); }
    </style>
</head>
<body>

<div class="details-wrapper">
    <div class="container-slim">
        
        <a href="student_info.php" class="back-link">
            <i class="fa-solid fa-arrow-left"></i> Back to Student List
        </a>

        <!-- 1. STUDENT SUMMARY CARD -->
        <div class="profile-header-card">
            <div class="header-avatar-circle">
                <?php 
                    // Corrected Image Path Logic
                    if(!empty($row['studentpic']) && file_exists($pic_path)) {
                        echo "<img src='$pic_path' class='header-img-large' alt='User'>";
                    } else {
                        echo $initial;
                    }
                ?>
            </div>
            <div class="student-main-info" style="flex:1;">
                <p>Library Member Profile</p>
                <h1><?php echo htmlspecialchars($row['FullName']); ?></h1>
                
                <div class="contact-grid">
                    <div class="contact-item"><i class="fa-solid fa-id-badge"></i> ID: #<?php echo $row['studentid']; ?></div>
                    <div class="contact-item"><i class="fa-solid fa-user"></i> Username: <?php echo $row['student_username']; ?></div>
                    <div class="contact-item"><i class="fa-solid fa-envelope"></i> <?php echo $row['Email']; ?></div>
                    <div class="contact-item"><i class="fa-solid fa-phone"></i> <?php echo $row['PhoneNumber']; ?></div>
                </div>
            </div>
        </div>

        <!-- 2. BORROWING HISTORY -->
        <h3 class="history-section-title"><i class="fa-solid fa-clock-rotate-left"></i> Borrowing History</h3>
        
        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>Book</th>
                        <th>Issued On</th>
                        <th>Return Date</th>
                        <th>Status</th>
                        <th>Fine</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $hist = mysqli_query($db, "
                            SELECT i.*, b.bookname, b.bookpic 
                            FROM issueinfo i 
                            JOIN books b ON i.bookid = b.bookid 
                            WHERE i.studentid = '$id' 
                            ORDER BY i.id DESC
                        ");

                        if(mysqli_num_rows($hist) > 0) {
                            while($h = mysqli_fetch_assoc($hist)) {
                                $status = $h['approve'];
                                $badge = ($status == 'yes' || $status == 'approved') ? 'bg-issued' : (($status == 'returned') ? 'bg-returned' : 'bg-expired');
                                $display_status = ($status == 'yes' || $status == 'approved') ? 'Active' : ucfirst($status);
                    ?>
                        <tr>
                            <td style="display:flex; align-items:center; gap:12px;">
                                <img src="images/<?php echo $h['bookpic']; ?>" class="book-thumb" onerror="this.src='images/book_default.png'">
                                <b><?php echo htmlspecialchars($h['bookname']); ?></b>
                            </td>
                            <td><?php echo $h['issuedate'] ? date('M d, Y', strtotime($h['issuedate'])) : '---'; ?></td>
                            <td><?php echo $h['returndate'] ? date('M d, Y', strtotime($h['returndate'])) : '---'; ?></td>
                            <td><span class="badge-status <?php echo $badge; ?>"><?php echo $display_status; ?></span></td>
                            <td style="font-weight:700; color:<?php echo $h['fine'] > 0 ? '#ef4444' : '#2d6a4f'; ?>">
                                Rs. <?php echo $h['fine']; ?>
                            </td>
                        </tr>
                    <?php 
                            }
                        } else {
                            echo "<tr><td colspan='5' style='text-align:center; padding:50px; color:#94a3b8;'>No borrowing history found for this student.</td></tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>

    </div>
</div>


</body>
</html>