<?php
	include "connection.php";
    include "admin_navbar.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Security | ShelfNova</title>
    <!-- Use specific versioning to clear cache -->
    <link rel="stylesheet" href="admin.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* --- ADMIN SECURITY PAGE UI --- */
        .security-wrapper {
            padding: 80px 20px;
            background-color: #f4f7f5; /* Light Sage background */
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

        .security-icon {
            font-size: 50px;
            color: #808847; /* Olive Gold */
            margin-bottom: 15px;
        }

        .security-header h2 {
            color: #2d6a4f; /* Academic Green */
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

        /* Eye Icon Positioning */
        .toggle-eye {
            position: absolute;
            right: 15px;
            top: 38px;
            color: #94a3b8;
            cursor: pointer;
            transition: 0.3s;
        }
        .toggle-eye:hover { color: #2d6a4f; }

        .btn-update-security {
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
            margin-top: 25px !important;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: 0.3s !important;
        }

        .btn-update-security:hover {
            background-color: #1b4332 !important;
            transform: translateY(-2px);
        }

        .dashboard-link {
            display: block;
            margin-top: 25px;
            font-size: 13px;
            color: #94a3b8;
            text-decoration: none;
            font-weight: 600;
        }
        .dashboard-link:hover { color: #808847; }
    </style>
</head>
<body>

    <div class="security-wrapper">
        <div class="security-card">
            
            <div class="security-header">
                <div class="security-icon"><i class="fa-solid fa-user-lock"></i></div>
                <h2>Security Update</h2>
                <p>Change your administrator access credentials.</p>
            </div>

            <form action="" method="post">
                <div class="sn-field-group">
                    <label>Admin Username</label>
                    <input type="text" name="admin_username" class="sn-input" value="<?php echo $_SESSION['login_admin_username']; ?>" readonly>
                </div>

                <div class="sn-field-group">
                    <label>New Secure Password</label>
                    <input type="password" name="password" id="update2" class="sn-input" placeholder="••••••••" required>
                    <span class="toggle-eye"><i class="fas fa-eye" id="eye-update2"></i></span>
                </div>

                <button type="submit" class="btn-update-security" name="change">Confirm Password Change</button>
                
                <a href="admin_dashboard.php" class="dashboard-link">Cancel and return to dashboard</a>
            </form>
        </div>
    </div>

    <?php
        // YOUR ORIGINAL PHP LOGIC (UNTOUCHED)
		if(isset($_POST['change']))
		{
            $new_pass = mysqli_real_escape_string($db, $_POST['password']);
            $current_admin = $_SESSION['login_admin_username'];

			$res = mysqli_query($db, "SELECT * FROM `admin` WHERE username='$current_admin';");
			$count = mysqli_num_rows($res);

			if($count == 0)
			{
				echo "<script>alert('Error: Admin session not recognized.');</script>";
			}
			else
			{
				if(mysqli_query($db, "UPDATE admin SET Password='$new_pass' WHERE username='$current_admin';"))
				{
					echo "<script>alert('Admin password has been successfully updated.'); window.location='admin_dashboard.php';</script>";
				}
			}
		}
	?>

    <!-- Password Visibility Script -->
    <script>
        var passInput = document.getElementById("update2");
        var toggleIcon = document.getElementById("eye-update2");
        
        toggleIcon.addEventListener("click", function() {
            if(passInput.type === "password"){
                passInput.type = "text";
                toggleIcon.classList.replace("fa-eye", "fa-eye-slash");
            } else {
                passInput.type = "password";
                toggleIcon.classList.replace("fa-eye-slash", "fa-eye");
            }
        });
    </script>


</body>
</html>