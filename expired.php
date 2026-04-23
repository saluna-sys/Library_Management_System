<?php
	include "connection.php";
    include "admin_navbar.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Expired Books | ShelfNova</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="admin.css?v=<?php echo time(); ?>">
    <style>
        /* --- ADMIN PAGE LAYOUT --- */
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
            padding: 18px 20px;
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 2px solid #f1f5f9;
        }

        td {
            padding: 15px 20px;
            border-bottom: 1px solid #f8fafc;
            font-size: 14px;
            color: #1e293b;
            vertical-align: middle;
        }

        tr:hover td { background-color: #fef2f2; } /* Light red hover for expired books */

        /* Meta Information Columns */
        .flex-meta { display: flex; align-items: center; gap: 12px; }
        
        .avatar-initial {
            width: 35px; height: 35px; border-radius: 50%;
            background: #ef4444; color: white; /* Red for expired */
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 14px;
        }

        .book-thumb {
            width: 35px; height: 50px; object-fit: cover;
            border-radius: 4px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .meta-text b { display: block; font-size: 14px; color: #1e293b; }
        .meta-text span { font-size: 11px; color: #64748b; font-weight: 600; }

        .fine-text { color: #dc2626; font-weight: 800; font-size: 15px; }

        .page-title { color: #2d6a4f; font-weight: 800; margin-bottom: 25px; text-transform: uppercase; letter-spacing: 1px; font-size: 24px; }
    </style>
</head>
<body>

<div class="admin-page-wrapper">
    <div class="container-full">
        
        <!-- SEARCH AREA -->
        <div class="admin-search-container">
            <form action="" method='post' style="display:flex; width:100%;">
                <input type="search" name='search' placeholder='Filter by Student ID...' required>
                <button type='submit' name='submit'><i class="fas fa-search"></i></button>
            </form>
        </div>

        <h2 class="page-title">Overdue & Expired Logs</h2>

        <div class="table-card">
            <?php
            $e = 0;
            // The exact variable from your old code
            $var = '<p style="color:yellow; background-color:red;">EXPIRED</p>';

            if(isset($_POST['submit'])) {
                $search_id = mysqli_real_escape_string($db, $_POST['search']);
                $q = mysqli_query($db,"SELECT student.studentid,FullName,studentpic,books.bookid,bookname,ISBN,price,bookpic,authors.authorname,category.categoryname,issueinfo.issuedate,returndate,approve,fine FROM student inner join issueinfo on student.studentid=issueinfo.studentid inner join books on issueinfo.bookid=books.bookid join authors on authors.authorid=books.authorid join category on category.categoryid=books.categoryid where issueinfo.approve='$var' AND issueinfo.studentid='$search_id' ORDER BY `issueinfo`.`returndate` ASC;");
            } else {
                $q = mysqli_query($db,"SELECT student.studentid,FullName,studentpic,books.bookid,bookname,ISBN,price,bookpic,authors.authorname,category.categoryname,issueinfo.issuedate,returndate,approve,fine FROM student inner join issueinfo on student.studentid=issueinfo.studentid inner join books on issueinfo.bookid=books.bookid join authors on authors.authorid=books.authorid join category on category.categoryid=books.categoryid where issueinfo.approve='$var' ORDER BY `issueinfo`.`returndate` ASC;");
            }

            if(mysqli_num_rows($q) > 0) {
            ?>
            <table>
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Book Information</th>
                        <th>Issue Date</th>
                        <th>Return Date</th>
                        <th>Approve Status</th>
                        <th>Fine Amount</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                while($row = mysqli_fetch_assoc($q)) {
                    // YOUR ORIGINAL PHP: Update logic for fines
                    $d = strtotime($row['returndate']);
                    $c = strtotime(date("Y-m-d"));
                    $diff = $c-$d;
                    if($diff > 0){
                        $day = floor($diff/(60*60*24));
                        $e = $e + 1;
                        $fine = $day * 10;
                        mysqli_query($db,"UPDATE issueinfo SET approve='$var', fine=$fine where `returndate`='$row[returndate]' and approve='yes' limit $e;");
                    }
                    
                    $initial = strtoupper(substr($row['FullName'], 0, 1));
                ?>
                    <tr>
                        <td>
                            <div class="flex-meta">
                                <div class="avatar-initial"><?php echo $initial; ?></div>
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
                        <td style="color:#64748b; font-size:13px;"><?php echo date('d M Y', strtotime($row['issuedate'])); ?></td>
                        <td style="color:#ef4444; font-weight:700; font-size:13px;"><?php echo date('d M Y', strtotime($row['returndate'])); ?></td>
                        <td><?php echo $row['approve']; ?></td>
                        <td class="fine-text">Rs. <?php echo $row['fine']; ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <?php
            } else {
                echo "<div style='text-align:center; padding:80px; color:#94a3b8;'>
                        <i class='fa-solid fa-circle-exclamation' style='font-size:40px; margin-bottom:15px; opacity:0.3;'></i><br>
                        No expired books found in the system.
                      </div>";
            }
            ?>
        </div>
    </div>
</div>

</body>
</html>