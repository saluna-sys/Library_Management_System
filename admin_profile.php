<?php
    include "connection.php";
    include "admin_navbar.php";

    // 1. Check if Admin is logged in
    if(!isset($_SESSION['login_admin_username'])) {
        echo "<script>window.location='admin.php';</script>";
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Profile | ShelfNova</title>
    <!-- CSS and Fonts -->
    <link rel="stylesheet" href="admin.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        .admin-profile-page {
            padding: 60px 20px;
            background-color: #f4f7f5;
            min-height: 90vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .profile-card {
            background: white;
            width: 100%;
            max-width: 450px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.05);
            padding: 45px;
            text-align: center;
        }

        .profile-title {
            color: #2d6a4f;
            font-size: 22px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 35px;
        }

        /* --- DYNAMIC AVATAR CIRCLE --- */
        .avatar-container {
            position: relative;
            width: 130px;
            height: 130px;
            margin: 0 auto 35px;
        }

        .avatar-circle {
            width: 130px;
            height: 130px;
            background-color: #2d6a4f; /* Academic Green */
            color: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 55px;
            font-weight: 800;
            box-shadow: 0 8px 20px rgba(45, 106, 79, 0.2);
            border: 4px solid #ffffff;
            overflow: hidden;
            text-transform: uppercase;
        }

        .avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Camera Badge Overlay */
        .camera-badge {
            position: absolute;
            bottom: 5px;
            right: 5px;
            background-color: #808847; /* Olive Gold */
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            cursor: pointer;
            border: 3px solid white;
            transition: 0.3s ease;
        }
        .camera-badge:hover { background-color: #1b4332; transform: scale(1.1); }

        /* --- INFO LIST --- */
        .admin-info-list {
            text-align: left;
            margin: 20px 0 35px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 15px 5px;
            border-bottom: 1px solid #f1f5f9;
        }
        .info-row:last-child { border-bottom: none; }

        .info-label {
            font-weight: 700;
            color: #94a3b8;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            color: #1e293b;
            font-weight: 600;
            font-size: 14px;
        }

        /* --- BUTTONS --- */
        .btn-update-pic {
            background: none;
            border: 1.5px solid #2d6a4f;
            color: #2d6a4f;
            padding: 8px 18px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            margin-top: 15px;
            cursor: pointer;
            display: none; /* Hidden until file selected */
        }

        .btn-security {
            display: block;
            width: 100%;
            background-color: #2d6a4f;
            color: white !important;
            text-decoration: none;
            padding: 14px;
            border-radius: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 13px;
            transition: 0.3s ease;
        }
        .btn-security:hover { background-color: #808847; transform: translateY(-2px); }
    </style>
</head>
<body>

<div class="admin-profile-page">
    <div class="profile-card">
        <h2 class="profile-title">Admin Profile</h2>

        <?php
            // Fetch current admin data from database
            $admin_user = $_SESSION['login_admin_username'];
            $q = mysqli_query($db, "SELECT * FROM admin WHERE username='$admin_user'");
            $row = mysqli_fetch_assoc($q);
            
            // Logic for Initial vs Image
            $initial = strtoupper(substr($row['username'], 0, 1));
            $pic_path = "images/" . $row['pic'];
        ?>

        <!-- Avatar Section -->
        <div class="avatar-container">
            <div class="avatar-circle" id="profileBox">
                <?php 
                    if(!empty($row['pic']) && file_exists($pic_path)) {
                        echo "<img src='$pic_path' class='avatar-img' id='imgPrev'>";
                    } else {
                        echo $initial;
                    }
                ?>
            </div>

            <form method="post" enctype="multipart/form-data">
                <label for="admin-file" class="camera-badge">
                    <i class="fas fa-camera"></i>
                </label>
                <input type="file" name="file" id="admin-file" style="display:none;" onchange="previewSelected();">
                
                <button type="submit" name="upload_admin_pic" id="save-pic-btn" class="btn-update-pic">
                    Confirm New Photo
                </button>
            </form>
        </div>

        <!-- Info List -->
        <div class="admin-info-list">
            <div class="info-row">
                <span class="info-label">Account Type</span>
                <span class="info-value" style="color:#808847;">System Administrator</span>
            </div>
            <div class="info-row">
                <span class="info-label">Username</span>
                <span class="info-value"><?php echo $row['username']; ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Email Address</span>
                <span class="info-value"><?php echo !empty($row['email']) ? $row['email'] : 'Not Set'; ?></span>
            </div>
        </div>

        <!-- Security Action -->
        <a href="admin_update_password.php" class="btn-security">
            <i class="fa-solid fa-shield-halved"></i> Change Security Key
        </a>
    </div>
</div>

<?php
    // IMAGE UPDATE LOGIC
    if(isset($_POST['upload_admin_pic']))
    {
        $pic_name = $_FILES['file']['name'];
        move_uploaded_file($_FILES['file']['tmp_name'], "images/".$pic_name);
        
        // Update session and database
        $_SESSION['pic1'] = $pic_name;
        $q_up = "UPDATE admin SET pic='$pic_name' WHERE username='$admin_user'";
        
        if(mysqli_query($db, $q_up)) {
            echo "<script>alert('Administrator photo updated.'); window.location='admin_profile.php';</script>";
        }
    }
?>

<script>
    function previewSelected() {
        document.getElementById('save-pic-btn').style.display = "inline-block";
        const [file] = document.getElementById('admin-file').files;
        if (file) {
            // If there was no image before, we need to create an img tag
            let box = document.getElementById('profileBox');
            let img = document.getElementById('imgPrev');
            
            if(!img) {
                box.innerHTML = `<img src="${URL.createObjectURL(file)}" class="avatar-img" id="imgPrev">`;
            } else {
                img.src = URL.createObjectURL(file);
            }
        }
    }
</script>


</body>
</html>