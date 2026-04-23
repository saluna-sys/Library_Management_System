<?php
	include "connection.php";
    include "admin_navbar.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Requests | ShelfNova</title>
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
            max-width: 1400px; /* ALIGNED WITH NAVBAR */
            margin: 0 auto;
            padding: 0 30px;
        }

        /* --- MODERN SEARCH BAR --- */
        .admin-search-container {
            width: 100%;
            max-width: 500px;
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
            padding: 0 20px;
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

        /* Complex Column Styles */
        .flex-cell { display: flex; align-items: center; gap: 12px; }
        
        .avatar-circle {
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

        /* Action Button */
        .btn-issue-action {
            background-color: #808847;
            color: white !important;
            padding: 8px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 700;
            font-size: 11px;
            text-transform: uppercase;
            transition: 0.3s;
            display: inline-block;
        }
        .btn-issue-action:hover { background-color: #2d6a4f; transform: translateY(-1px); }

        .page-title { color: #2d6a4f; font-weight: 800; margin-bottom: 25px; text-transform: uppercase; letter-spacing: 1px; font-size: 22px; }
    </style>
</head>
<body>

<div class="admin-page-wrapper">
    <div class="container-full">
        
        <!-- SEARCH BAR AREA -->
        <div class="admin-search-container">
            <form action="" method='post' style="display:flex; width:100%;">
                <input type="search" name='search' placeholder='Search by Student ID...' required>
                <button type='submit' name='submit'><i class="fas fa-search"></i></button>
            </form>
        </div>

        <h2 class="page-title">Pending Book Requests</h2>

        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Book Requested</th>
                        <th>Author / Category</th>
                        <th>ISBN</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                   <?php
// BUILDING THE FIXED QUERY
// 1. We check for 'no' OR 'pending' to be 100% sure we catch them
$condition = "WHERE (issueinfo.approve='pending' OR issueinfo.approve='no')";

if(isset($_POST['submit'])) {
    $s_id = mysqli_real_escape_string($db, $_POST['search']);
    $condition .= " AND issueinfo.studentid='$s_id'";
}

// 2. We use LEFT JOIN so if a book is missing an author/category, it STILL shows up
$q_str = "SELECT student.studentid, student.FullName, student.studentpic, 
                 books.bookid, books.bookname, books.ISBN, books.price, books.bookpic, 
                 authors.authorname, category.categoryname,
                 issueinfo.approve, issueinfo.issuedate, issueinfo.fine
          FROM issueinfo 
          INNER JOIN student ON student.studentid = issueinfo.studentid 
          INNER JOIN books ON issueinfo.bookid = books.bookid 
          LEFT JOIN authors ON authors.authorid = books.authorid 
          LEFT JOIN category ON category.categoryid = books.categoryid 
          $condition
          ORDER BY issueinfo.id DESC";

$res = mysqli_query($db, $q_str);

if (!$res) {
    echo "<p style='color:red; padding:20px;'>SQL Error: " . mysqli_error($db) . "</p>";
}

if(mysqli_num_rows($res) > 0) {
    while($row = mysqli_fetch_assoc($res)) {
        // ... (The rest of your while loop code)
?>
    <tr>
        <td>
            <div class="flex-cell">
                <div class="avatar-circle"><?php echo strtoupper(substr($row['FullName'], 0, 1)); ?></div>
                <div class="meta-text">
                    <b><?php echo htmlspecialchars($row['FullName']); ?></b>
                    <span>ID: #<?php echo $row['studentid']; ?></span>
                </div>
            </div>
        </td>
        <td>
            <div class="flex-cell">
                <img src="images/<?php echo $row['bookpic']; ?>" class="book-thumb" onerror="this.src='images/book_default.png'">
                <div class="meta-text">
                    <b><?php echo htmlspecialchars($row['bookname']); ?></b>
                    <span>BID: #<?php echo $row['bookid']; ?></span>
                </div>
            </div>
        </td>
        <td>
            <div class="meta-text">
                <b style="font-weight:600;"><?php echo htmlspecialchars($row['authorname'] ?? 'N/A'); ?></b>
                <span style="color:#94a3b8;"><?php echo htmlspecialchars($row['categoryname'] ?? 'General'); ?></span>
            </div>
        </td>
        <td style="font-family: monospace;"><?php echo $row['ISBN']; ?></td>
        <td style="font-weight:700; color:#2d6a4f;">Rs. <?php echo $row['price']; ?></td>
        <td>
            <a href="issue_book.php?ed=<?php echo $row['studentid'];?>&ed1=<?php echo $row['bookid'];?>" class="btn-issue-action">
                ISSUE BOOK
            </a>
        </td>
    </tr>
<?php 
    }
} else {
    echo "<tr><td colspan='6' style='text-align:center; padding:60px; color:#94a3b8;'>No pending book requests found.</td></tr>";
}
?>
                </tbody>
            </table>
        </div>
    </div>
</div>


</body>
</html>