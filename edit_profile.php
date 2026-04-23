<?php
	include "connection.php";
    include "student_navbar.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile | ShelfNova</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="student_auth.css?v=<?php echo time(); ?>">
    
    <style>
        /* --- EDIT PROFILE UI --- */
        .edit-wrapper {
            padding: 60px 20px;
            background-color: #f4f7f5;
            min-height: 90vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .edit-card {
            background: white;
            width: 100%;
            max-width: 450px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.05);
            padding: 40px;
        }

        .edit-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .edit-header h2 {
            color: #2d6a4f;
            font-size: 22px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0;
        }

        .edit-header p {
            font-size: 13px;
            color: #94a3b8;
            margin-top: 5px;
        }

        /* --- STYLED FORM --- */
        .sn-form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .sn-form-group label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #2d6a4f;
            text-transform: uppercase;
            margin-bottom: 8px;
            margin-left: 5px;
        }

        /* Unique Input Styling to prevent CSS conflicts */
        .sn-input-field {
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

        .sn-input-field:focus {
            border-color: #2d6a4f !important;
            background-color: #ffffff !important;
            box-shadow: 0 0 0 4px rgba(45, 106, 79, 0.1) !important;
        }

        /* Read-only Student ID Badge */
        .id-badge {
            display: inline-block;
            background: #808847;
            color: white;
            padding: 2px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 700;
        }

        /* Update Button */
        .btn-update-now {
            all: unset !important;
            display: block !important;
            width: 100% !important;
            box-sizing: border-box !important;
            background-color: #2d6a4f !important;
            color: white !important;
            text-align: center !important;
            padding: 14px !important;
            border-radius: 10px !important;
            font-weight: 700 !important;
            cursor: pointer !important;
            margin-top: 10px !important;
            transition: 0.3s !important;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-update-now:hover {
            background-color: #1b4332 !important;
            transform: translateY(-2px);
        }

        .cancel-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: #94a3b8;
            text-decoration: none;
            font-weight: 600;
        }
        .cancel-link:hover { color: #808847; }
    </style>
</head>
<body>

    <div class="edit-wrapper">
        <div class="edit-card">
            
            <div class="edit-header">
                <h2>Edit Profile</h2>
                <p>Update your personal information below</p>
            </div>

            <?php
                // ORIGINAL PHP: Fetch student data
                $studentid = $_SESSION['studentid'];
                $q = "SELECT * FROM student WHERE studentid='$_SESSION[studentid]'";
                $res = mysqli_query($db, $q);

                while($row = mysqli_fetch_assoc($res)) {
                    $student_username = $row['student_username'];
                    $FullName = $row['FullName'];
                    $Email = $row['Email'];
                    $PhoneNumber = $row['PhoneNumber'];
                }
            ?>

            <form action="" method="post">
                
                <div class="sn-form-group" style="text-align:center; border-bottom: 1px solid #f1f5f9; padding-bottom: 15px;">
                    <span style="font-size: 13px; color: #64748b;">Student ID: </span>
                    <span class="id-badge">#<?php echo $studentid; ?></span>
                </div>

                <div class="sn-form-group">
                    <label>Username</label>
                    <input type="text" name="student_username" class="sn-input-field" value="<?php echo $student_username; ?>" required>
                </div>

                <div class="sn-form-group">
                    <label>Full Name</label>
                    <input type="text" name="FullName" class="sn-input-field" value="<?php echo $FullName; ?>" required>
                </div>

                <div class="sn-form-group">
                    <label>Email Address</label>
                    <input type="email" name="Email" class="sn-input-field" value="<?php echo $Email; ?>" required>
                </div>

                <div class="sn-form-group">
                    <label>Phone Number</label>
                    <input type="text" name="PhoneNumber" class="sn-input-field" value="<?php echo $PhoneNumber; ?>" required>
                </div>

                <button type="submit" class="btn-update-now" name="change">Save Changes</button>
                
                <a href="profile.php" class="cancel-link">Cancel and go back</a>
            </form>
        </div>
    </div>

    <?php
        // ORIGINAL PHP: Handle Update Logic
		if(isset($_POST['change']))
		{
            $student_username = mysqli_real_escape_string($db, $_POST['student_username']);
			$FullName = mysqli_real_escape_string($db, $_POST['FullName']);
			$Email = mysqli_real_escape_string($db, $_POST['Email']);
			$PhoneNumber = mysqli_real_escape_string($db, $_POST['PhoneNumber']);
            
			$_SESSION['login_student_username'] = $student_username;

			$q1 = "UPDATE student SET student_username='$student_username', FullName='$FullName', Email='$Email', PhoneNumber='$PhoneNumber'
			      WHERE studentid='".$_SESSION['studentid']."';";
                  
			if(mysqli_query($db, $q1))
            {
                echo "<script>alert('Profile updated successfully.'); window.location='profile.php';</script>";
            }
		}
	?>

</body>
</html>