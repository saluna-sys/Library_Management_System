<?php
session_start();
include "connection.php";
include "student_navbar.php";

if(!isset($_SESSION['login_student_username'])) {
    echo "<script>window.location='student.php';</script>";
    exit();
}

$category_res = mysqli_query($db, "SELECT * FROM category");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Library Catalog | ShelfNova</title>
    <link rel="stylesheet" href="student_auth.css?v=<?php echo time(); ?>">
    <style>
        /* --- CENTERED PAGE LAYOUT --- */
        .books-page { 
            padding: 40px 0; 
            background-color: #f4f7f5; 
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center; 
        }

        .main-content-area {
            width: 100%;
            max-width: 1200px;
            padding: 0 20px;
        }

        /* --- SEARCH BAR --- */
        .search-container { 
            width: 100%;
            max-width: 800px; 
            margin: 0 auto 60px; 
            display: flex; 
            gap: 10px;
        }
        .search-container select, .search-container input { 
            padding: 12px 20px; border-radius: 8px; border: 1.5px solid #e2e8f0; outline: none; font-family: inherit; 
        }
        .search-container select { flex: 1; background: #fff; font-weight: 600; color: #2d6a4f; }
        .search-container input { flex: 3; }
        .search-btn { 
            background: #2d6a4f; color: white; border: none; padding: 0 25px; border-radius: 8px; cursor: pointer; transition: 0.3s;
        }

        /* --- THE GRID --- */
        .books-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(190px, 1fr)); 
            gap: 25px;
            justify-content: center;
        }

        /* --- THE CARD --- */
        .book-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            height: 380px; 
            transition: transform 0.3s ease;
        }

        .book-card:hover { transform: translateY(-8px); }

        /* --- TALL IMAGE AREA --- */
        .img-box {
            position: relative;
            height: 320px; 
            width: 100%;
            overflow: hidden;
            background: #f0f0f0;
        }

        .img-box img {
            width: 100%;
            height: 100%;
            object-fit: cover; 
        }

        /* --- PERFECTLY CENTERED HOVER OVERLAY --- */
        .details-overlay {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(45, 106, 79, 0.95); 
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: 0.4s ease;
            z-index: 10;
        }

        .img-box:hover .details-overlay { opacity: 1; }

        .details-inner {
            width: 85%;
            padding: 10px 0;
            border-left: 1.5px solid rgba(255,255,255,0.3);  
            border-right: 1.5px solid rgba(255,255,255,0.3); 
            text-align: center;
        }

        .details-inner p { 
            font-size: 11px; 
            margin-bottom: 12px;
            line-height: 1.6;
        }

        .details-inner b { color: #d4d8b5; font-weight: 600; display: block; margin-bottom: 2px; }

        .request-link {
            background: #808847; 
            color: white !important; 
            display: inline-block;
            width: 80%; 
            padding: 8px 0;
            text-decoration: none; 
            border-radius: 4px; 
            font-weight: 700; 
            font-size: 10px; 
            margin-top: 10px;
            text-transform: uppercase;
        }

        /* --- COMPACT BOTTOM INFO AREA --- */
        .info-bottom {
            padding: 0 12px;
            background: #fff;
            height: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .info-bottom h4 {
            margin: 0;
            font-size: 13px;
            color: #1e293b;
            font-weight: 700;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 5px;
        }

        .price-text { color: #2d6a4f; font-weight: 800; font-size: 13px; }
        .qty-text { font-size: 10px; font-weight: 600; }
    </style>
</head>
<body>

<div class="books-page">
    <div class="main-content-area">
        
        <!-- SEARCH -->
        <form action="" method="post" class="search-container">
            <select name="category">
                <option value="selectcat">All Categories</option>
                <?php while($row=mysqli_fetch_array($category_res)) echo "<option value='".$row[0]."'>".$row[1]."</option>"; ?>
            </select>
            <input type="search" name="search" placeholder="Search by name, author, ISBN...">
            <button type="submit" name="submit" class="search-btn"><i class="fas fa-search"></i></button>
        </form>

        <?php
        $query = "SELECT books.*, category.categoryname, authors.authorname FROM `books` 
                  JOIN `category` ON category.categoryid=books.categoryid 
                  JOIN `authors` ON authors.authorid=books.authorid";

        if(isset($_POST['submit'])) {
            $s = mysqli_real_escape_string($db, $_POST['search']);
            $c = mysqli_real_escape_string($db, $_POST['category']);
            $query .= " WHERE bookname LIKE '%$s%'";
            if($c != "selectcat") $query .= " AND books.categoryid = '$c'";
        }
        $res = mysqli_query($db, $query);
        ?>

        <div class="books-grid">
            <?php while($row = mysqli_fetch_assoc($res)) { 
                // CRUCIAL LOGIC: Check quantity and status
                $is_out_of_stock = ($row['quantity'] <= 0 || $row['status'] != 'Available');
            ?>
                <div class="book-card" style="<?php echo $is_out_of_stock ? 'opacity: 0.6;' : ''; ?>">
                    <div class="img-box">
                        <img src="images/<?php echo $row['bookpic']; ?>" alt="book">
                        
                        <div class="details-overlay">
                            <div class="details-inner">
                                <p><b>Author</b> <?php echo $row['authorname']; ?></p>
                                <p><b>ISBN</b> <?php echo $row['ISBN']; ?></p>
                                <p><b>Status</b> <?php echo $is_out_of_stock ? 'Out of Stock' : 'Available'; ?></p>
                                
                                <?php if(!$is_out_of_stock): ?>
                                    <a href="request_book.php?req=<?php echo $row['bookid']; ?>" class="request-link">REQUEST BOOK</a>
                                <?php else: ?>
                                    <span class="request-link" style="background:#ef4444; cursor:not-allowed;">SOLD OUT</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="info-bottom">
                        <h4><?php echo $row['bookname']; ?></h4>
                        <div class="price-row">
                            <div class="price-text">Rs. <?php echo $row['price']; ?></div>
                            <div class="qty-text" style="color: <?php echo ($row['quantity'] <= 0) ? '#ef4444' : '#94a3b8'; ?>">
                                <?php echo ($row['quantity'] <= 0) ? 'OUT OF STOCK' : 'Qty: '.$row['quantity']; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

</body>
</html>