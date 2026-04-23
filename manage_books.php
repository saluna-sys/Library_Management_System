<?php
	include "connection.php";
    include "admin_navbar.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Books | ShelfNova</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="admin.css?v=<?php echo time(); ?>">
    <style>
        /* --- ADMIN PAGE WRAPPER --- */
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
            gap: 10px;
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

        /* Book Column Info */
        .book-details { display: flex; align-items: center; gap: 15px; }
        .book-img-mini {
            width: 45px; height: 65px;
            object-fit: cover; border-radius: 4px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .book-text-info b { display: block; font-size: 14px; color: #1e293b; }
        .book-text-info span { font-size: 11px; color: #808847; font-weight: 700; }

        /* Status Badges */
        .badge {
            padding: 4px 10px; border-radius: 50px; font-size: 11px; font-weight: 700;
        }
        .bg-available { background: #f0fdf4; color: #16a34a; }
        .bg-unavailable { background: #fef2f2; color: #ef4444; }

        /* Action Icons */
        .act-icon { font-size: 16px; margin: 0 10px; transition: 0.3s; }
        .act-edit { color: #2d6a4f; }
        .act-del { color: #ef4444; }
        .act-icon:hover { transform: scale(1.2); }

        /* Trending Button Styling */
        .btn-trending {
            all: unset;
            display: inline-block;
            font-size: 10px;
            font-weight: 800;
            color: #808847;
            border: 1px solid #808847;
            padding: 4px 8px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 5px;
            transition: 0.3s;
        }
        .btn-trending:hover { background: #808847; color: white; }

        .page-title { color: #2d6a4f; font-weight: 800; margin-bottom: 25px; text-transform: uppercase; letter-spacing: 1px; font-size: 24px; }
    </style>
</head>
<body>

<div class="admin-page-wrapper">
    <div class="container-full">
        
        <!-- SEARCH BAR AREA -->
        <div class="admin-search-container">
            <form action="" method='post' style="display:flex; width:100%; gap:10px;">
                <input type="search" name='search' placeholder='Find book by title...' required>
                <button type='submit' name='submit'><i class="fas fa-search"></i></button>
            </form>
        </div>

        <h2 class="page-title">Library Collection</h2>

        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>Book Information</th>
                        <th>Author</th>
                        <th>Category</th>
                        <th>ISBN</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Status</th>
                        <th style="text-align:center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    // Logic: Building Query
                    $query_str = "SELECT books.*, authors.authorname, category.categoryname 
                                  FROM `books`
                                  JOIN `authors` ON authors.authorid=books.authorid 
                                  JOIN `category` ON category.categoryid=books.categoryid";

                    if(isset($_POST['submit'])) {
                        $search = mysqli_real_escape_string($db, $_POST['search']);
                        $query_str .= " WHERE bookname LIKE '%$search%'";
                    }

                    $res = mysqli_query($db, $query_str);

                    if(mysqli_num_rows($res) > 0) {
                        while($row = mysqli_fetch_assoc($res)) {
                ?>
                        <tr>
                            <td>
                                <div class="book-details">
                                    <img src="images/<?php echo $row['bookpic']; ?>" class="book-img-mini">
                                    <div class="book-text-info">
                                        <b><?php echo htmlspecialchars($row['bookname']); ?></b>
                                        <span>ID: #<?php echo $row['bookid']; ?></span>
                                        
                                    </div>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($row['authorname']); ?></td>
                            <td><?php echo htmlspecialchars($row['categoryname']); ?></td>
                            <td style="font-family: monospace;"><?php echo $row['ISBN']; ?></td>
                            <td style="font-weight:700; color:#2d6a4f;">Rs. <?php echo $row['price']; ?></td>
                            <td style="font-weight:600;"><?php echo $row['quantity']; ?></td>
                            <td>
    <?php 
        // We decide the status based on actual quantity
        if($row['quantity'] > 0) {
            $display_status = "Available";
            $badge_class = "bg-available";
        } else {
            $display_status = "Out of Stock";
            $badge_class = "bg-unavailable";
        }
    ?>
    <span class="badge <?php echo $badge_class; ?>">
        <?php echo $display_status; ?>
    </span>
</td>
                            <td style="text-align:center;">
                                <a href="edit_book.php?ed=<?php echo $row['bookid'];?>" title="Edit">
                                    <i class="fa-solid fa-pen-to-square act-icon act-edit"></i>
                                </a>
                                <a href="delete_book.php?del=<?php echo $row['bookid'];?>" 
                                   onclick="return confirm('Permanently delete this book?')" title="Delete">
                                    <i class="fa-solid fa-trash-can act-icon act-del"></i>
                                </a>
                            </td>
                        </tr>
                <?php 
                        }
                    } else {
                        echo "<tr><td colspan='8' style='text-align:center; padding:50px; color:#94a3b8;'>No books found in the collection.</td></tr>";
                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
    // TRENDING BOOK LOGIC (UNTOUCHED)
    if(isset($_GET['req']))
	{
		$id = mysqli_real_escape_string($db, $_GET['req']);
		mysqli_query($db,"INSERT INTO `TRENDINGBOOK` VALUES('$id');");
		echo "<script>alert('Added to Trending successfully.'); window.location='manage_books.php';</script>";
	}
?>

</body>
</html>