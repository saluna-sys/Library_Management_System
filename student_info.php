<?php
	include "connection.php";
    include "admin_navbar.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Records | ShelfNova</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="admin.css?php echo time(); ?>">
    <style>
        /* --- CENTERED PAGE LAYOUT --- */
        .admin-page-wrapper { 
            padding: 60px 0; 
            background-color: #f4f7f5; 
            min-height: 100vh;
        }

        .container-slim {
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 30px;
        }

        /* --- MODERN SEARCH BAR --- */
        .admin-search-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto 50px;
            display: flex;
            background: white;
            padding: 15px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        }

        .admin-search-container input {
            flex: 1;
            padding: 12px 15px;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            outline: none;
            font-family: inherit;
        }

        .admin-search-container button {
            background-color: #2d6a4f;
            color: white;
            border: none;
            padding: 0 25px;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }
        .admin-search-container button:hover { background-color: #808847; }

        /* --- MINIMALIST GHOST TABLE --- */
        .table-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.03);
            border: 1px solid #eef2f0;
        }

        table { width: 100%; border-collapse: collapse; }
        
        th {
            background-color: #f8fafc;
            padding: 18px 20px;
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 2.5px solid #f1f5f9;
        }

        td {
            padding: 15px 20px;
            border-bottom: 1px solid #f8fafc;
            font-size: 14px;
            color: #1e293b;
            vertical-align: middle;
        }

        tr:hover td { background-color: #f9fbf9; }

        /* --- AVATAR CIRCLE LOGIC --- */
        .student-profile {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .student-initial-circle {
            width: 42px;
            height: 42px;
            background-color: #2d6a4f; /* Academic Green */
            color: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 18px;
            box-shadow: 0 4px 10px rgba(45, 106, 79, 0.15);
            border: 2px solid #ffffff;
            flex-shrink: 0;
            overflow: hidden; /* Clips the image into a circle */
        }

        .student-img-list {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .student-name-box b {
            display: block;
            font-size: 15px;
            color: #1e293b;
            line-height: 1.2;
        }

        .student-name-box span {
            font-size: 11px;
            color: #808847; /* Olive Gold */
            font-weight: 700;
        }

        .page-title {
            color: #2d6a4f;
            font-weight: 800;
            margin-bottom: 25px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 24px;
        }

        .empty-msg {
            text-align: center;
            padding: 60px;
            color: #94a3b8;
        }
    </style>
</head>
<body>

<div class="admin-page-wrapper">
    <div class="container-slim">
        
        <!-- SEARCH BAR AREA -->
        <div class="admin-search-container">
            <form action="" method="post" style="display:flex; width:100%; gap:10px;">
                <input type="search" name="search" placeholder="Search by ID, Name, Email, or Phone..." required>
                <button type="submit" name="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>

        <h2 class="page-title">Registered Students</h2>

        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>Student Identity</th>
                        <th>Email Address</th>
                        <th>Phone Number</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    if(isset($_POST['submit'])) {
                        $search = mysqli_real_escape_string($db, $_POST['search']);
                        $q_str = "SELECT * FROM `student` 
                                  WHERE (studentid LIKE '%$search%') 
                                  OR (FullName LIKE '%$search%') 
                                  OR (Email LIKE '%$search%') 
                                  OR (PhoneNumber LIKE '%$search%')
                                  ORDER BY studentid ASC";
                        $q = mysqli_query($db, $q_str);
                    } else {
                        $q = mysqli_query($db, "SELECT * FROM `student` ORDER BY studentid ASC");
                    }

                    if($q && mysqli_num_rows($q) > 0) {
                        while($row = mysqli_fetch_assoc($q)) {
                            // Extract data for logic
                            $initial = strtoupper(substr($row['FullName'], 0, 1));
                            $pic_path = "images/" . $row['studentpic'];
                ?>
                    <tr>
                        <td>
                            <div class="student-profile">
                                <div class="student-initial-circle">
                                    <?php 
                                        if(!empty($row['studentpic']) && file_exists($pic_path)) {
                                            echo "<img src='$pic_path' class='student-img-list' alt='User'>";
                                        } else {
                                            echo $initial;
                                        }
                                    ?>
                                </div>
                                <div class="student-name-box">
                                    <b><?php echo htmlspecialchars($row['FullName']); ?></b>
                                    <span>ID: #<?php echo $row['studentid']; ?></span>
                                </div>
                            </div>
                        </td>
                        <td style="color: #64748b;"><?php echo htmlspecialchars($row['Email']); ?></td>
                        <td><?php echo htmlspecialchars($row['PhoneNumber']); ?></td>
                        <td>
                            <a href="student_details.php?id=<?php echo $row['studentid']; ?>" style="color:#2d6a4f; text-decoration:none; font-weight:700; font-size:12px;">
                                <i class="fa-solid fa-circle-info"></i> DETAILS
                            </a>
                        </td>
                    </tr>
                <?php 
                        }
                    } else {
                        echo "<tr><td colspan='4' class='empty-msg'>No students found.</td></tr>";
                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


</body>
</html>