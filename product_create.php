<?php
ob_start();
session_start();
if (!isset($_SESSION["username"])) {
    header("location: index.php");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Product Creates</title>
    <?php
    include 'head/head.php';
    ?>
</head>

<body>
    <!-- container -->
    <div class="container">
        <?php include 'navbar/navbar.php'; ?>
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Create Product</li>
                    </ol>
                </nav>
        </nav>
        <div class="page-header">
            <h1>Create A Product</h1>
        </div>



        <?php
        // define variables and set to empty values
        $name = $description = $price = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // include database connection
            include 'database/connection.php';
            include 'function/function.php';
            // posted values
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];


            if (empty($name) || empty($price)) {
                echo "<div class='alert alert-danger text-white'>Cannot Be Left Blank.</div>";
            }else if (empty($error)) {
                $error = array_filter($error);
                $error['name'] = validatename($name);
                $error['price'] = validatePrice($price);

                if (!empty($_FILES["image"]["name"])) {

                    try {
                        // insert query
                        $query = "INSERT INTO products SET name=:name, description=:description, price=:price, image=:image, created=:created";
                        // prepare query for execution
                        $stmt = $con->prepare($query);
                        $name = htmlspecialchars(strip_tags($_POST['name']));
                        $description = htmlspecialchars(strip_tags($_POST['description']));
                        $price = htmlspecialchars(strip_tags($_POST['price']));

                        // new 'image' field
                        $image = !empty($_FILES["image"]["name"]) ? sha1_file($_FILES['image']['tmp_name']) . "-" . basename($_FILES["image"]["name"]) : "";

                        $image = htmlspecialchars(strip_tags($image));

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
                            ob_end_clean();
                            $_SESSION['success'] = "<div class='alert alert-success text-white'>Record was saved.</div>";
                            header('Location: product_read.php');
                            // now, if image is not empty, try to upload the image
                            if ($image) {
                                $target_directory = "uploads/";
                                // make sure the 'uploads' folder exists
                                // if not, create it
                                if (!is_dir($target_directory)) {
                                    mkdir($target_directory, 0777, true);
                                }
                                $target_file = $target_directory . $image;

                                // make sure file does not exist
                                if (file_exists($target_file)) {
                                    $file_upload_error_messages .= "<div>Image already exists. Try to change file name.</div>";
                                }

                                // check the extension of the upload file
                                $file_type = pathinfo($target_file, PATHINFO_EXTENSION);
                                // make sure certain file types are allowed
                                $allowed_file_types = array("jpg", "png");
                                if (!in_array($file_type, $allowed_file_types)) {
                                    $file_upload_error_messages .= "<div>Only JPG or PNG files are allowed.</div>";
                                }
                                // make sure submitted file is not too large
                                if ($_FILES['image']['size'] > (5242880)) {
                                    $file_upload_error_messages .= "<div>Image must be less than 5 MB in size.</div>";
                                }
                                // make sure the 'uploads' folder exists
                                // if not, create it
                                if (!is_dir($target_directory)) {
                                    mkdir($target_directory, 0777, true);
                                }
                                // if $file_upload_error_messages is still empty
                                if (empty($file_upload_error_messages)) {
                                    // it means there are no errors, so try to upload the file
                                    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                                        // it means photo was uploaded
                                    } else {
                                        echo "<div class='alert alert-danger text-white'>";
                                        echo "<div>Unable to upload photo.</div>";
                                        echo "<div>Update the record to upload photo.</div>";
                                        echo "</div>";
                                    }
                                }
                                // if $file_upload_error_messages is NOT empty
                                else {
                                    // it means there are some errors, so show them to user
                                    echo "<div class='alert alert-danger text-white'>";
                                    echo "<div>{$file_upload_error_messages}</div>";
                                    echo "<div>Update the record to upload photo.</div>";
                                    echo "</div>";
                                }
                            } else {
                                echo "no file selected.";
                            }
                        } else {
                            echo "<div class='alert alert-danger text-white'>Unable to save record.</div>";
                        }
                    }
                    // show error
                    catch (PDOException $exception) {
                        die('ERROR: ' . $exception->getMessage());
                    }
                }
            } else {
                foreach ($error as $value) {
                    echo "<div class='alert alert-danger text-white'>$value <br/></div>"; //start print error msg
                }
            }
        }
        ?>

        <!-- html form here where the product information will be entered -->

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">

            <table class='table table-hover table-responsive table-bordered border border-3'>

                <div class="mb-3">
                    <tr class="border border-3">
                        <td class="border border-3 p-4">Name</td>
                        <td class="input-group input-group-outline my-2"><input type='text' name='name' class='form-control' value="<?php echo $name; ?>" /></td>
                    </tr>

                    <tr class="border border-3">
                        <td class="border border-3 p-4">Description</td>
                        <td class="input-group input-group-outline my-2"><textarea name='description' class='form-control' value="<?php echo $description; ?>"></textarea></td>
                    </tr>

                    <tr class="border border-3">
                        <td class="border border-3 p-4">Price</td>
                        <td class="input-group input-group-outline my-2"><input type='text' name='price' class='form-control' value="<?php echo $price; ?>" /></td>
                    </tr>

                    <tr class="border border-3">
                        <td class=" border border-3 p-4">Upload Image</td>
                        <td class="p-4"><input type="file" name="image" /></td>

                    </tr>
                </div>
            </table>

            <div class="d-flex justify-content-end gap-2">
                <input type='submit' value='Save' class='btn btn-primary' />
                <a href='product_read.php' class='btn btn-danger'>Back to read products</a>
            </div>
        </form>
    </div>
    <!-- end .container -->
</body>

</html>