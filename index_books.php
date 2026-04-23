<?php
	include "connection.php";
	include "index_navbar.php";
    $category_res = mysqli_query($db,"SELECT * FROM category");
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Library Catalog | ShelfNova</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <style>
        /* --- FIX: ADDED MORE TOP PADDING TO PREVENT NAV OVERLAP --- */
        .books-page { 
            padding: 120px 0 60px; /* 120px top padding ensures search bar is visible */
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

        /* --- MODERN CENTERED SEARCH --- */
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
        .search-btn:hover { background: #808847; }

        /* --- THE GRID --- */
        .books-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(190px, 1fr)); 
            gap: 25px;
            justify-content: center;
        }

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

        .img-box {
            position: relative;
            height: 320px; 
            width: 100%;
            overflow: hidden;
            background: #f0f0f0;
        }
        .img-box img { width: 100%; height: 100%; object-fit: cover; }

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
        .details-inner p { font-size: 11px; margin-bottom: 12px; line-height: 1.6; }
        .details-inner b { color: #d4d8b5; font-weight: 600; display: block; margin-bottom: 2px; }

        .login-req-btn {
            background: #808847; 
            color: white !important; 
            display: inline-block;
            width: 85%; 
            padding: 8px 0;
            text-decoration: none; 
            border-radius: 4px; 
            font-weight: 700; 
            font-size: 10px; 
            margin-top: 5px;
            text-transform: uppercase;
        }

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
        .qty-text { color: #94a3b8; font-size: 10px; font-weight: 600; }
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
            <input type="search" name="search" placeholder="Search by book name, author, ISBN...">
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
            <?php while($row = mysqli_fetch_assoc($res)) { ?>
                <div class="book-card">
                    <div class="img-box">
                        <img src="images/<?php echo $row['bookpic']; ?>" alt="book">
                        <div class="details-overlay">
                            <div class="details-inner">
                                <p><b>Author</b> <?php echo $row['authorname']; ?></p>
                                <p><b>ISBN</b> <?php echo $row['ISBN']; ?></p>
                                <p><b>Status</b> <?php echo $row['status']; ?></p>
                                <a href="student.php" class="login-req-btn">LOGIN TO REQUEST</a>
                            </div>
                        </div>
                    </div>
                    <div class="info-bottom">
                        <h4><?php echo $row['bookname']; ?></h4>
                        <div class="price-row">
                            <div class="price-text">Rs. <?php echo $row['price']; ?></div>
                            <div class="qty-text">Qty: <?php echo $row['quantity']; ?></div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>

</body>
</html>