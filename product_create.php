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
                    $query = "INSERT INTO products SET name=:name, description=:description, price=:price, created=:created";
                    // prepare query for execution
                    $stmt = $con->prepare($query);
                    // bind the parameters
                    $stmt->bindParam(':name', $name);
                    $stmt->bindParam(':description', $description);
                    $stmt->bindParam(':price', $price);
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
            } else {
                foreach ($error as $value) {
                    echo "<div class='alert alert-danger'>$value <br/></div>"; //start print error msg
                } 
            }
        }

        ?>
        <!-- html form here where the product information will be entered -->

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
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