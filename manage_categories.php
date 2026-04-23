<?php
	include "connection.php";
    include "admin_navbar.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Categories | ShelfNova</title>
    <!-- Use the exact same design libraries -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="admin.css?v=<?php echo time(); ?>">
    <style>
        /* --- CENTERED PAGE LAYOUT --- */
        .admin-page-wrapper { 
            padding: 60px 0; 
            background-color: #f4f7f5; 
            min-height: 100vh;
        }

        .container-slim {
            width: 100%;
            max-width: 1400px; /* Slimmer for better readability */
            margin: 0 auto;
            padding: 0 30px;
        }

        /* --- MODERN SEARCH BAR --- */
        .admin-search-container {
            width: 100%;
            max-width: 500px;
            margin: 0 auto 50px;
            display: flex;
            gap: 10px;
            background: white;
            padding: 12px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        }

        .admin-search-container input {
            flex: 1;
            padding: 10px 15px;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            outline: none;
            font-family: inherit;
        }

        .admin-search-container button {
            background-color: #2d6a4f;
            color: white;
            border: none;
            padding: 0 20px;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }
        .admin-search-container button:hover { background-color: #808847; }

        /* --- MINIMALIST TABLE --- */
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
            border-bottom: 2px solid #f1f5f9;
        }

        td {
            padding: 15px 20px;
            border-bottom: 1px solid #f8fafc;
            font-size: 14px;
            color: #1e293b;
            vertical-align: middle;
        }

        tr:hover td { background-color: #f9fbf9; }

        .page-title {
            color: #2d6a4f;
            font-weight: 800;
            margin-bottom: 25px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 22px;
        }

        /* --- ACTION LINKS --- */
        .btn-edit {
            color: #2d6a4f;
            text-decoration: none;
            font-weight: 700;
            font-size: 12px;
            margin-right: 20px;
            transition: 0.3s;
        }
        .btn-edit:hover { color: #808847; }

        .btn-delete {
            color: #ef4444;
            text-decoration: none;
            font-weight: 700;
            font-size: 12px;
            background: none;
            border: none;
            cursor: pointer;
            transition: 0.3s;
        }
        .btn-delete:hover { color: #991b1b; }

        .empty-msg { text-align: center; padding: 40px; color: #94a3b8; }
    </style>
</head>
<body>

<div class="admin-page-wrapper">
    <div class="container-slim">
        
        <!-- SEARCH AREA -->
        <div class="admin-search-container">
            <form action="" method='post' style="display:flex; width:100%; gap:10px;">
                <input type="search" name='search' placeholder='Search Category Name...' required>
                <button type='submit' name='submit'><i class="fas fa-search"></i></button>
            </form>
        </div>

        <h2 class="page-title">Book Categories</h2>

        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Category Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    // PHP Logic: Search vs All
                    if(isset($_POST['submit'])) {
                        $search = mysqli_real_escape_string($db, $_POST['search']);
                        $q = mysqli_query($db, "SELECT * FROM category WHERE categoryname LIKE '%$search%'");
                    } else {
                        $q = mysqli_query($db, "SELECT * FROM category ORDER BY categoryid ASC");
                    }

                    if(mysqli_num_rows($q) > 0) {
                        while($row = mysqli_fetch_assoc($q)) {
                ?>
                        <tr>
                            <td style="color:#94a3b8; font-weight:700;">#<?php echo $row['categoryid']; ?></td>
                            <td style="font-weight:600;"><?php echo htmlspecialchars($row['categoryname']); ?></td>
                            <td>
                                <a href="edit_category.php?ed=<?php echo $row['categoryid'];?>" class="btn-edit">
                                    <i class="fas fa-edit"></i> EDIT
                                </a>
                                <a href="delete_category.php?del=<?php echo $row['categoryid'];?>" 
                                   class="btn-delete" 
                                   onclick="return confirm('Delete this category? This might affect books under this category.')">
                                    <i class="fas fa-trash-alt"></i> DELETE
                                </a>
                            </td>
                        </tr>
                <?php 
                        }
                    } else {
                        echo "<tr><td colspan='3' class='empty-msg'>No categories found.</td></tr>";
                    }
                ?>
                </tbody>
            </table>
        </div>

        <div style="margin-top: 30px;">
            <a href="add_category.php" style="color: #808847; text-decoration:none; font-weight:700; font-size:14px;">
                <i class="fa-solid fa-folder-plus"></i> Add New Category
            </a>
        </div>

    </div>
</div>

</body>
</html>