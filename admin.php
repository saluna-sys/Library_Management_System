<?php
    include "connection.php";
    include "index_navbar.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login | ShelfNova</title>
    <!-- Use specific versioning to clear cache -->
    <link rel="stylesheet" href="student_auth.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* --- ADMIN AUTH OVERRIDE --- */
        .sn-main-wrapper {
            padding: 100px 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f4f7f5;
            min-height: 85vh;
        }

        .sn-form-card {
            width: 400px !important;
            background: #ffffff !important;
            border-radius: 20px !important;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
            padding: 40px !important;
            text-align: center;
        }

        .admin-icon-header {
            font-size: 50px;
            color: #808847; /* Olive Gold */
            margin-bottom: 15px;
        }

        .admin-header-text h2 {
            color: #2d6a4f; /* Academic Green */
            font-size: 22px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }

        .admin-header-text p {
            font-size: 13px;
            color: #94a3b8;
            margin-bottom: 30px;
        }

        /* Input Styling */
        .sn-input {
            all: unset !important;
            display: block !important;
            box-sizing: border-box !important;
            width: 100% !important;
            height: 50px !important;
            margin: 15px 0 !important;
            padding: 0 15px !important;
            background-color: #f8fafc !important;
            border: 1.5px solid #e2e8f0 !important;
            border-radius: 10px !important;
            font-family: 'Poppins', sans-serif !important;
            font-size: 14px !important;
            color: #333 !important;
            text-align: left;
        }

        .sn-input:focus {
            border-color: #2d6a4f !important;
            background-color: #ffffff !important;
        }

        /* Submit Button */
        .sn-submit-btn {
            all: unset !important;
            display: block !important;
            width: 100% !important;
            box-sizing: border-box !important;
            background-color: #2d6a4f !important;
            color: #ffffff !important; /* Forced White */
            text-align: center !important;
            padding: 14px !important;
            border-radius: 10px !important;
            font-weight: 700 !important;
            cursor: pointer !important;
            margin-top: 20px !important;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .sn-submit-btn:hover { background-color: #808847 !important; }

        .forgot-link {
            display: block;
            margin-top: 20px;
            font-size: 12px;
            color: #64748b;
            text-decoration: none;
        }
        .forgot-link:hover { color: #2d6a4f; text-decoration: underline; }
    </style>
</head>
<body>

<div class="sn-main-wrapper">
    <div class="sn-form-card">
        <div class="admin-icon-header">
            <i class="fa-solid fa-user-shield"></i>
        </div>
        <div class="admin-header-text">
            <h2>Admin Login</h2>
            <p>Access the ShelfNova Management Console</p>
        </div>

        <form action="" method="post">
            <input type="text" class="sn-input" placeholder="Admin Username" name="username" required>
            
            <div style="position:relative;">
                <input type="password" class="sn-input" placeholder="Password" name="password" id="adminpass" required>
                <i class="fas fa-eye" id="eye-toggle" style="position:absolute; right:15px; top:16px; color:#94a3b8; cursor:pointer;"></i>
            </div>

            <button type="submit" class="sn-submit-btn" name="login">Authorize Access</button>
            <a href="admin_forgot_password.php" class="forgot-link">Trouble logging in? Forgot Password</a>
        </form>
    </div>
</div>

<?php
    if(isset($_POST['login']))
    {
        $username = mysqli_real_escape_string($db, $_POST['username']);
        $password = mysqli_real_escape_string($db, $_POST['password']);

        $res = mysqli_query($db,"SELECT * FROM `admin` WHERE username='$username' && password='$password';");
        $count = mysqli_num_rows($res);
        $row = mysqli_fetch_assoc($res);

        if($count == 0)
        {
            echo "<script>alert('Unauthorized access. Invalid credentials.');</script>";
        }
        else
        {
            $_SESSION['login_admin_username'] = $username;
            $_SESSION['pic1'] = $row['pic'];
            $_SESSION['stdusername'] = '';
            echo "<script>window.location='admin_dashboard.php';</script>";
        }
    }
?>

<script>
    // Password Toggle
    const passField = document.getElementById("adminpass");
    const toggleIcon = document.getElementById("eye-toggle");

    toggleIcon.onclick = function() {
        if (passField.type === "password") {
            passField.type = "text";
            toggleIcon.className = "fas fa-eye-slash";
        } else {
            passField.type = "password";
            toggleIcon.className = "fas fa-eye";
        }
    };
</script>
<?php include "footer.php"; ?>

</body>
</html>