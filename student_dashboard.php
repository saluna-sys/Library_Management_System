<?php
session_start();
if(!isset($_SESSION['login_student_username'])) {
    echo "<script>window.location='student.php';</script>";
    exit();
}
include "connection.php";
include "student_navbar.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | ShelfNova</title>
    <link rel="stylesheet" href="student_auth.css?v=<?php echo time(); ?>">
    
    <style>
        .dash-outer-container {
            padding: 40px 0;
            background-color: #f4f7f5;
            min-height: 100vh;
        }

        /* MASTER CONTAINER - Aligns everything with the navbar */
        .dash-content {
            width: 100%;
            max-width: 1400px; /* MATCH THIS TO YOUR NAVBAR WIDTH */
            margin: 0 auto;
            padding: 0 30px;
        }

        /* 1. WELCOME BANNER */
        .welcome-section {
            width: 93%;
            background: linear-gradient(135deg, #2d6a4f 0%, #1b4332 100%);
            border-radius: 20px;
            padding: 45px 50px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(45, 106, 79, 0.15);
        }

        /* 2. THREE MIDDLE BOXES (STATS) */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr); /* FORCES 3 EQUAL COLUMNS */
            gap: 25px;
            margin-bottom: 30px;
            width: 100%;
        }

        .stat-card {
            background: white;
            padding: 30px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            border: 1px solid #eef2f0;
        }

        .s-icon {
            width: 55px; height: 55px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center; font-size: 22px;
        }

        /* 3. TRENDING & NEW ARRIVALS (HORIZONTAL ROW) */
        .bottom-grid {
            display: grid;
            grid-template-columns: 1fr 1fr; /* TWO EQUAL BOXES SIDE-BY-SIDE */
            gap: 25px;
            width: 100%;
        }

        .recent-box {
            background: white;
            border-radius: 20px;
            padding: 30px;
            border: 1px solid #eef2f0;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            min-height: 300px;
        }

        .recent-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #f0f4f2;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .recent-header h4 { margin: 0; color: #2d6a4f; font-size: 18px; font-weight: 800; }

        .book-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px 0;
            border-bottom: 1px solid #fcfcfc;
        }

        .book-circle {
            width: 40px; height: 40px; background: #f4f7f5;
            display: flex; align-items: center; justify-content: center;
            border-radius: 50%; color: #808847; font-size: 14px;
        }

        .fa-fire {
    animation: fire-glow 1.5s infinite alternate;
}

@keyframes fire-glow {
    from { color: #e63946; transform: scale(1); }
    to { color: #ff8c00; transform: scale(1.2); }
}

.book-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px 0;
    border-bottom: 1px solid #f8fafc;
    transition: 0.3s;
}

.book-item:hover {
    background-color: #fcfdfc;
    padding-left: 5px;
}

.book-item:last-child { border: none; }
    </style>
</head>
<body>

<div class="dash-outer-container">
    <div class="dash-content">
        
        <!-- BLOCK 1: WELCOME BANNER -->
        <div class="welcome-section">
            <div class="welcome-text">
                <h1>Welcome back, <?php echo $_SESSION['login_student_username']; ?>!</h1>
                <p>Knowledge is a journey that never ends. Continue yours at ShelfNova.</p>
            </div>
            <a href="student_books.php" class="login-btn" style="background:#808847 !important; color:white !important; text-decoration:none; padding: 15px 35px !important;">BROWSE BOOKS</a>
        </div>

        <?php
            $student_id = $_SESSION['studentid'];
            $res1 = mysqli_query($db, "SELECT count(*) as t FROM books");
            $res2 = mysqli_query($db, "SELECT count(*) as t FROM issueinfo WHERE studentid='$student_id' AND approve='pending'");
            $res3 = mysqli_query($db, "SELECT count(*) as t FROM issueinfo WHERE studentid='$student_id' AND approve='yes'");
            $total_books = mysqli_fetch_assoc($res1)['t'];
            $pending = mysqli_fetch_assoc($res2)['t'];
            $issued = mysqli_fetch_assoc($res3)['t'];
        ?>

        <!-- BLOCK 2: STATS (NOW STRETCHED) -->
        <div class="stats-grid">
    <!-- Card 1: Collection -->
    <a href="student_books.php" class="stat-card-link">
        <div class="stat-card">
            <div class="s-icon" style="background:#f0fdf4; color:#16a34a;"><i class="fa-solid fa-book"></i></div>
            <div>
                <h2 style="margin:0; font-size:28px;"><?php echo $total_books; ?></h2>
                <p style="margin:0; font-size:12px; color:#666; font-weight:700; text-transform:uppercase;">Collection</p>
            </div>
        </div>
    </a>

    <!-- Card 2: Requests -->
    <a href="request_book.php" class="stat-card-link">
        <div class="stat-card">
            <div class="s-icon" style="background:#fff7ed; color:#ea580c;"><i class="fa-solid fa-clock-rotate-left"></i></div>
            <div>
                <h2 style="margin:0; font-size:28px;"><?php echo $pending; ?></h2>
                <p style="margin:0; font-size:12px; color:#666; font-weight:700; text-transform:uppercase;">Requests</p>
            </div>
        </div>
    </a>

    <!-- Card 3: My Books -->
    <a href="student_issue_info.php" class="stat-card-link">
        <div class="stat-card">
            <div class="s-icon" style="background:#eff6ff; color:#2563eb;"><i class="fa-solid fa-bookmark"></i></div>
            <div>
                <h2 style="margin:0; font-size:28px;"><?php echo $issued; ?></h2>
                <p style="margin:0; font-size:12px; color:#666; font-weight:700; text-transform:uppercase;">My Books</p>
            </div>
        </div>
    </a>
</div>
<!-- BLOCK 3: AUTOMATIC TRENDING & NEW ARRIVALS -->
<div class="bottom-grid">
    
    <!-- 🔥 AUTOMATIC TRENDING (Popularity Algorithm) -->
    <div class="recent-box">
        <div class="recent-header">
            <h4><i class="fa-solid fa-fire" style="color:#e63946;"></i> Trending Now</h4>
        </div>
        
        <?php
        /* 
           THE ALGORITHM:
           1. Count how many times each book appears in 'issueinfo'.
           2. Join with 'books' to get the names/images.
           3. Sort by the highest borrow count.
        */
        $trending_q = "SELECT b.bookname, b.bookpic, COUNT(i.bookid) as borrow_count 
                       FROM books b
                       LEFT JOIN issueinfo i ON b.bookid = i.bookid
                       GROUP BY b.bookid
                       ORDER BY borrow_count DESC, b.bookid DESC 
                       LIMIT 3";
        
        $trending_res = mysqli_query($db, $trending_q);

        if(mysqli_num_rows($trending_res) > 0) {
            while($t_row = mysqli_fetch_assoc($trending_res)) {
                // If a book has 0 borrows, it's not really trending yet
                $count = $t_row['borrow_count'];
                ?>
                <div class="book-item" style="display: flex; align-items: center; gap: 15px; padding: 12px 0; border-bottom: 1px solid #fafafa;">
                    <div class="book-circle" style="background:#fff5f5; color:#e63946; border: 1px solid #ffebeb; width:40px; height:40px; border-radius:50%; display:flex; align-items:center; justify-content:center;">
                        <i class="fa-solid fa-arrow-trend-up"></i>
                    </div>
                    <div style="flex:1;">
                        <h5 style="margin:0; font-size:14px;"><?php echo $t_row['bookname']; ?></h5>
                        <p style="margin:0; font-size:11px; color:#64748b;">
                            Borrowed <span style="color:#2d6a4f; font-weight:800;"><?php echo $count; ?> times</span>
                        </p>
                    </div>
                    <?php if($count > 0): ?>
                        <div style="font-size: 9px; background:#e63946; color:white; padding:2px 8px; border-radius:10px; font-weight:700;">HOT</div>
                    <?php endif; ?>
                </div>
                <?php
            }
        }
        ?>
    </div>

    <!-- ✨ NEW ARRIVALS (Matches Aligned Design) -->
    <div class="recent-box">
        <div class="recent-header">
            <h4>New Arrivals</h4>
            <a href="student_books.php" style="color:#808847; font-size:11px; font-weight:700; text-decoration:none;">VIEW ALL</a>
        </div>
        <?php
        $new_res = mysqli_query($db, "SELECT bookname, bookpic FROM books ORDER BY bookid DESC LIMIT 3");
        while($n_row = mysqli_fetch_assoc($new_res)) {
            ?>
            <div class="book-item" style="display: flex; align-items: center; gap: 15px; padding: 12px 0; border-bottom: 1px solid #fafafa;">
                <div class="book-circle" style="width:40px; height:40px; border-radius:50%; background:#f4f7f5; display:flex; align-items:center; justify-content:center; color:#808847;"><i class="fa-solid fa-plus"></i></div>
                <div>
                    <h5 style="margin:0; font-size:14px;"><?php echo $n_row['bookname']; ?></h5>
                    <p style="margin:0; font-size:11px; color:#94a3b8;">Just added</p>
                </div>
            </div>
        <?php } ?>
    </div>

</div>
</div>

</body>
</html>