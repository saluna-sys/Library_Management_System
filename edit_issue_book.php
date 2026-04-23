<?php
	include "connection.php";
    include "admin_navbar.php";
    $res1=mysqli_query($db,"SELECT * FROM authors");
	$res2=mysqli_query($db,"SELECT * FROM category");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Issuance | ShelfNova</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="admin.css?v=<?php echo time(); ?>">
    
    <style>
        /* --- SHELFNOVA EDIT ISSUANCE UI --- */
        .edit-wrapper {
            padding: 60px 20px;
            background-color: #f4f7f5;
            min-height: 90vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .edit-card {
            background: white;
            width: 100%;
            max-width: 800px; /* Wider for 2-column layout */
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.05);
            overflow: hidden;
            display: flex;
        }

        /* Left side summary */
        .info-summary {
            flex: 1;
            background-color: #fcfdfc;
            padding: 40px;
            border-right: 1px solid #f1f5f9;
        }

        /* Right side form */
        .form-area {
            flex: 1.2;
            padding: 40px;
        }

        .section-tag {
            font-size: 11px;
            font-weight: 800;
            color: #808847;
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

        .avatar-circle {
            width: 45px; height: 45px; border-radius: 50%; object-fit: cover; border: 2px solid #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .book-thumb {
            width: 40px; height: 55px; border-radius: 4px; object-fit: cover; box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .item-text b { display: block; font-size: 14px; color: #1e293b; }
        .item-text span { font-size: 11px; color: #64748b; }

        /* Form Styling */
        .sn-field { margin-bottom: 20px; text-align: left; }
        .sn-field label { display: block; font-size: 12px; font-weight: 700; color: #2d6a4f; margin-bottom: 8px; }

        .sn-input {
            all: unset !important;
            display: block !important;
            box-sizing: border-box !important;
            width: 100% !important;
            height: 45px !important;
            background-color: #f8fafc !important;
            border: 1.5px solid #e2e8f0 !important;
            border-radius: 10px !important;
            padding: 0 15px !important;
            font-family: 'Poppins', sans-serif !important;
            font-size: 13px !important;
            color: #334155 !important;
            transition: 0.3s ease !important;
        }

        .sn-input:focus {
            border-color: #2d6a4f !important;
            background-color: #ffffff !important;
            box-shadow: 0 0 0 4px rgba(45, 106, 79, 0.1) !important;
        }

        .btn-update-issue {
            all: unset !important;
            display: block !important;
            width: 100% !important;
            box-sizing: border-box !important;
            background-color: #2d6a4f !important;
            color: white !important;
            text-align: center !important;
            padding: 14px !important;
            border-radius: 10px !important;
            font-weight: 700 !important;
            cursor: pointer !important;
            margin-top: 20px !important;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-update-issue:hover { background-color: #1b4332 !important; transform: translateY(-2px); }
    </style>
</head>
<body>

    <div class="edit-wrapper">
        <div class="edit-card">
            
            <?php
                // ORIGINAL DATA FETCHING LOGIC
                $studentid_get = mysqli_real_escape_string($db, $_GET['ed']);
                $bookid_get = mysqli_real_escape_string($db, $_GET['ed1']);

                $var_expired_html = '<p style="color:yellow; background-color:red;">EXPIRED</p>';
                
                $q = "SELECT student.studentid, FullName, studentpic, issueinfo.bookid, books.bookname, ISBN, price, bookpic, issuedate, returndate, approve, fine, authors.authorname, category.categoryname 
                      FROM issueinfo 
                      INNER JOIN student ON issueinfo.studentid=student.studentid 
                      INNER JOIN books ON issueinfo.bookid=books.bookid 
                      JOIN authors ON authors.authorid=books.authorid 
                      JOIN category ON category.categoryid=books.categoryid 
                      WHERE student.studentid=$studentid_get AND (approve='yes' OR approve='approved' OR approve='$var_expired_html') AND issueinfo.bookid=$bookid_get";
                
                $res = mysqli_query($db, $q) or die(mysqli_error($db));
                $row = mysqli_fetch_assoc($res);

                $t = mysqli_query($db, "SELECT * FROM timer WHERE stdid='$studentid_get' AND bid='$bookid_get';");
                $row2 = mysqli_fetch_assoc($t);
                
                // Assigning variables for display
                $FullName = $row['FullName'];
                $studentpic = $row['studentpic'];
                $bookname = $row['bookname'];
                $bookpic = $row['bookpic'];
                $ISBN = $row['ISBN'];
                $authorname = $row['authorname'];
                $categoryname = $row['categoryname'];
                $issuedate = $row['issuedate'];
                $returndate = $row['returndate'];
                $fine = $row['fine'];
                $tm = $row2['date'];
            ?>

            <!-- LEFT SIDE: SUMMARY -->
            <div class="info-summary">
                <span class="section-tag">Student Details</span>
                <div class="item-row">
                    <div class="item-text">
                        <b><?php echo $FullName; ?></b>
                        <span>Student ID: #<?php echo $studentid_get; ?></span>
                    </div>
                </div>

                <span class="section-tag" style="margin-top:40px;">Book Details</span>
                <div class="item-row">
                    <img src="images/<?php echo $bookpic; ?>" class="book-thumb">
                    <div class="item-text">
                        <b><?php echo $bookname; ?></b>
                        <span>ISBN: <?php echo $ISBN; ?></span>
                    </div>
                </div>

                <div style="margin-top:30px; padding:15px; background:#f8fafc; border-radius:10px; font-size:12px; color:#64748b;">
                    <p><b>Author:</b> <?php echo $authorname; ?></p>
                    <p><b>Category:</b> <?php echo $categoryname; ?></p>
                    <p><b>Issue Date:</b> <?php echo $issuedate; ?></p>
                </div>
            </div>

            <!-- RIGHT SIDE: EDIT FORM -->
            <div class="form-area">
                <h2 style="color:#2d6a4f; margin-bottom:25px;">Update Issuance</h2>

                <form action="" method="post">
                    
                    <div class="sn-field">
                        <label>Penalty Fine (Rs.)</label>
                        <input type="text" name="fine" class="sn-input" value="<?php echo $fine; ?>">
                    </div>

                    <div class="sn-field">
                        <label>Simple Return Date</label>
                        <input type="date" name="returndate" class="sn-input" value="<?php echo $returndate; ?>">
                    </div>

                    <div class="sn-field">
                        <label>Countdown End Time</label>
                        <?php $formattedDateTime = date('Y-m-d\TH:i:s', strtotime($tm)); ?>
                        <input type="datetime-local" name="returndatetime" class="sn-input" value="<?php echo $formattedDateTime; ?>">
                    </div>

                    <button type="submit" class="btn-update-issue" name="submit">Save Updates</button>
                    
                    <p style="text-align:center; margin-top:20px;">
                        <a href="manage_issued_books.php" style="color:#94a3b8; font-size:12px; text-decoration:none;">Cancel and return</a>
                    </p>
                </form>
            </div>
        </div>
    </div>

    <?php
        // ORIGINAL PHP UPDATE LOGIC (UNTOUCHED)
        if(isset($_POST['submit']))
        {
            $new_fine = mysqli_real_escape_string($db, $_POST['fine']);
            $new_returndate = mysqli_real_escape_string($db, $_POST['returndate']);
            
            $dbInsertDate = date('Y-m-d H:i:s', strtotime($_POST['returndatetime']));
            
            // Updating Timer Table
            $q_timer = "UPDATE timer SET date='$dbInsertDate' WHERE bid=$bookid_get AND stdid=$studentid_get;";
            
            // Updating IssueInfo Table
            // Using same logic: checking for approve='yes' as per your original file
            $q_issue = "UPDATE issueinfo SET returndate='$new_returndate', fine='$new_fine' WHERE (approve='yes' OR approve='approved') AND bookid=$bookid_get AND studentid=$studentid_get;";
            
            if(mysqli_query($db, $q_issue) && mysqli_query($db, $q_timer))
            {
                echo "<script>alert('Issuance records updated successfully.'); window.location='manage_issued_books.php';</script>";
            }					
        }
	?>

</body>
</html>