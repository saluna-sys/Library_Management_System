<?php
	include "connection.php";
    include "admin_navbar.php";
    $res1=mysqli_query($db,"SELECT * FROM authors");
	$res2=mysqli_query($db,"SELECT * FROM category");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Book | ShelfNova</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="admin.css?v=<?php echo time(); ?>">
    
    <style>
        /* --- SHELFNOVA EDIT BOOK UI --- */
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
            max-width: 650px; /* Slightly wider for 2-column form */
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

        /* --- IMAGE PREVIEW AREA --- */
        .preview-box {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }

        .current-cover {
            width: 100px;
            height: 140px;
            object-fit: cover;
            border-radius: 8px;
            border: 3px solid #fff;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        /* --- FORM GRID --- */
        .sn-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            text-align: left;
        }

        .full-width { grid-column: span 2; }

        .sn-field label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            color: #2d6a4f;
            text-transform: uppercase;
            margin-bottom: 6px;
            margin-left: 2px;
        }

        .sn-input, .sn-select {
            all: unset !important;
            display: block !important;
            box-sizing: border-box !important;
            width: 100% !important;
            height: 48px !important;
            background-color: #f8fafc !important;
            border: 1.5px solid #e2e8f0 !important;
            border-radius: 10px !important;
            padding: 0 15px !important;
            font-family: 'Poppins', sans-serif !important;
            font-size: 13px !important;
            color: #334155 !important;
            transition: 0.3s ease !important;
        }

        .sn-input:focus, .sn-select:focus {
            border-color: #2d6a4f !important;
            background-color: #ffffff !important;
            box-shadow: 0 0 0 4px rgba(45, 106, 79, 0.1) !important;
        }

        .id-badge {
            background: #808847;
            color: white;
            padding: 2px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 700;
            margin-top: 5px;
            display: inline-block;
        }

        .btn-save-edit {
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
        }

        .btn-save-edit:hover { background-color: #1b4332 !important; transform: translateY(-2px); }
    </style>
</head>
<body>

    <div class="edit-wrapper">
        <div class="edit-card">
            
            <?php
                $id = mysqli_real_escape_string($db, $_GET['ed']);
                $q = "SELECT books.*, authors.authorid, authors.authorname, category.categoryid, category.categoryname 
                      FROM `books`
                      JOIN `authors` ON authors.authorid=books.authorid 
                      JOIN `category` ON category.categoryid=books.categoryid 
                      WHERE bookid=$id";
                $res_data = mysqli_query($db, $q) or die(mysqli_error($db));
                
                while($row = mysqli_fetch_assoc($res_data)) {
                    $bookid=$row['bookid'];
                    $pic=$row['bookpic'];
                    $bookname=$row['bookname'];
                    $authorid=$row['authorid'];
                    $categoryid=$row['categoryid'];
                    $authorname=$row['authorname'];
                    $categoryname=$row['categoryname'];
                    $ISBN=$row['ISBN'];
                    $price=$row['price'];
                    $quantity=$row['quantity'];
                    $status=$row['status'];
                }
            ?>

            <div class="edit-header">
                <h2>Edit Book Details</h2>
                <div class="id-badge">Book ID: #<?php echo $bookid; ?></div>
            </div>

            <div class="preview-box">
                <img src="images/<?php echo $pic; ?>" class="current-cover" alt="Current Cover">
            </div>

            <form action="" method="post" enctype="multipart/form-data">
                <div class="sn-grid">
                    
                    <div class="sn-field full-width">
                        <label>Book Title</label>
                        <input type="text" name="bookname" class="sn-input" value="<?php echo $bookname; ?>" required>
                    </div>

                    <div class="sn-field">
                        <label>Author</label>
                        <select class="sn-select" name="authorname">
                            <option value="<?php echo $authorid;?>"><?php echo $authorname;?></option>
                            <?php while($row1=mysqli_fetch_array($res1)) {
                                if($authorname != $row1['authorname']) {
                                    echo "<option value='".$row1['authorid']."'>".$row1['authorname']."</option>";
                                }
                            } ?>        
                        </select>
                    </div>

                    <div class="sn-field">
                        <label>Category</label>
                        <select class="sn-select" name="categoryname">
                            <option value="<?php echo $categoryid;?>"><?php echo $categoryname;?></option>
                            <?php while($row2=mysqli_fetch_array($res2)) {
                                if($categoryname != $row2['categoryname']) {
                                    echo "<option value='".$row2['categoryid']."'>".$row2['categoryname']."</option>";
                                }
                            } ?>
                        </select>
                    </div>

                    <div class="sn-field">
                        <label>ISBN</label>
                        <input type="text" name="ISBN" class="sn-input" value="<?php echo $ISBN; ?>" required>
                    </div>

                    <div class="sn-field">
                        <label>Price (Rs.)</label>
                        <input type="text" name="price" class="sn-input" value="<?php echo $price; ?>" required>
                    </div>

                    <div class="sn-field">
                        <label>Quantity</label>
                        <input type="number" name="quantity" class="sn-input" value="<?php echo $quantity; ?>" required>
                    </div>

                   <div class="sn-field">
    <label>Current Status</label>
    <select name="status" class="sn-select">
        <option value="Available" <?php if($status == 'Available') echo 'selected'; ?>>Available</option>
        <option value="Not Available" <?php if($status == 'Not Available') echo 'selected'; ?>>Not Available</option>
    </select>
</div>

                    <div class="sn-field full-width">
                        <label>Update Cover Image (Optional)</label>
                        <input type="file" name="file" style="font-size: 12px; color: #64748b;">
                    </div>

                </div>

                <button type="submit" class="btn-save-edit" name="submit">Update Library Record</button>
                <p style="text-align:center; margin-top:20px;">
                    <a href="manage_books.php" style="color:#94a3b8; font-size:13px; text-decoration:none;">Cancel and Exit</a>
                </p>
            </form>
        </div>
    </div>

    <?php
        // ORIGINAL PHP UPDATE LOGIC (KEEPING YOUR EXACT VARIABLE NAMES)
        if(isset($_POST['submit'])) {
            $bookname = mysqli_real_escape_string($db, $_POST['bookname']);
            $authorname = mysqli_real_escape_string($db, $_POST['authorname']);
            $categoryname = mysqli_real_escape_string($db, $_POST['categoryname']);
            $ISBN = mysqli_real_escape_string($db, $_POST['ISBN']);
            $price = mysqli_real_escape_string($db, $_POST['price']);
            $quantity = mysqli_real_escape_string($db, $_POST['quantity']);
            $status = mysqli_real_escape_string($db, $_POST['status']);

            if(!empty($_FILES["file"]["name"])) {
                move_uploaded_file($_FILES['file']['tmp_name'], "images/".$_FILES['file']['name']);
                $new_pic = $_FILES['file']['name'];
                $q_up = "UPDATE books SET bookpic='$new_pic', bookname='$bookname', authorid='$authorname', categoryid='$categoryname', ISBN='$ISBN', price='$price', quantity='$quantity', status='$status' WHERE bookid=$id";
            } else {
                $q_up = "UPDATE books SET bookname='$bookname', authorid='$authorname', categoryid='$categoryname', ISBN='$ISBN', price='$price', quantity='$quantity', status='$status' WHERE bookid=$id";
            }

            if(mysqli_query($db, $q_up)) {
                echo "<script>alert('Book details updated successfully!'); window.location='manage_books.php';</script>";
            }
        }
    ?>

</body>
</html>