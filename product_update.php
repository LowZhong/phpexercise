<!DOCTYPE HTML>
<html>

<head>
    <title>Update The Products</title>
    <?php
    include 'head/head.php';
    ?>
    <!-- custom css -->
    <style>
        .m-r-1em {
            margin-right: 1em;
        }

        .m-b-1em {
            margin-bottom: 1em;
        }

        .m-l-1em {
            margin-left: 1em;
        }

        .mt0 {
            margin-top: 0;
        }
    </style>
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
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Update Products</li>
                    </ol>
                </nav>
        </nav>
        <div class="page-header">
            <h1>Update Product</h1>
        </div>
        <!-- PHP read record by ID will be here -->
        <?php
        // get passed parameter value, in this case, the record ID
        // isset() is a PHP function used to verify if a value is there or not
        $productID = isset($_GET['productID']) ? $_GET['productID'] : die('ERROR: Record ID not found.');
        $name = $description = $price = "";
        $target_file = "";

        //include database connection
        include 'database/connection.php';
        include 'function/function.php';

        // check if form was submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // posted values
            $name = htmlspecialchars(strip_tags($_POST['name']));
            $description = htmlspecialchars(strip_tags($_POST['description']));
            $price = htmlspecialchars(strip_tags($_POST['price']));

            $error['name'] = validatename($name);
            $error['price'] = validatePrice($price);
            $error = array_filter($error);
            $file_upload_error_messages ="";

            if (empty($error)) {
                if(!empty($_FILES["image"]["name"])){
                try {
                    // write update query
                    // in this case, it seemed like we have so many fields to pass and
                    // it is better to label them and not use question marks
                    $query = "UPDATE products SET name=:name, description=:description, price=:price, image=:image WHERE productID = :productID";
                    // prepare query for excecution
                    $stmt = $con->prepare($query);

                   

                    // new 'image' field
                    $image = !empty($_FILES["image"]["name"])
                        ? sha1_file($_FILES['image']['tmp_name']) . "-" . basename($_FILES["image"]["name"])
                        : "";
                    $image = htmlspecialchars(strip_tags($image));

                    // bind the parameters
                    $stmt->bindParam(':name', $name);
                    $stmt->bindParam(':description', $description);
                    $stmt->bindParam(':price', $price);
                    $stmt->bindParam(':productID', $productID);
                    $stmt->bindParam(':image', $image);
                    // Execute the query
                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Record was updated.</div>";
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
                        echo "<div class='alert alert-danger text-white'>Unable to update record. Please try again.</div>";
                    }
                }
                // show errors
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

        // read current record's data
        try {
            // prepare select query
            $query = "SELECT productID, name, description, price, image FROM products WHERE productID = ? LIMIT 0,1";
            $stmt = $con->prepare($query);

            // this is the first question mark
            $stmt->bindParam(1, $productID);

            // execute our query
            $stmt->execute();

            // store retrieved row to a variable
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // values to fill up our form
            $name = $row['name'];
            $description = $row['description'];
            $price = $row['price'];
            $image = $row['image'];
        }

        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>
        <!-- HTML form to update record will be here -->
        <!--we have our html form here where new record information can be updated-->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?productID={$productID}"); ?>" method="post" enctype="multipart/form-data">
            <table class='table table-hover table-responsive table-bordered border border-3'>
                <div class="mb-3">
                    <tr class="border border-3">
                        <td class="border border-3 p-4">Name</td>
                        <td class="input-group input-group-outline my-2"><input type='text' name='name' <?php echo htmlspecialchars($name, ENT_QUOTES);  ?> class='form-control' value="<?php echo $name; ?>" /></td>
                    </tr>
                    <tr class="border border-3">
                        <td class="border border-3 p-4">Description</td>
                        <td class="input-group input-group-outline my-2"><textarea name='description' class='form-control'><?php echo htmlspecialchars($description, ENT_QUOTES); ?></textarea></td>
                    </tr>
                    <tr class="border border-3">
                        <td class="border border-3 p-4">Price</td>
                        <td class="input-group input-group-outline my-2"><input type='text' name='price' value="<?php echo htmlspecialchars($price, ENT_QUOTES);  ?>" class='form-control' value="<?php echo $price; ?>" /></td>
                    </tr>

                    <tr class="border border-3">
                        <td class="border border-3 p-4">Upload Product Image</td>
                        <td class="p-4">
                            <input type="file" name="image" />
                        </td>
                    </tr>
                </div>

            </table>
            <div class="d-flex justify-content-end gap-2">
                <input type='submit' value='Save Changes' class='btn btn-primary' />
                <a href='product_read.php' class='btn btn-danger'>Back to read products</a>
            </div>
        </form>


    </div>
    <!-- end .container -->
</body>

</html>