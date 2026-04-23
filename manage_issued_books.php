<?php
session_start();

// 1. ADMIN SESSION CHECK
if(!isset($_SESSION['login_admin_username'])) {
    echo "<script>alert('Please login as admin first'); window.location='admin.php';</script>";
    exit();
}

include "connection.php";
include "admin_navbar.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Issued Books Management | ShelfNova</title>
    <!-- CSS and Fonts -->
    <link rel="stylesheet" href="admin.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        .admin-page-wrapper { 
            padding: 60px 0; 
            background-color: #f4f7f5; 
            min-height: 100vh;
        }

        .container-full {
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 30px;
        }

        /* --- MODERN SEARCH BAR --- */
        .admin-search-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto 50px;
            display: flex;
            background: white;
            padding: 12px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        }

        .admin-search-container input {
            flex: 1;
            padding: 10px 15px;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            outline: none;
            font-family: inherit;
        }

        .admin-search-container button {
            background-color: #2d6a4f;
            color: white;
            border: none;
            padding: 0 25px;
            margin-left: 10px;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }

        /* --- MINIMALIST GHOST TABLE --- */
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
            padding: 18px 15px;
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 2px solid #f1f5f9;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #f8fafc;
            font-size: 14px;
            color: #1e293b;
            vertical-align: middle;
        }

        tr:hover td { background-color: #f9fbf9; }

        /* Meta Columns (Student & Book) */
        .flex-meta { display: flex; align-items: center; gap: 12px; }
        
        .avatar-circle {
            width: 35px; height: 35px; border-radius: 50%;
            background: #2d6a4f; color: white;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 14px; flex-shrink: 0;
        }

        .book-thumb {
            width: 35px; height: 50px; object-fit: cover;
            border-radius: 4px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .meta-text b { display: block; font-size: 14px; color: #1e293b; }
        .meta-text span { font-size: 11px; color: #808847; font-weight: 700; }

        /* Modern Badges */
        .status-pill {
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 800;
            display: inline-block;
            text-transform: uppercase;
        }
        .st-issued { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
        .st-expired { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

        /* Action Buttons */
        .btn-act {
            all: unset;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.3s;
            display: inline-block;
            text-transform: uppercase;
        }
        .btn-edit { color: #2d6a4f; border: 1px solid #2d6a4f; margin-right: 5px; }
        .btn-edit:hover { background: #2d6a4f; color: #fff; }
        
        .btn-return { background: #808847; color: #fff; }
        .btn-return:hover { background: #2d6a4f; transform: translateY(-1px); }

        .page-title { color: #2d6a4f; font-weight: 800; margin-bottom: 25px; text-transform: uppercase; letter-spacing: 1px; font-size: 24px; }
    </style>
</head>
<body>

<div class="admin-page-wrapper">
    <div class="container-full">
        
        <!-- SEARCH AREA -->
        <div class="admin-search-container">
            <form action="" method='post' style="display:flex; width:100%;">
                <input type="search" name='search' placeholder='Search by Student ID...' required>
                <button type='submit' name='submit'><i class="fas fa-search"></i></button>
            </form>
        </div>

        <h2 class="page-title">Current Issued Books</h2>

        <div class="table-card">
            <?php
            // Logic for building query
            $condition = "WHERE issueinfo.approve = 'yes' OR issueinfo.approve = 'approved' OR issueinfo.approve = 'expired'";
            
            if(isset($_POST['submit'])) {
                $search_id = mysqli_real_escape_string($db, $_POST['search']);
                $condition .= " AND issueinfo.studentid = '$search_id'";
            }

            $q_str = "SELECT student.studentid, FullName, studentpic, books.bookid, bookname, ISBN, price, bookpic,
                             authors.authorname, category.categoryname, issueinfo.issuedate, returndate, approve, fine 
                      FROM student 
                      INNER JOIN issueinfo ON student.studentid = issueinfo.studentid 
                      INNER JOIN books ON issueinfo.bookid = books.bookid 
                      JOIN authors ON authors.authorid = books.authorid 
                      JOIN category ON category.categoryid = books.categoryid 
                      $condition 
                      ORDER BY issueinfo.returndate ASC";

            $res = mysqli_query($db, $q_str);

            if(mysqli_num_rows($res) > 0) {
                ?>
                <table>
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Book Details</th>
                            <th>Dates</th>
                            <th>Status</th>
                            <th>Fine</th>
                            <th style="text-align:center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    while($row = mysqli_fetch_assoc($res)) {
                        // AUTOMATIC FINE CALCULATION LOGIC
                        $return_date = strtotime($row['returndate']);
                        $current_date = strtotime(date("Y-m-d"));
                        $diff = $current_date - $return_date;
                        
                        if($diff > 0 && ($row['approve'] == 'approved' || $row['approve'] == 'yes')) {
                            $day = floor($diff/(60*60*24));
                            $fine = $day * 10;
                            mysqli_query($db, "UPDATE issueinfo SET approve = 'expired', fine = $fine 
                                              WHERE studentid = '{$row['studentid']}' 
                                              AND bookid = '{$row['bookid']}'");
                            $row['approve'] = 'expired';
                            $row['fine'] = $fine;
                        }
                        
                        $is_expired = ($row['approve'] == 'expired');
                        $initial = strtoupper(substr($row['FullName'], 0, 1));
                    ?>
                        <tr>
                            <td>
                                <div class="flex-meta">
                                    <div class="avatar-circle"><?php echo $initial; ?></div>
                                    <div class="meta-text">
                                        <b><?php echo htmlspecialchars($row['FullName']); ?></b>
                                        <span>ID: #<?php echo $row['studentid']; ?></span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="flex-meta">
                                    <img src="images/<?php echo $row['bookpic']; ?>" class="book-thumb">
                                    <div class="meta-text">
                                        <b><?php echo htmlspecialchars($row['bookname']); ?></b>
                                        <span>ISBN: <?php echo $row['ISBN']; ?></span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="meta-text">
                                    <b>Issued: <?php echo date('d M', strtotime($row['issuedate'])); ?></b>
                                    <span style="color:<?php echo $is_expired ? '#ef4444':'#64748b';?>">
                                        Return: <?php echo date('d M Y', strtotime($row['returndate'])); ?>
                                    </span>
                                </div>
                            </td>
                            <td>
                                <span class="status-pill <?php echo $is_expired ? 'st-expired' : 'st-issued'; ?>">
                                    <?php echo $is_expired ? 'Overdue' : 'Issued'; ?>
                                </span>
                            </td>
                            <td style="font-weight:700; color:<?php echo $row['fine'] > 0 ? '#dc2626' : '#2d6a4f'; ?>">
                                Rs. <?php echo $row['fine']; ?>
                            </td>
                            <td style="text-align:center;">
                                <a href='edit_issue_book.php?ed=<?php echo $row['studentid']; ?>&ed1=<?php echo $row['bookid']; ?>' class="btn-act btn-edit">Edit</a>
                                
                                <?php if(!$is_expired): ?>
                                    <a href='return_book.php?ed=<?php echo $row['studentid']; ?>&ed1=<?php echo $row['bookid']; ?>' 
                                       onclick='return confirm("Process book return?")' class="btn-act btn-return">Return</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <?php
            } else {
                echo "<div style='text-align:center; padding:80px; color:#94a3b8;'>
                        <i class='fa-solid fa-folder-open' style='font-size:40px; margin-bottom:15px; opacity:0.3;'></i><br>
                        No issued books found in the current logs.
                      </div>";
            }
            ?>
        </div>
    </div>
</div>


</body>
</html>