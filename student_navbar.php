<?php
	session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Panel | ShelfNova</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="student_auth.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php
    if(isset($_SESSION['login_student_username']))
    {
        // Database logic
        $r=mysqli_query($db,"SELECT COUNT(status) as total FROM message where status='no' and username='$_SESSION[login_student_username]' and sender='admin';");
        $b = mysqli_query($db,"SELECT * FROM issueinfo where studentid='$_SESSION[studentid]' and approve='yes' ORDER BY returndate ASC limit 0,1;");
        $var1 = mysqli_num_rows($b);
        $bi=mysqli_fetch_assoc($b);
        ?>
        <header class="header">
            <div class="navbar-container">
                <!-- Brand Left -->
                <a href="student_dashboard.php" class="logo-area">
                    <div class="brand-logo-icon">
                        <i class="fa-solid fa-book-open-reader"></i>
                    </div>
                    <div class="brand-text">
                        <h3 style="letter-spacing: 1px; font-weight: 800; color: var(--lib-green); margin:0;">
                            ShelfNova<span style="color: var(--gold-accent);">.</span>
                        </h3>
                    </div>
                </a>

                <!-- Nav Menu Right -->
                <nav>
                    <ul class="nav-menu">
                        <?php if($var1==1){ 
                            $t=mysqli_query($db,"SELECT * FROM timer where stdid='$_SESSION[studentid]' and bid='$bi[bookid]';");
                            $res = mysqli_fetch_assoc($t);
                        ?>
    
                            <li><span id="demo" style="color:#d9534f; font-weight:700; font-family:monospace; padding:0 10px;"></span></li>
                        <?php } ?>

                        <li><a href="student_dashboard.php">Dashboard</a></li>
                        <li><a href="student_books.php">Books</a></li>
                        <li><a href="request_book.php">Requested</a></li>
                        <li><a href="student_issue_info.php">Issue Info</a></li>
                       
                        <!-- Premium Initial-Based Dropdown -->
                       <li style="position:relative; list-style:none;">
    <?php 
        // 1. Logic for Initial and Image Path
        $user_initial = strtoupper(substr($_SESSION['login_student_username'], 0, 1)); 
        $nav_pic_path = "images/" . $_SESSION['pic'];
    ?>
    
    <button onclick="toggleUserDrop()" class="user-pill-btn" id="userBtn">
        <div class="user-avatar-circle">
            <?php 
                // 2. Logic: Show image if it exists, otherwise show initial
                if(!empty($_SESSION['pic']) && file_exists($nav_pic_path)) {
                    echo "<img src='$nav_pic_path' class='nav-avatar-img-small'>";
                } else {
                    echo "<span class='nav-avatar-initial'>$user_initial</span>";
                }
            ?>
        </div>
        <span class="nav-username"><?php echo $_SESSION['login_student_username']; ?></span>
        <i class="fas fa-caret-down caret-icon"></i>
    </button>
    
    <div id="sn-dropdown" class="premium-dropdown">
        <a href="profile.php"><i class="fa-solid fa-circle-user"></i> My Profile</a>
        <a href="student_update_password.php"><i class="fa-solid fa-shield-halved"></i> Security</a>
        <a href="logout.php" class="logout-link"><i class="fa-solid fa-power-off"></i> Logout</a>
    </div>
</li>
                    </ul>
                </nav>
            </div>
        </header>
    <?php
    }
    else {
        ?>
        <header class="header">
            <div class="navbar-container">
                <a href="index.php" class="logo-area">
                    <div class="brand-logo-icon"><i class="fa-solid fa-book-open-reader"></i></div>
                    <div class="brand-text"><h3>ShelfNova<span>.</span></h3></div>
                </a>
                <nav>
                    <ul class="nav-menu">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="index_books.php">Library</a></li>
                        <li><a href="admin.php">Admin</a></li>
                        <li><a href="student.php" class="login-btn" style="color: white !important;">Student Login</a></li>
                    </ul>
                </nav>
            </div>
        </header>
        <?php
    }
    ?>
    
    <div style="height: 80px;"></div>

    <script>
        function toggleUserDrop() {
            document.getElementById("sn-dropdown").classList.toggle("show-premium");
        }

        window.addEventListener('click', function(e) {
            if (!document.getElementById('userBtn').contains(e.target)) {
                var dropdown = document.getElementById("sn-dropdown");
                if (dropdown) dropdown.classList.remove("show-premium");
            }
        });

        const currentLoc = location.href;
        const menuItems = document.querySelectorAll('.nav-menu a');
        menuItems.forEach(item => {
            if(item.href === currentLoc) { item.classList.add("active"); }
        });
    </script>
</body>
</html>