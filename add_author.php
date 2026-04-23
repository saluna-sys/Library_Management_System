<?php
	include "connection.php";
    include "admin_navbar.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Author | ShelfNova</title>
    <!-- Use standard Poppins/Montserrat fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="admin.css?v=<?php echo time(); ?>">
    
    <style>
        /* --- SHELFNOVA ADD AUTHOR UI --- */
        .admin-page-wrapper {
            padding: 80px 20px;
            background-color: #f4f7f5; /* Soft Sage Background */
            min-height: 85vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .auth-card {
            background: white;
            width: 100%;
            max-width: 420px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.05);
            padding: 40px;
            text-align: center;
        }

        .auth-icon {
            font-size: 45px;
            color: #808847; /* Olive Gold */
            margin-bottom: 15px;
        }

        .auth-header h2 {
            color: #2d6a4f; /* Academic Green */
            font-size: 22px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0;
        }

        .auth-header p {
            font-size: 13px;
            color: #94a3b8;
            margin: 10px 0 30px;
        }

        /* --- FORM STYLING --- */
        .sn-field-group {
            margin-bottom: 25px;
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

        .btn-add-author {
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

        .btn-add-author:hover {
            background-color: #1b4332 !important;
            transform: translateY(-2px);
        }

        .manage-link {
            display: block;
            margin-top: 25px;
            font-size: 13px;
            color: #94a3b8;
            text-decoration: none;
            font-weight: 600;
        }
        .manage-link:hover { color: #808847; }
    </style>
</head>
<body>

    <div class="admin-page-wrapper">
        <div class="auth-card">
            
            <div class="auth-header">
                <div class="auth-icon"><i class="fa-solid fa-pen-nib"></i></div>
                <h2>Add New Author</h2>
                <p>Register a new author into the library system.</p>
            </div>

            <form action="" method="post">
                <div class="sn-field-group">
                    <label>Author Full Name</label>
                    <input type="text" name="authorname" class="sn-input" placeholder="e.g. George R.R. Martin" required>
                </div>

                <button type="submit" class="btn-add-author" name="add">Save Author</button>
                
                <a href="manage_authors.php" class="manage-link">View all authors</a>
            </form>
        </div>
    </div>

    <?php
    if(isset($_POST['add']))
    {
        // 1. Sanitize the input
        $authorname = mysqli_real_escape_string($db, $_POST['authorname']);

        // 2. FIXED QUERY: Specifically name the column 'authorname'
        // This allows the database to handle the 'authorid' automatically
        $query = "INSERT INTO authors (authorname) VALUES ('$authorname')";
        
        if(mysqli_query($db, $query)) {
            echo "<script>
                    alert('Author added successfully.'); 
                    window.location='manage_authors.php';
                  </script>";
        } else {
            // This will alert the exact error if it fails (very helpful for Linux debugging)
            $error_msg = mysqli_error($db);
            echo "<script>alert('SQL Error: $error_msg');</script>";
        }
    }
?>

</body>
</html>