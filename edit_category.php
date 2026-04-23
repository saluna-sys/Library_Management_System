<?php
	include "connection.php";
    include "admin_navbar.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Category | ShelfNova</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="admin.css?v=<?php echo time(); ?>">
    
    <style>
        /* --- SHELFNOVA EDIT CATEGORY UI --- */
        .edit-wrapper {
            padding: 80px 20px;
            background-color: #f4f7f5; /* Light Sage Background */
            min-height: 90vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .edit-card {
            background: white;
            width: 100%;
            max-width: 420px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.05);
            padding: 40px;
            text-align: center;
        }

        .edit-header i {
            font-size: 40px;
            color: #808847; /* Olive Gold icon */
            margin-bottom: 15px;
        }

        .edit-header h2 {
            color: #2d6a4f; /* Academic Green */
            font-size: 22px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0;
        }

        .edit-header p {
            font-size: 13px;
            color: #94a3b8;
            margin-bottom: 30px;
        }

        /* ID Badge Style */
        .id-badge {
            display: inline-block;
            background: #808847;
            color: white;
            padding: 3px 12px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 25px;
        }

        /* --- FORM STYLING --- */
        .sn-field-group {
            margin-bottom: 25px;
            text-align: left;
        }

        .sn-field-group label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #2d6a4f;
            text-transform: uppercase;
            margin-bottom: 8px;
            margin-left: 5px;
        }

        /* Reset and Modernize Input */
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

        .btn-update-cat {
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

        .btn-update-cat:hover {
            background-color: #1b4332 !important;
            transform: translateY(-2px);
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

    <div class="edit-wrapper">
        <div class="edit-card">
            
            <?php
                // ORIGINAL PHP: Fetch category data
                $id = mysqli_real_escape_string($db, $_GET['ed']);
                $q = "SELECT * FROM category WHERE categoryid=$id";
                $res = mysqli_query($db, $q) or die(mysqli_error($db));
                
                while($row = mysqli_fetch_assoc($res))
                {
                    $categoryid = $row['categoryid'];
                    $categoryname = $row['categoryname'];
                }		
            ?>

            <div class="edit-header">
                <i class="fa-solid fa-layer-group"></i>
                <h2>Update Category</h2>
                <p>Modify the book classification details</p>
                <div class="id-badge">Category ID: #<?php echo $categoryid; ?></div>
            </div>

            <form action="" method="post">
                <div class="sn-field-group">
                    <label>Category Name</label>
                    <input type="text" name="categoryname" class="sn-input" value="<?php echo htmlspecialchars($categoryname); ?>" required>
                </div>

                <button type="submit" class="btn-update-cat" name="submit">Save Changes</button>
                
                <a href="manage_categories.php" class="back-link">Cancel and Return</a>
            </form>
        </div>
    </div>

    <?php
        // ORIGINAL PHP: Update logic
        if(isset($_POST['submit']))
        {
            $new_name = mysqli_real_escape_string($db, $_POST['categoryname']);

            $q1 = "UPDATE category SET categoryname='$new_name' WHERE categoryid=".$id.";";
            if(mysqli_query($db, $q1))
            {
                echo "<script>alert('Category updated successfully.'); window.location='manage_categories.php';</script>";
            }
        }
	?>

</body>
</html>