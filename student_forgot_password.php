<?php
    include "connection.php";
    include "index_navbar.php"; // Unified ShelfNova Navbar
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Recovery | ShelfNova</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* --- STUDENT RECOVERY PAGE UI --- */
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

        .recovery-icon-header {
            font-size: 50px;
            color: #808847; /* Olive Gold */
            margin-bottom: 15px;
        }

        .recovery-header-text h2 {
            color: #2d6a4f; /* Academic Green */
            font-size: 22px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }

        .recovery-header-text p {
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

        .back-to-login {
            display: block;
            margin-top: 20px;
            font-size: 12px;
            color: #64748b;
            text-decoration: none;
            font-weight: 600;
        }
        .back-to-login:hover { color: #2d6a4f; }
    </style>
</head>
<body>

<div class="sn-main-wrapper">
    <div class="sn-form-card">
        <div class="recovery-icon-header">
            <i class="fa-solid fa-user-lock"></i>
        </div>
        <div class="recovery-header-text">
            <h2>Student Recovery</h2>
            <p>Verify your details to reset your password</p>
        </div>

        <form action="" method="post">
            <input type="text" class="sn-input" placeholder="Username" name="student_username" required>
            <input type="email" class="sn-input" placeholder="Registered Email" name="Email" required>
            
            <div style="position:relative;">
                <input type="password" class="sn-input" placeholder="New Password" name="password" id="forgot" required>
                <i class="fas fa-eye" id="eye-toggle" style="position:absolute; right:15px; top:16px; color:#94a3b8; cursor:pointer;"></i>
            </div>

            <button type="submit" class="sn-submit-btn" name="change">Update Password</button>
            <a href="student.php" class="back-to-login">Back to Student Login</a>
        </form>
    </div>
</div>

<?php
    if(isset($_POST['change']))
    {
        $username = mysqli_real_escape_string($db, $_POST['student_username']);
        $email = mysqli_real_escape_string($db, $_POST['Email']);
        $new_pass = mysqli_real_escape_string($db, $_POST['password']);

        $res = mysqli_query($db, "SELECT * FROM `student` WHERE student_username='$username' AND Email='$email';");
        $count = mysqli_num_rows($res);
        
        if($count == 0)
        {
            echo "<script>alert('Incorrect information. Please check your username and email.');</script>";
        }
        else
        {
            if(mysqli_query($db, "UPDATE student SET Password='$new_pass' WHERE student_username='$username' AND Email='$email';"))
            {
                echo "<script>alert('Password updated successfully! You can now login.'); window.location='student.php';</script>";
            }
        }
    }
?>

<?php include "footer.php"; ?>

<script>
    // Integrated Eye Toggle Logic
    const passField = document.getElementById("forgot");
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

</body>
</html>