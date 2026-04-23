<?php
session_start();

// 1. Check if admin is logged in
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
    <title>Admin Dashboard | ShelfNova</title>
    <!-- Force exact same fonts as student side -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="admin.css?v=<?php echo time(); ?>">
    
    <style>
        /* --- EXACT FONT SYNC --- */
        :root {
            --font-main: 'Poppins', sans-serif;
            --font-head: 'Montserrat', sans-serif;
        }

        .admin-dash-body {
            padding: 40px 0;
            background-color: #f4f7f5;
            min-height: 90vh;
            font-family: var(--font-main);
        }

        /* --- CONTAINER ALIGNMENT (Exact match to Navbar) --- */
        .dash-container {
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 30px;
        }

        /* --- WELCOME BANNER (Student Side Style) --- */
        .welcome-card {
            width: 100%;
            background: linear-gradient(135deg, #2d6a4f 0%, #1b4332 100%);
            border-radius: 20px;
            padding: 50px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(45, 106, 79, 0.15);
        }

        .welcome-text h1 { 
            font-family: var(--font-head);
            font-size: 32px; 
            margin: 0; 
            font-weight: 800; 
        }
        .welcome-text p { opacity: 0.8; margin-top: 10px; font-size: 16px; }

        /* --- STATS GRID (4-column layout) --- */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
            width: 100%;
        }

        .stat-card-link {
            text-decoration: none !important;
            transition: transform 0.3s ease;
        }

        .stat-card {
            background: white;
            padding: 30px 20px;
            border-radius: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            border: 1px solid #eef2f0;
            transition: all 0.3s ease;
        }

        .stat-card-link:hover .stat-card {
            transform: translateY(-8px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.08);
        }

        .icon-circle {
            width: 55px; height: 55px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; margin-bottom: 15px;
        }

        .stat-card h3 {
            font-family: var(--font-head);
            font-size: 32px;
            font-weight: 800;
            color: #1e293b;
            margin: 0;
        }

        .stat-card p {
            font-family: var(--font-main);
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 5px;
        }

        /* ICON COLORS (Creative Palette) */
        .bg-stu { background: #eff6ff; color: #2563eb; }
        .bg-book { background: #f0fdf4; color: #16a34a; }
        .bg-auth { background: #fdf2f8; color: #db2777; }
        .bg-cat { background: #f5f3ff; color: #7c3aed; }
        .bg-pen { background: #fff7ed; color: #ea580c; }
        .bg-iss { background: #f0f9ff; color: #0ea5e9; }
        .bg-ret { background: #ecfdf5; color: #059669; }
        .bg-exp { background: #fef2f2; color: #dc2626; }
    </style>
</head>
<body>

<div class="admin-dash-body">
    <div class="dash-container">
        
        <!-- BLOCK 1: WELCOME BANNER -->
        <div class="welcome-card">
            <div class="welcome-text">
                <h1>Master Administrator Panel</h1>
                <p>Monitor library usage, manage digital assets, and oversee student activity.</p>
            </div>
            <div style="font-size: 70px; opacity: 0.1;"><i class="fa-solid fa-toolbox"></i></div>
        </div>

        <!-- BLOCK 2: STATS GRID -->
        <div class="stats-grid">
            
            <?php
                // Query counts
                $student_count = mysqli_num_rows(mysqli_query($db, "SELECT * FROM student"));
                $book_count = mysqli_num_rows(mysqli_query($db, "SELECT * FROM books"));
                $author_count = mysqli_num_rows(mysqli_query($db, "SELECT * FROM authors"));
                $cat_count = mysqli_num_rows(mysqli_query($db, "SELECT * FROM category"));
                
                $pending_count = mysqli_num_rows(mysqli_query($db, "SELECT * FROM issueinfo WHERE approve='no' OR approve='pending'"));
                $issued_count = mysqli_num_rows(mysqli_query($db, "SELECT * FROM issueinfo WHERE approve='yes' OR approve='approved'"));
                $returned_count = mysqli_num_rows(mysqli_query($db, "SELECT * FROM issueinfo WHERE approve='returned'"));
                $expired_count = mysqli_num_rows(mysqli_query($db, "SELECT * FROM issueinfo WHERE approve='expired' OR fine > 0"));
            ?>

            <a href="student_info.php" class="stat-card-link">
                <div class="stat-card">
                    <div class="icon-circle bg-stu"><i class="fa-solid fa-users"></i></div>
                    <h3><?php echo $student_count; ?></h3>
                    <p>Students</p>
                </div>
            </a>

            <a href="manage_books.php" class="stat-card-link">
                <div class="stat-card">
                    <div class="icon-circle bg-book"><i class="fa-solid fa-book"></i></div>
                    <h3><?php echo $book_count; ?></h3>
                    <p>Books</p>
                </div>
            </a>

            <a href="manage_authors.php" class="stat-card-link">
                <div class="stat-card">
                    <div class="icon-circle bg-auth"><i class="fa-solid fa-pen-nib"></i></div>
                    <h3><?php echo $author_count; ?></h3>
                    <p>Authors</p>
                </div>
            </a>

            <a href="manage_categories.php" class="stat-card-link">
                <div class="stat-card">
                    <div class="icon-circle bg-cat"><i class="fa-solid fa-tags"></i></div>
                    <h3><?php echo $cat_count; ?></h3>
                    <p>Categories</p>
                </div>
            </a>

            <a href="request_info.php" class="stat-card-link">
                <div class="stat-card">
                    <div class="icon-circle bg-pen"><i class="fa-solid fa-clock-rotate-left"></i></div>
                    <h3><?php echo $pending_count; ?></h3>
                    <p>Pending</p>
                </div>
            </a>

            <a href="manage_issued_books.php" class="stat-card-link">
                <div class="stat-card">
                    <div class="icon-circle bg-iss"><i class="fa-solid fa-bookmark"></i></div>
                    <h3><?php echo $issued_count; ?></h3>
                    <p>Issued</p>
                </div>
            </a>

            <a href="returned.php" class="stat-card-link">
                <div class="stat-card">
                    <div class="icon-circle bg-ret"><i class="fa-solid fa-calendar-check"></i></div>
                    <h3><?php echo $returned_count; ?></h3>
                    <p>Returned</p>
                </div>
            </a>

            <a href="expired.php" class="stat-card-link">
                <div class="stat-card">
                    <div class="icon-circle bg-exp"><i class="fa-solid fa-circle-exclamation"></i></div>
                    <h3><?php echo $expired_count; ?></h3>
                    <p>Expired</p>
                </div>
            </a>

        </div>
    </div>
</div>

</body>
</html>