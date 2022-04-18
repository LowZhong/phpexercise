<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Create a Record - PHP CRUD Tutorial</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <!-- container -->
    <div class="container">
        <?php include 'database/navbar.php'; ?>
        <div class="page-header">
            <h1>Create Product</h1>
        </div>

        <?php
        // define variables and set to empty values
        $name = $description = $price = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // include database connection
            include 'database/connection.php';
            include 'database/function.php';
            // posted values
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];

            $error['name'] = validatename($name);
            $error['price'] = validatePrice($price);

            $error = array_filter($error);
            if (empty($error)) {
                try {
                    // insert query
                    $query = "INSERT INTO products SET name=:name, description=:description, price=:price, image=:image, created=:created";
                    // prepare query for execution
                    $stmt = $con->prepare($query);
                    $name = htmlspecialchars(strip_tags($_POST['name']));
                    $description = htmlspecialchars(strip_tags($_POST['description']));
                    $price = htmlspecialchars(strip_tags($_POST['price']));

                    // new 'image' field
                    $image = !empty($_FILES["image"]["name"])
                        ? sha1_file($_FILES['image']['tmp_name']) . "-" . basename($_FILES["image"]["name"])
                        : "";
                    $image = htmlspecialchars(strip_tags($image));

                    if ($image) {
                        $target_directory = "uploads/";
                        // make sure the 'uploads' folder exists
                        // if not, create it
                        if (!is_dir($target_directory)) {
                            mkdir($target_directory, 0777, true);
                        }
                        $target_file = $target_directory . $image;
                        $file_upload_error_messages = "";

                        // make sure file does not exist
                        if (file_exists($target_file)) {
                            $file_upload_error_messages .= "<div>Image already exists. Try to change file name.</div>";
                        }

                        // check the extension of the upload file
                        $file_type = pathinfo($target_file, PATHINFO_EXTENSION);
                        // make sure certain file types are allowed
                        $allowed_file_types = array("jpg", "png");
                        if (!in_array($file_type, $allowed_file_types)) {
                            $file_upload_error_messages .= "<div>Only JPG and PNG files are allowed.</div>";
                        }
                        // make sure submitted file is not too large
                        if ($_FILES['image']['size'] > (5120)) {
                            $file_upload_error_messages .= "<div>Image must be less than 5 MB in size.</div>";
                        }
                    } else {
                        echo "no file selected.";
                    }

                
                    // bind the parameters
                    $stmt->bindParam(':name', $name);
                    $stmt->bindParam(':description', $description);
                    $stmt->bindParam(':price', $price);
                    $stmt->bindParam(':image', $image);
                    // specify when this record was inserted to the database
                    date_default_timezone_set("Asia/Kuala_Lumpur");
                    $created = date('Y-m-d H:i:s');
                    $stmt->bindParam(':created', $created);

                    // Execute the query
                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Record was saved.</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Unable to save record.</div>";
                    }
                }

                // show error
                catch (PDOException $exception) {
                    die('ERROR: ' . $exception->getMessage());
                }

                if (empty($file_upload_error_messages)) {
                    // it means there are no errors, so try to upload the file (now only start uploading)
                    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                        echo "<div class='alert'>";
                        echo "<div>Uploaded successfully</div>";
                        echo "</div>";
                    } else {
                        echo "<div class='alert alert-danger'>";
                        echo "<div>Unable to upload photo.</div>";
                        echo "</div>";
                    }
                } else {
                    // it means there are some errors, so show them to user
                    echo "<div class='alert alert-danger'>";
                    echo "<div>{$file_upload_error_messages}</div>";
                    echo "</div>";
                }

            } else {
                foreach ($error as $value) {
                    echo "<div class='alert alert-danger'>$value <br/></div>"; //start print error msg
                }
            }
        }
        ?>

        <!-- html form here where the product information will be entered -->

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <table class='table table-hover table-responsive table-bordered'>

                <tr>
                    <td>Name</td>
                    <td><input type='text' name='name' class='form-control' value="<?php echo $name; ?>" /></td>
                </tr>

                <tr>
                    <td>Description</td>
                    <td><textarea name='description' class='form-control' value="<?php echo $description; ?>"></textarea></td>
                </tr>

                <tr>
                    <td>Price</td>
                    <td><input type='text' name='price' class='form-control' value="<?php echo $price; ?>" /></td>
                </tr>

                <tr>
                    <td>Upload Image</td>
                    <td>
                        <input type="file" name="image" />

                    </td>
                </tr>

                <tr>

                    <td></td>

                    <td>
                        <input type='submit' value='Save' class='btn btn-primary' />
                        <a href='product_read.php' class='btn btn-danger'>Back to read products</a>
                    </td>
                </tr>

            </table>

        </form>
    </div>
    <!-- end .container -->
</body>

</html>