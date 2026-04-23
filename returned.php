<?php
include "connection.php";
include "admin_navbar.php"; // Links to admin.css
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Returned History | ShelfNova</title>
    <link rel="stylesheet" href="admin.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    
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

        /* --- HEADER SECTION --- */
        .header-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .page-title { color: #2d6a4f; font-weight: 800; margin: 0; text-transform: uppercase; letter-spacing: 1px; font-size: 24px; }

        .clear-btn-modern {
            all: unset;
            padding: 10px 20px;
            background-color: #fef2f2;
            color: #dc2626;
            border: 1.5px solid #fee2e2;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            text-transform: uppercase;
            transition: 0.3s;
        }
        .clear-btn-modern:hover { background-color: #dc2626; color: white; }

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
            padding: 18px 20px;
            border-bottom: 1px solid #f8fafc;
            font-size: 14px;
            color: #1e293b;
            vertical-align: middle;
        }

        tr:hover td { background-color: #f9fbf9; }

        /* Student & Book Meta */
        .flex-meta { display: flex; align-items: center; gap: 12px; }
        .avatar-initial {
            width: 35px; height: 35px; border-radius: 50%;
            background: #2d6a4f; color: white;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 14px;
        }
        .book-thumb {
            width: 35px; height: 50px; object-fit: cover;
            border-radius: 4px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .meta-text b { display: block; font-size: 14px; color: #1e293b; }
        .meta-text span { font-size: 11px; color: #808847; font-weight: 700; }

        .status-badge {
            background: #f0fdf4; color: #166534;
            padding: 4px 12px; border-radius: 50px;
            font-size: 11px; font-weight: 800;
            text-transform: uppercase;
        }

        .date-text { font-size: 13px; color: #64748b; font-weight: 500; }
    </style>
</head>
<body>

<div class="admin-page-wrapper">
    <div class="container-full">
        
        <!-- 🔍 SEARCH AREA -->
        <div class="admin-search-container">
            <form action="" method='post' style="display:flex; width:100%;">
                <input type="search" name='search' placeholder='Filter by Student ID...' required>
                <button type='submit' name='submit'><i class="fas fa-search"></i></button>
            </form>
        </div>

        <!-- 🏷️ HEADER -->
        <div class="header-flex">
            <h2 class="page-title">Returned Books History</h2>
            <form method="post" onsubmit="return confirm('Permanently delete all returned book history?')">
                <button type="submit" name="clear" class="clear-btn-modern">
                    <i class="fa-solid fa-trash-can"></i> Clear History
                </button>
            </form>
        </div>

        <div class="table-card">
        <?php
        // 🧹 CLEAR ALL RETURNED
        if(isset($_POST['clear'])) {
            mysqli_query($db,"DELETE FROM issueinfo WHERE approve='returned'");
            echo "<script>alert('History cleared successfully'); window.location='returned.php';</script>";
        }

        // Define the Base Query
        $q_base = "SELECT student.studentid, FullName, studentpic, books.bookid, bookname, bookpic,
                          issueinfo.issuedate, issueinfo.returndate, issueinfo.approve, issueinfo.fine
                   FROM student 
                   INNER JOIN issueinfo ON student.studentid = issueinfo.studentid 
                   INNER JOIN books ON issueinfo.bookid = books.bookid 
                   WHERE issueinfo.approve = 'returned'";

        if(isset($_POST['submit'])) {
            $sid = mysqli_real_escape_string($db, $_POST['search']);
            $q_base .= " AND issueinfo.studentid = '$sid'";
        }
        
        $q_base .= " ORDER BY issueinfo.returndate DESC";
        $result = mysqli_query($db, $q_base);

        if(mysqli_num_rows($result) == 0) {
            echo "<div style='text-align:center; padding:80px; color:#94a3b8;'>
                    <i class='fa-solid fa-clock-rotate-left' style='font-size:40px; margin-bottom:15px; opacity:0.3;'></i><br>
                    No returned book records found.
                  </div>";
        } else {
            displayTable($result);
        }

        // ✅ TABLE FUNCTION (Updated for ShelfNova UI)
        function displayTable($data) {
            echo "<table>";
            echo "<thead>
                    <tr>
                        <th>Student</th>
                        <th>Book Returned</th>
                        <th>Issue Date</th>
                        <th>Return Date</th>
                        <th>Status</th>
                        <th>Total Fine</th>
                    </tr>
                  </thead><tbody>";

            while($row = mysqli_fetch_assoc($data)) {
                $initial = strtoupper(substr($row['FullName'], 0, 1));
                echo "<tr>";

                // Student Identity
                echo "<td>
                        <div class='flex-meta'>
                            <div class='avatar-initial'>$initial</div>
                            <div class='meta-text'>
                                <b>".htmlspecialchars($row['FullName'])."</b>
                                <span>ID: #".$row['studentid']."</span>
                            </div>
                        </div>
                      </td>";

                // Book Details
                echo "<td>
                        <div class='flex-meta'>
                            <img src='images/".$row['bookpic']."' class='book-thumb'>
                            <div class='meta-text'>
                                <b>".htmlspecialchars($row['bookname'])."</b>
                                <span>BID: #".$row['bookid']."</span>
                            </div>
                        </div>
                      </td>";

                echo "<td class='date-text'>".date('d M Y', strtotime($row['issuedate']))."</td>";
                echo "<td class='date-text' style='font-weight:700; color:#1e293b;'>".date('d M Y', strtotime($row['returndate']))."</td>";
                echo "<td><span class='status-badge'>Returned</span></td>";
                echo "<td style='font-weight:700; color:#2d6a4f;'>Rs. ".$row['fine']."</td>";

                echo "</tr>";
            }
            echo "</tbody></table>";
        }
        ?>
        </div>
    </div>
</div>

</body>
</html>