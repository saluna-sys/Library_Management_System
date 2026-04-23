<?php session_start(); ?>
<header class="header">
    <div class="navbar-container">
        <!-- Brand Left -->
        <a href="index.php" class="logo-area">
    <!-- This replaces the logo2.jpg image -->
    <div class="brand-logo-icon">
        <i class="fa-solid fa-book-open-reader"></i>
    </div>
    
    <div class="brand-text">
        <h3 style="letter-spacing: 1px; font-weight: 800; color: var(--lib-green);">
            ShelfNova<span style="color: var(--gold-accent);">.</span>
        </h3>
    </div>
</a>

        <!-- Menu Right -->
        <ul class="nav-menu">
            <li><a href="index.php">Home</a></li>
            <li><a href="index_books.php">Books</a></li>
            
            <?php if(isset($_SESSION['login_student_username'])): ?>
                <li style="position:relative;">
                    <a href="javascript:void(0)" onclick="toggleUser()" class="login-btn">
                        <i class="fa fa-user"></i> <?php echo $_SESSION['login_student_username']; ?>
                    </a>
                    <div id="userDrop" class="dropdown-content">
                        <a href="profile.php">My Profile</a>
                        <a href="logout.php">Logout</a>
                    </div>
                </li>
            <?php elseif(isset($_SESSION['login_admin_username'])): ?>
                <li><a href="admin_dashboard.php" class="login-btn">Admin Panel</a></li>
            <?php else: ?>
                <li><a href="admin.php">Admin</a></li>
                <li><a href="student.php" class="login-btn" style="color: white !important;">Student Login</a></li>
            <?php endif; ?>
        </ul>
    </div>
</header>

<script>
    function toggleUser() { document.getElementById("userDrop").classList.toggle("show"); }
    window.onclick = function(e) { 
        if (!e.target.closest('.login-btn')) {
            var d = document.getElementById("userDrop");
            if (d && d.classList.contains('show')) d.classList.remove('show');
        }
    }
</script>