<?php session_start(); ?>
<header class="header">
    <div class="navbar-container">
        <!-- Brand Left (Exact Match to Index) -->
        <a href="admin_dashboard.php" class="logo-area">
            <div class="brand-logo-icon">
                <i class="fa-solid fa-book-open-reader"></i>
            </div>
            <div class="brand-text">
                <h3 style="letter-spacing: 1px; font-weight: 800; color: var(--lib-green); margin:0;">
                    ShelfNova<span style="color: var(--gold-accent);">.</span>
                </h3>
            </div>
        </a>

        <!-- Menu Right -->
        <ul class="nav-menu">
            <?php if(isset($_SESSION['login_admin_username'])): ?>
                <li><a href="admin_dashboard.php">Dashboard</a></li>

                <!-- Dropdown 1: Records -->
                <li style="position:relative;">
                    <button class="admin-trigger-btn" onclick="toggleAdmin('drop-rec')">Records <i class="fa fa-caret-down"></i></button>
                    <div id="drop-rec" class="admin-dropdown">
                        <a href="student_info.php"><i class="fa-solid fa-users"></i> Students</a>
                        <a href="manage_authors.php"><i class="fa-solid fa-pen-nib"></i> Authors</a>
                        <a href="manage_categories.php"><i class="fa-solid fa-tags"></i> Categories</a>
                    </div>
                </li>

                <!-- Dropdown 2: Library -->
                <li style="position:relative;">
                    <button class="admin-trigger-btn" onclick="toggleAdmin('drop-lib')">Library <i class="fa fa-caret-down"></i></button>
                    <div id="drop-lib" class="admin-dropdown">
                        <a href="add_book.php"><i class="fa-solid fa-plus"></i> Add Book</a>
                        <a href="manage_books.php"><i class="fa-solid fa-book"></i> Book List</a>
                        <a href="request_info.php"><i class="fa-solid fa-bell"></i> Request Info</a>
                    </div>
                </li>

                <!-- Dropdown 3: Issuance -->
                <li style="position:relative;">
                    <button class="admin-trigger-btn" onclick="toggleAdmin('drop-iss')">Issuance <i class="fa fa-caret-down"></i></button>
                    <div id="drop-iss" class="admin-dropdown">
                        <a href="manage_issued_books.php">Issued Books</a>
                        <a href="returned.php">Returned History</a>
                        <a href="expired.php">Overdue List</a>
                    </div>
                </li>

                 <li style="position:relative;">
                    <?php 
                        $init = strtoupper(substr($_SESSION['login_admin_username'], 0, 1)); 
                        $admin_pic_path = "images/" . $_SESSION['pic1'];
                    ?>
                    <button class="login-btn" onclick="toggleAdmin('dropUser')" id="adminBtn" style="border:none; cursor:pointer; color: white !important; display: flex; align-items: center; gap: 10px;">
                        <div class="admin-avatar-circle" style="overflow: hidden; display: flex; align-items: center; justify-content: center;">
                            <?php 
                                // Show Image if exists, else show Initial
                                if(!empty($_SESSION['pic1']) && file_exists($admin_pic_path)) {
                                    echo "<img src='$admin_pic_path' style='width:100%; height:100%; object-fit:cover;'>";
                                } else {
                                    echo "<span>$init</span>";
                                }
                            ?>
                        </div>
                        <?php echo $_SESSION['login_admin_username']; ?>
                        <i class="fa fa-caret-down" style="font-size:10px; margin-left: 5px;"></i>
                    </button>
                    
                    <div id="dropUser" class="admin-dropdown">
                        <a href="admin_profile.php"><i class="fa-solid fa-user-gear"></i> My Profile</a>
                        <a href="admin_update_password.php"><i class="fa-solid fa-key"></i> Security Settings</a>
                        <a href="logout.php" style="color:red !important; border-top: 1px solid #eee;"><i class="fa-solid fa-power-off"></i> Logout</a>
                    </div>
                </li>


            <?php else: ?>
                <!-- Guest View -->
                <li><a href="index.php">Home</a></li>
                <li><a href="index_books.php">Books</a></li>
                <li><a href="admin.php">Admin Login</a></li>
                <li><a href="student.php" class="login-btn" style="color: white !important;">Student Login</a></li>
            <?php endif; ?>
        </ul>
    </div>
</header>
<div style="height: 80px;"></div>

<script>
    function toggleAdmin(id) {
        // Close all other open dropdowns
        var dropdowns = document.getElementsByClassName("admin-dropdown");
        for (var i = 0; i < dropdowns.length; i++) {
            if (dropdowns[i].id !== id) {
                dropdowns[i].classList.remove("show");
            }
        }
        // Toggle the clicked one
        document.getElementById(id).classList.toggle("show");
    }

    // Close when clicking outside
    window.onclick = function(e) {
        if (!e.target.matches('.admin-trigger-btn') && !e.target.closest('.login-btn')) {
            var d = document.getElementsByClassName("admin-dropdown");
            for (var i = 0; i < d.length; i++) {
                if (d[i].classList.contains('show')) d[i].classList.remove('show');
            }
        }
    }
</script>