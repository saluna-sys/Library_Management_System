<?php
    include "connection.php";
    include "index_navbar.php";
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Library Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>

    <!-- HERO SECTION -->
    <section class="hero">
    <div class="container">
        <!-- Modern Logo Icon for Hero -->
        <div style="font-size: 60px; color: var(--gold-accent); margin-bottom: 20px;">
            <i class="fa-solid fa-book-bookmark"></i>
        </div>
        
        <h1 style="font-size: 85px; margin-bottom: 5px; color: var(--lib-green);">ShelfNova.</h1>
        <h2 style="font-size: 22px; color: var(--gold-accent); font-weight: 600; text-transform: uppercase; letter-spacing: 4px; margin-bottom: 30px;">
            Library Management System
        </h2>
        
        <p>A sophisticated digital sanctuary for Kathmandu College of Central State. Search, borrow, and manage your resources with creative concepts.</p>
        
        <div style="margin-top: 40px;">
            <a href="student.php" class="login-btn" style="padding: 18px 50px !important; font-size: 16px; text-decoration: none;">EXPLORE THE LIBRARY</a>
        </div>
    </div>
</section>

    <!-- OUR SERVICES -->
    <section class="services-section">
        <div class="section-title" style="text-align:center; margin-bottom:50px;">
            <h2 style="color:var(--lib-green); font-size: 28px; text-transform:uppercase; letter-spacing:2px;">Our Services</h2>
        </div>
        <div class="services-grid">
            <div class="service-card">
                <i class="fa fa-book-open"></i>
                <h4>Digital Catalog</h4>
                <p>Browse thousands of curated books across all academic faculties with our instant search system.</p>
            </div>
            <div class="service-card">
                <i class="fa fa-clock-rotate-left"></i>
                <h4>Real-time Tracking</h4>
                <p>Monitor your borrowed books, due dates, and issue history directly from your student dashboard.</p>
            </div>
            <div class="service-card">
                <i class="fa fa-shield-halved"></i>
                <h4>Secure Access</h4>
                <p>Enjoy a highly secure environment for your academic records with our verified login protocols.</p>
            </div>
        </div>
    </section>


<?php include "footer.php"; ?>

</body>
</html>