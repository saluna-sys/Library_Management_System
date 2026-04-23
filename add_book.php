<?php
	include "connection.php";
    include "admin_navbar.php";
    $res = mysqli_query($db,"SELECT * FROM category");
	$res1 = mysqli_query($db,"SELECT * FROM authors");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Book | ShelfNova</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="admin.css?v=<?php echo time(); ?>">
    
    <style>
        /* --- ADD BOOK UI --- */
        .add-wrapper {
            padding: 60px 20px;
            background-color: #f4f7f5;
            min-height: 90vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .add-card {
            background: white;
            width: 100%;
            max-width: 500px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.05);
            padding: 40px;
        }

        .add-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .add-header i {
            font-size: 40px;
            color: #808847; /* Olive Gold */
            margin-bottom: 10px;
        }

        .add-header h2 {
            color: #2d6a4f;
            font-size: 22px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0;
        }

        /* --- STYLED FORM --- */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .full-width { grid-column: span 2; }

        .sn-field {
            margin-bottom: 15px;
            text-align: left;
        }

        .sn-field label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            color: #2d6a4f;
            text-transform: uppercase;
            margin-bottom: 5px;
            margin-left: 2px;
        }

        .sn-input, .sn-select {
            all: unset !important;
            display: block !important;
            box-sizing: border-box !important;
            width: 100% !important;
            height: 45px !important;
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

        /* File Input Styling */
        .custom-file {
            border: 1.5px dashed #cbd5e1;
            padding: 10px;
            border-radius: 10px;
            text-align: center;
            cursor: pointer;
            transition: 0.3s;
        }
        .custom-file:hover { border-color: #2d6a4f; background: #f0fdf4; }

        /* Button */
        .btn-add-now {
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
            margin-top: 20px !important;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-add-now:hover {
            background-color: #808847 !important;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>

    <div class="add-wrapper">
        <div class="add-card">
            
            <div class="add-header">
                <i class="fa-solid fa-book-medical"></i>
                <h2>Add New Book</h2>
            </div>

            <form action="" method="post" enctype="multipart/form-data">
                <div class="form-grid">
                    
                    <!-- Row 1 -->
                    <div class="sn-field full-width">
                        <label>Book Title</label>
                        <input type="text" name="bookname" class="sn-input" placeholder="e.g. Modern Web Development" required>
                    </div>

                    <!-- Row 2 -->
                    <div class="sn-field">
                        <label>Author</label>
                        <select class="sn-select" name="author" required>
                            <option value="">Select Author</option>
                            <?php while($row1=mysqli_fetch_array($res1)):;?>
                                <option value="<?php echo $row1[0];?>"><?php echo $row1[1];?></option>
                            <?php endwhile;?>
                        </select>
                    </div>

                    <div class="sn-field">
                        <label>Category</label>
                        <select class="sn-select" name="category" required>
                            <option value="">Select Category</option>
                            <?php while($row=mysqli_fetch_array($res)):;?>
                                <option value="<?php echo $row[0];?>"><?php echo $row[1];?></option>
                            <?php endwhile;?>
                        </select>
                    </div>

                    <!-- Row 3 -->
                    <div class="sn-field">
                        <label>ISBN Number</label>
                        <input type="text" name="ISBN" class="sn-input" placeholder="10 or 13 digits" required>
                    </div>

                    <div class="sn-field">
                        <label>Price (Rs.)</label>
                        <input type="text" name="price" class="sn-input" placeholder="0.00" required>
                    </div>

                    <!-- Row 4 -->
                    <div class="sn-field">
                        <label>Quantity</label>
                        <input type="number" name="quantity" class="sn-input" value="1" required>
                    </div>

                    <div class="sn-field">
                        <label>Status</label>
                        <input type="text" name="status" class="sn-input" value="Available" required>
                    </div>

                    <!-- Row 5: File -->
                    <div class="sn-field full-width">
                        <label>Book Cover Image</label>
                        <div class="custom-file">
                            <input type="file" name="file" id="book-img" required style="font-size: 12px; color: #64748b;">
                        </div>
                    </div>

                </div>

                <button type="submit" class="btn-add-now" name="submit">Add to Collection</button>
            </form>
        </div>
    </div>
<?php
if(isset($_POST['submit'])) {
    // 1. Check if file was actually sent
    if(isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        
        $pic_name = $_FILES['file']['name'];
        $pic_temp = $_FILES['file']['tmp_name'];
        $target_path = "images/" . $pic_name;

        // 2. Try to move the file
        if(move_uploaded_file($pic_temp, $target_path)) {
            
            // 3. Database Insert (Only if image move is successful)
            $bookname = mysqli_real_escape_string($db, $_POST['bookname']);
            $author = mysqli_real_escape_string($db, $_POST['author']);
            $category = mysqli_real_escape_string($db, $_POST['category']);
            $isbn = mysqli_real_escape_string($db, $_POST['ISBN']);
            $price = mysqli_real_escape_string($db, $_POST['price']);
            $qty = mysqli_real_escape_string($db, $_POST['quantity']);
            $status = mysqli_real_escape_string($db, $_POST['status']);

            $query = "INSERT INTO books (bookpic, bookname, authorid, categoryid, ISBN, price, quantity, status) 
                      VALUES ('$pic_name', '$bookname', '$author', '$category', '$isbn', '$price', '$qty', '$status')";
            
            if(mysqli_query($db, $query)) {
                echo "<script>alert('Book and Image Added Successfully!'); window.location='manage_books.php';</script>";
            } else {
                echo "<script>alert('DB Error: ".mysqli_error($db)."');</script>";
            }

        } else {
            // This runs if Linux blocks the file move
            echo "<script>alert('ERROR: Linux blocked the file move. Did you run the sudo chmod command?');</script>";
        }
    } else {
        // This runs if the form is missing enctype="multipart/form-data"
        $error_no = $_FILES['file']['error'];
        echo "<script>alert('ERROR: PHP received no file. Error Code: $error_no');</script>";
    }
}
?>

</body>
</html>