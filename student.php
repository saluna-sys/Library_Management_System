<?php
    include "connection.php";
    include "index_navbar.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Portal | ShelfNova</title>
    <!-- Use specific versioning to clear cache -->
    <link rel="stylesheet" href="student_auth.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
    /* --- ULTIMATE FORM FIX --- */
    body { background-color: #f4f7f5 !important; margin: 0; font-family: 'Poppins', sans-serif; }

    .sn-main-wrapper {
        padding-top: 100px;
        padding-bottom: 80px;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 80vh;
    }

    .sn-form-card {
        width: 400px !important;
        height: 600px !important;
        background: #ffffff !important;
        border-radius: 20px !important;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
        position: relative;
        overflow: hidden; /* This keeps the sliding forms inside */
    }

    /* --- PERFECT TABS LOGIC --- */
.sn-toggle-group {
    width: 220px !important; /* Fixed width for math */
    margin: 35px auto !important;
    display: flex !important;
    justify-content: space-between !important;
    position: relative !important;
}

.sn-toggle-group span {
    width: 100px !important; /* Each button is exactly 100px */
    text-align: center !important;
    cursor: pointer;
    font-weight: 700;
    font-size: 14px;
    text-transform: uppercase;
    color: #94a3b8;
    transition: 0.3s;
}

#sn-indicator {
    all: unset !important;
    display: block !important;
    position: absolute !important; /* REQUIRED for movement */
    bottom: -8px !important;
    left: 20px !important;         /* Starting position */
    width: 60px !important;
    height: 4px !important;
    background: #808847 !important;
    border-radius: 10px !important;
    transition: transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
}

    /* Form Layout Fix */
    .sn-form {
        width: 400px !important;
        padding: 0 40px !important; /* This gives 40px breathing room on both sides */
        position: absolute;
        top: 110px;
        transition: 0.5s ease-in-out;
        box-sizing: border-box !important; /* CRUCIAL: Makes padding stay inside the 400px */
    }

    #sn-reg-form { left: 400px; }

    /* Input Styling */
    .sn-form input:not([type="file"]) {
        all: unset !important;
        display: block !important;
        box-sizing: border-box !important;
        width: 100% !important; /* Now fits perfectly within the padding */
        height: 48px !important;
        margin: 15px 0 !important;
        padding: 0 15px !important;
        background-color: #f8fafc !important;
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 10px !important;
        font-family: 'Poppins', sans-serif !important;
        font-size: 14px !important;
        color: #333 !important;
    }

    .sn-form input:focus {
        border-color: #2d6a4f !important;
        background-color: #ffffff !important;
    }

    /* Button Fix (No more cut off) */
    .sn-submit-btn {
        all: unset !important;
        display: block !important;
        box-sizing: border-box !important;
        width: 100% !important;
        background-color: #2d6a4f !important;
        color: #ffffff !important;
        text-align: center !important;
        padding: 14px !important;
        border-radius: 10px !important;
        font-weight: 700 !important;
        cursor: pointer !important;
        margin-top: 25px !important;
        letter-spacing: 1px;
    }

    .sn-submit-btn:hover { background-color: #808847 !important; }

    .form-footer-link {
        display: block;
        text-align: center;
        margin-top: 25px;
        font-size: 13px;
        color: #64748b;
        text-decoration: none;


    }
</style>
</head>
<body>

<div class="sn-main-wrapper">
    <div class="sn-form-card">
        <!-- Toggles -->
        <div class="sn-toggle-group">
            <span onclick="sn_login()" id="btn-log" style="color: #2d6a4f;">Login</span>
            <span onclick="sn_reg()" id="btn-reg">Register</span>
            <hr id="sn-indicator">
        </div>

        <!-- Login Form -->
        <form id="sn-login-form" class="sn-form" method="post">
            <input type="text" placeholder="Username" name="student_username" required>
            <input type="email" placeholder="Email Address" name="Email" required>
            <div style="position:relative;">
                <input type="password" placeholder="Password" name="Password" id="Pass" required>
                <i class="fas fa-eye" id="eye" style="position:absolute; right:15px; top:15px; color:#94a3b8; cursor:pointer;"></i>
            </div>
            <button type="submit" class="sn-submit-btn" name="login">LOGIN TO PORTAL</button>
            <a href="student_forgot_password.php" class="form-footer-link">Forgot Password?</a>
        </form>

        <!-- Register Form -->
        <form id="sn-reg-form" class="sn-form" method="post" enctype="multipart/form-data">
            <input type="text" placeholder="Username" name="student_username" required>
            <input type="text" placeholder="Full Name" name="FullName" required>
            <input type="email" placeholder="Email" name="Email" required>
            <div style="position:relative;">
                <input type="password" placeholder="Password" name="Password" id="Pass-reg" required>
                <i class="fas fa-eye" id="eye-reg" style="position:absolute; right:15px; top:15px; color:#94a3b8; cursor:pointer;"></i>
            </div>
            <input type="text" name="PhoneNumber" placeholder="Phone Number" required>
            <div class="file-group">
    <label>Profile Picture</label>
    <input type="file" name="file">
</div>
            <button type="submit" class="sn-submit-btn" name="register">CREATE ACCOUNT</button>
        </form>
    </div>
</div>


 <!-- PHP Logic (Unchanged) -->
    <?php
		if(isset($_POST['login'])) {
			$res=mysqli_query($db,"SELECT * FROM `student` WHERE student_username='$_POST[student_username]' AND Email='$_POST[Email]' AND password='$_POST[Password]';");
			$count=mysqli_num_rows($res);
			$row=mysqli_fetch_assoc($res);
			if($count==0) {
				echo "<script>alert('The username or password doesn\'t match.');</script>";
			} else {
				$_SESSION['login_student_username'] = $_POST['student_username'];
				$_SESSION['studentid'] = $row['studentid'];
                $_SESSION['pic'] = $row['studentpic'];
				echo "<script>window.location='student_dashboard.php';</script>";
			}
		}

        if(isset($_POST['register'])) {
            $username = $_POST['student_username'];
            $fullname = $_POST['FullName'];
            $email = $_POST['Email'];
            $password = $_POST['Password'];
            $phone = $_POST['PhoneNumber'];

            $check = "SELECT * FROM student WHERE student_username='$username'";
            if(mysqli_num_rows(mysqli_query($db, $check)) > 0) {
                echo "<script>alert('This username is already registered');</script>";
            } else {
                if(!empty($_FILES["file"]["name"])) {
                    $pic = $_FILES['file']['name'];
                    move_uploaded_file($_FILES['file']['tmp_name'], "images/".$pic);
                } else { $pic = "user2.png"; }

                $query = "INSERT INTO student (student_username, FullName, Email, Password, PhoneNumber, studentpic)
                          VALUES ('$username', '$fullname', '$email', '$password', '$phone', '$pic')";
                if(mysqli_query($db, $query)) {
                    echo "<script>alert('Registration successful! Please login.'); login();</script>";
                } else { echo "<script>alert('Error: ".mysqli_error($db)."');</script>"; }
            }
        }
	?>

<script>
    var logF = document.getElementById("sn-login-form");
    var regF = document.getElementById("sn-reg-form");
    var ind = document.getElementById("sn-indicator");
    var bL = document.getElementById("btn-log");
    var bR = document.getElementById("btn-reg");

    function sn_reg(){
        logF.style.transform = "translateX(-400px)";
        regF.style.transform = "translateX(-400px)";
        ind.style.transform = "translateX(105px)"; // PERFECT POSITION
        bR.style.color = "#2d6a4f";
        bL.style.color = "#94a3b8";
    }

    function sn_login(){
        logF.style.transform = "translateX(0px)";
        regF.style.transform = "translateX(0px)";
        ind.style.transform = "translateX(0px)"; 
        bL.style.color = "#2d6a4f";
        bR.style.color = "#94a3b8";
    }

    // Eye toggle
    function setupEye(inputId, eyeId) {
        var p = document.getElementById(inputId);
        var e = document.getElementById(eyeId);
        e.onclick = function() {
            if(p.type === "password") { p.type = "text"; e.className = "fas fa-eye-slash"; }
            else { p.type = "password"; e.className = "fas fa-eye"; }
        }
    }
    setupEye("Pass", "eye");
    setupEye("Pass-reg", "eye-reg");
</script>
<?php include "footer.php"; ?>

</body>
</html>
   