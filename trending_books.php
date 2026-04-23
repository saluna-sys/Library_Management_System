<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Panel</title>
    <!-- Icons and Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --nav-bg: #ffffff;
            --primary: #2563eb; /* Modern Blue */
            --text-main: #1f2937;
            --text-muted: #6b7280;
            --border-color: #f3f4f6;
            --hover-bg: #f9fafb;
            --danger: #ef4444;
        }

        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background-color: #fbfbfb;
        }

        /* Navbar Main Container */
        .header {
            background-color: var(--nav-bg);
            border-bottom: 1px solid var(--border-color);
            height: 70px;
            display: flex;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .container {
            width: 100%;
            max-width: 1300px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Logo & Brand */
        .brand-area {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .logo img {
            width: 42px;
            height: 42px;
            border-radius: 8px;
            object-fit: cover;
        }

        .brand-text h3 {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
            color: var(--text-main);
            letter-spacing: -0.5px;
        }

        /* Navigation Links */
        .nav-links {
            display: flex;
            align-items: center;
            gap: 8px;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .nav-links li a {
            text-decoration: none;
            color: var(--text-muted);
            font-size: 14px;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .nav-links li a:hover {
            background-color: var(--hover-bg);
            color: var(--primary);
        }

        .nav-links li a.active {
            background-color: #eff6ff;
            color: var(--primary);
        }

        /* Timer Style */
        #demo {
            font-size: 13px;
            font-weight: 600;
            color: var(--danger);
            background: #fef2f2;
            padding: 6px 12px;
            border-radius: 6px;
            margin-right: 10px;
            border: 1px solid #fee2e2;
        }

        /* Profile Dropdown */
        .user-dropdown {
            position: relative;
            margin-left: 15px;
            padding-left: 15px;
            border-left: 1px solid var(--border-color);
        }

        .user-pill {
            display: flex;
            align-items: center;
            gap: 10px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 8px;
            transition: background 0.2s;
        }

        .user-pill:hover { background-color: var(--hover-bg); }

        .user-pill img {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #eee;
        }

        .user-pill span {
            font-size: 14px;
            font-weight: 500;
            color: var(--text-main);
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 50px;
            background: white;
            min-width: 180px;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
            overflow: hidden;
            z-index: 100;
        }

        .dropdown-menu a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            color: var(--text-main);
            text-decoration: none;
            font-size: 14px;
            border-bottom: 1px solid var(--border-color);
        }

        .dropdown-menu a:last-child { border-bottom: none; color: var(--danger); }
        .dropdown-menu a:hover { background-color: var(--hover-bg); }

        .show { display: block; }

        /* Icon spacing */
        .nav-links i { margin-right: 6px; font-size: 16px; }
    </style>
</head>
<body>

    <header class="header">
        <div class="container">
            <!-- Left: Brand -->
            <a href="index.php" class="brand-area">
                <div class="logo">
                    <img src="images/logo2.jpg" alt="LMS">
                </div>
                <div class="brand-text">
                    <h3>Library System</h3>
                </div>
            </a>

            <!-- Right: Navigation -->
            <nav style="display: flex; align-items: center;">
                <ul class="nav-links" id="menuitems">
                    <?php if(isset($_SESSION['login_student_username'])): 
                        // PHP Logic for messages/timer
                        $b = mysqli_query($db,"SELECT * FROM issueinfo where studentid='$_SESSION[studentid]' and approve='yes' ORDER BY returndate ASC limit 0,1;");
                        if(mysqli_num_rows($b) == 1){
                            $bi = mysqli_fetch_assoc($b);
                            $t = mysqli_query($db,"SELECT * FROM timer where stdid='$_SESSION[studentid]' and bid='$bi[bookid]';");
                            $res = mysqli_fetch_assoc($t);
                    ?>
                        <script>
                            var countDownDate = new Date("<?php echo $res['date']; ?>").getTime();
                            var x = setInterval(function() {
                                var now = new Date().getTime();
                                var distance = countDownDate - now;
                                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                var minutes = Math.floor((distance % (1000 * 60)) / (1000 * 60));
                                var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                                document.getElementById("demo").innerHTML = "<i class='far fa-clock'></i> " + days + "d " + hours + "h " + minutes + "m " + seconds + "s ";
                                if (distance < 0) { clearInterval(x); document.getElementById("demo").innerHTML = "EXPIRED"; }
                            }, 1000);
                        </script>
                        <li><span id="demo"></span></li>
                        <?php } ?>

                        <li><a href="student_dashboard.php">Dashboard</a></li>
                        <li><a href="student_books.php">Books</a></li>
                        <li><a href="request_book.php">Requests</a></li>
                        <li><a href="student_issue_info.php">My Issues</a></li>
                    
                    <?php else: ?>
                        <li><a href="index.php"><i class="fa-solid fa-house"></i> Home</a></li>
                        <li><a href="index_books.php"><i class="fa-solid fa-book-open"></i> Books</a></li>
                        <li><a href="admin.php"><i class="fa-solid fa-user-gear"></i> Admin</a></li>
                        <li><a href="student.php"><i class="fa-solid fa-user-graduate"></i> Student</a></li>
                    <?php endif; ?>
                </ul>

                <?php if(isset($_SESSION['login_student_username'])): ?>
                <!-- Profile Section -->
                <div class="user-dropdown">
                    <button onclick="toggleDrop()" class="user-pill">
                        <img src="images/<?php echo $_SESSION['pic']; ?>" alt="User">
                        <span><?php echo $_SESSION['login_student_username']; ?></span>
                        <i class="fa-solid fa-chevron-down" style="font-size: 10px; color: var(--text-muted);"></i>
                    </button>
                    <div class="dropdown-menu" id="userMenu">
                        <a href="profile.php"><i class="fa-regular fa-circle-user"></i> My Profile</a>
                        <a href="student_update_password.php"><i class="fa-solid fa-shield-halved"></i> Security</a>
                        <a href="logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</a>
                    </div>
                </div>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <script>
        function toggleDrop() {
            document.getElementById("userMenu").classList.toggle("show");
        }

        window.onclick = function(event) {
            if (!event.target.closest('.user-pill')) {
                var dropdowns = document.getElementsByClassName("dropdown-menu");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }

        // Auto-active link highlight
        const currentPath = location.href;
        const navLinks = document.querySelectorAll('.nav-links a');
        navLinks.forEach(link => {
            if(link.href === currentPath){
                link.classList.add('active');
            }
        });
    </script>
</body>
</html>