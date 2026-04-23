<?php
	include "connection.php";
    include "student_navbar.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile | ShelfNova</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="student_auth.css?v=<?php echo time(); ?>">
    
    <style>
        .profile-page {
            padding: 60px 20px;
            background-color: #f4f7f5; /* Soft Sage Background */
            min-height: 100vh;
            display: flex;
            justify-content: center;
        }

        .profile-card {
            background: white;
            width: 100%;
            max-width: 500px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.05);
            padding: 40px;
            text-align: center;
            height: fit-content;
        }

        .profile-title {
            color: #2d6a4f;
            font-size: 22px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 30px;
        }

        /* --- MODERN AVATAR DESIGN --- */
        .avatar-wrapper {
            position: relative;
            width: 130px;
            height: 130px;
            margin: 0 auto 30px;
        }

        .profile-img-main {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #fff;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        /* Camera Badge */
        .camera-icon-label {
            position: absolute;
            bottom: 5px;
            right: 5px;
            background-color: #808847; /* Olive accent */
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            cursor: pointer;
            border: 3px solid white;
            transition: 0.3s;
        }

        .camera-icon-label:hover {
            background-color: #2d6a4f;
            transform: scale(1.1);
        }

        /* --- INFO LIST (Minimalist replacement for table) --- */
        .info-list {
            text-align: left;
            margin-top: 20px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .info-item:last-child { border-bottom: none; }

        .info-label {
            font-weight: 700;
            color: #94a3b8;
            font-size: 12px;
            text-transform: uppercase;
        }

        .info-value {
            color: #1e293b;
            font-weight: 600;
            font-size: 14px;
        }

        /* --- BUTTONS --- */
        .btn-update-pic {
            background: none;
            border: 1px solid #2d6a4f;
            color: #2d6a4f;
            padding: 8px 15px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 700;
            margin-top: 10px;
            cursor: pointer;
            transition: 0.3s;
            display: none; /* Only show when file is selected */
        }

        .btn-edit-profile {
            display: block;
            width: 95%;
            background-color: #2d6a4f;
            color: white !important;
            text-decoration: none;
            padding: 14px;
            border-radius: 10px;
            font-weight: 700;
            margin-top: 30px;
            transition: 0.3s;
        }

        .btn-edit-profile:hover {
            background-color: #808847;
            transform: translateY(-2px);
        }

		/* --- INITIAL AVATAR STYLING --- */
.avatar-wrapper {
    position: relative;
    width: 120px; /* Equal width and height for a perfect circle */
    height: 120px;
    margin: 0 auto 40px;
}

.profile-initial-circle {
    width: 120px;
    height: 120px;
    background-color: #2d6a4f; /* Your Academic Green */
    color: #ffffff;
    border-radius: 50%; /* Forces the Circle */
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 55px; /* Large, bold letter */
    font-weight: 800;
    box-shadow: 0 8px 20px rgba(45, 106, 79, 0.2);
    border: 4px solid #ffffff;
    text-transform: uppercase;
}

/* The small camera icon badge */
.camera-icon-badge {
    position: absolute;
    bottom: 5px;
    right: 5px;
    background-color: #808847; /* Your Olive accent */
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    cursor: pointer;
    border: 3px solid white;
    transition: 0.3s ease;
}

.camera-icon-badge:hover {
    background-color: #1b4332;
    transform: scale(1.1);
}

/* Info List Alignment Fix */
.info-item {
    display: flex;
    justify-content: space-between;
    padding: 15px 0;
    border-bottom: 1px solid #f1f5f9;
}
    </style>
</head>
<body>

<div class="profile-page">
    <div class="profile-card">
        <h2 class="profile-title">My Profile</h2>

        <?php
            // Fetch student data
            $q = mysqli_query($db, "SELECT * FROM student WHERE studentid='$_SESSION[studentid]';");
            $row = mysqli_fetch_assoc($q);
        ?>

        <!-- Initial-Based Avatar Section -->
<!-- Dynamic Avatar Section -->
<div class="avatar-wrapper">
    <?php 
        // 1. Get the initial from the username
        $user_initial = strtoupper(substr($row['student_username'], 0, 1));
        
        // 2. Define the image path
        $image_path = "images/" . $row['studentpic'];
    ?>
    
    <div class="profile-initial-circle">
        <?php 
            // 3. Logic: If image exists and is not empty, show it. Else show Initial.
            if(!empty($row['studentpic']) && file_exists($image_path)) {
                echo "<img src='$image_path' class='profile-img-main' id='profileDisplay'>";
            } else {
                echo $user_initial;
            }
        ?>
    </div>
    
    <!-- Camera icon badge for uploading -->
    <form method="post" enctype="multipart/form-data">
        <label for="file-input" class="camera-icon-badge">
            <i class="fas fa-camera"></i>
        </label>
        <input type="file" name="file" id="file-input" style="display:none;" onchange="this.form.submit();">
        <input type="hidden" name="profileimg"> 
    </form>
</div>
        <!-- Info List -->
        <div class="info-list">
            <div class="info-item">
                <span class="info-label">Username</span>
                <span class="info-value"><?php echo $row['student_username']; ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Full Name</span>
                <span class="info-value"><?php echo $row['FullName']; ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Email</span>
                <span class="info-value"><?php echo $row['Email']; ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Phone</span>
                <span class="info-value"><?php echo $row['PhoneNumber']; ?></span>
            </div>
        </div>

        <!-- Edit Button -->
        <a href="edit_profile.php?ed=<?php echo $row['studentid'];?>" class="btn-edit-profile">
            <i class="fas fa-user-edit"></i> Edit Profile Details
        </a>
    </div>
</div>

<?php
    // Original PHP update logic (Untouched)
    if(isset($_POST['profileimg']))
    {
        $pic_name = $_FILES['file']['name'];
        move_uploaded_file($_FILES['file']['tmp_name'], "images/".$pic_name);
        $_SESSION['pic'] = $pic_name;
        $q1 = "UPDATE student SET studentpic='$pic_name' WHERE studentid='".$_SESSION['studentid']."';";
        if(mysqli_query($db, $q1))
        {
            echo "<script>alert('Profile picture updated successfully.'); window.location='profile.php';</script>";
        }
    }
?>

<script>
    // Logic to show the "Save New Photo" button only after a user picks a file
    function showBtn() {
        document.getElementById('update-btn').style.display = "inline-block";
        
        // Optional: Preview the image before saving
        const [file] = document.getElementById('file-input').files;
        if (file) {
            document.getElementById('profileDisplay').src = URL.createObjectURL(file);
        }
    }
</script>

</body>
</html>