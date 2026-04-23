<?php
	include "connection.php";
    include "student_navbar.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Security | ShelfNova</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="student_auth.css?v=<?php echo time(); ?>">
    
    <style>
        .security-wrapper {
            padding: 80px 20px;
            background-color: #f4f7f5;
            min-height: 90vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .security-card {
            background: white;
            width: 100%;
            max-width: 420px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.05);
            padding: 40px;
            text-align: center;
        }

        .security-header i {
            font-size: 40px;
            color: #808847; /* Olive gold icon */
            margin-bottom: 15px;
        }

        .security-header h2 {
            color: #2d6a4f;
            font-size: 22px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0;
        }

        .security-header p {
            font-size: 13px;
            color: #94a3b8;
            margin: 10px 0 30px;
        }

        /* --- FORM STYLING --- */
        .sn-field-group {
            margin-bottom: 20px;
            position: relative;
            text-align: left;
        }

        .sn-field-group label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            color: #2d6a4f;
            text-transform: uppercase;
            margin-bottom: 8px;
            margin-left: 5px;
        }

        .sn-input {
            all: unset !important;
            display: block !important;
            box-sizing: border-box !important;
            width: 100% !important;
            height: 50px !important;
            background-color: #f8fafc !important;
            border: 1.5px solid #e2e8f0 !important;
            border-radius: 10px !important;
            padding: 0 15px !important;
            font-family: 'Poppins', sans-serif !important;
            font-size: 14px !important;
            color: #334155 !important;
            transition: 0.3s ease !important;
        }

        .sn-input:focus {
            border-color: #2d6a4f !important;
            background-color: #ffffff !important;
            box-shadow: 0 0 0 4px rgba(45, 106, 79, 0.1) !important;
        }

        .eye-icon {
            position: absolute;
            right: 15px;
            top: 38px;
            color: #94a3b8;
            cursor: pointer;
            transition: 0.3s;
        }

        .eye-icon:hover { color: #2d6a4f; }

        .btn-secure-update {
            all: unset !important;
            display: block !important;
            width: 100% !important;
            box-sizing: border-box !important;
            background-color: #2d6a4f !important;
            color: white !important;
            text-align: center !important;
            padding: 15px !important;
            border-radius: 10px !important;
            font-weight: 700 !important;
            cursor: pointer !important;
            margin-top: 10px !important;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: 0.3s !important;
        }

        .btn-secure-update:hover {
            background-color: #1b4332 !important;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .back-link {
            display: block;
            margin-top: 25px;
            font-size: 13px;
            color: #94a3b8;
            text-decoration: none;
            font-weight: 600;
        }
        .back-link:hover { color: #808847; }
    </style>
</head>
<body>

    <div class="security-wrapper">
        <div class="security-card">
            
            <div class="security-header">
                <i class="fa-solid fa-shield-halved"></i>
                <h2>Change Password</h2>
                <br>
            </div>

            <form action="" method="post">
                <div class="sn-field-group">
                    <label>Username</label>
                    <input type="text" name="student_username" class="sn-input" value="<?php echo $_SESSION['login_student_username']; ?>" required readonly>
                </div>

                <div class="sn-field-group">
                    <label>Confirm Email</label>
                    <input type="email" name="Email" class="sn-input" placeholder="Enter registered email" required>
                </div>

                <div class="sn-field-group">
                    <label>New Password</label>
                    <input type="password" name="password" id="update" class="sn-input" placeholder="••••••••" required>
                    <span class="eye-icon"><i class="fas fa-eye" id="eye-toggle"></i></span>
                </div>

                <button type="submit" class="btn-secure-update" name="change">Update Password</button>
                
                <a href="profile.php" class="back-link">Return to Profile</a>
            </form>
        </div>
    </div>

    <?php
        // ORIGINAL PHP LOGIC: Handled with minor security improvements
		if(isset($_POST['change']))
		{
            $email = mysqli_real_escape_string($db, $_POST['Email']);
            $new_pass = mysqli_real_escape_string($db, $_POST['password']);
            $current_user = $_SESSION['login_student_username'];

			$res = mysqli_query($db, "SELECT * FROM `student` WHERE student_username='$current_user' AND Email='$email';");
			$count = mysqli_num_rows($res);

			if($count == 0)
			{
				echo "<script>alert('The provided email does not match our records for this account.');</script>";
			}
			else
			{
				if(mysqli_query($db, "UPDATE student SET Password='$new_pass' WHERE student_username='$current_user' AND Email='$email';"))
				{
					echo "<script>alert('Your password has been successfully updated.'); window.location='profile.php';</script>";
				}
			}
		}
	?>

    <script>
        // Integrated Password Eye Toggle
        const passInput = document.getElementById("update");
        const toggleBtn = document.getElementById("eye-toggle");

        toggleBtn.addEventListener("click", function() {
            if (passInput.type === "password") {
                passInput.type = "text";
                toggleBtn.classList.replace("fa-eye", "fa-eye-slash");
            } else {
                passInput.type = "password";
                toggleBtn.classList.replace("fa-eye-slash", "fa-eye");
            }
        });
    </script>

</body>
</html>