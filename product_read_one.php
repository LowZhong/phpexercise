<!DOCTYPE HTML>
<html>

<head>
    <title>Product Details</title>
    <!-- Latest compiled and minified Bootstrap CSS → -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Product Details</li>
                    </ol>
                </nav>
        </nav>
        <div class="page-header">
            <h1>Product Details</h1>
        </div>

        <!-- PHP read one record will be here -->
        <?php
        // get passed parameter value, in this case, the record ID
        // isset() is a PHP function used to verify if a value is there or not
        $productID = isset($_GET['productID']) ? $_GET['productID'] : die('ERROR: Record ID not found.');

        //include database connection
        include 'database/connection.php';
        include 'function/function.php';
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

        <!-- HTML read one record table will be here -->

        <table class='table table-hover table-responsive table-bordered border border-3'>
            <div class="mb-3">
                <tr class='border border-3'>
                    <td class='border border-3'>Name</td>
                    <td><?php echo htmlspecialchars($name, ENT_QUOTES);  ?></td>
                </tr>
                <tr class='border border-3'>
                    <td class='border border-3'>Description</td>
                    <td><?php echo htmlspecialchars($description, ENT_QUOTES);  ?></td>
                </tr>
                <tr class='border border-3'>
                    <td class='border border-3'>Price</td>
                    <td><?php echo htmlspecialchars($price, ENT_QUOTES);  ?></td>
                </tr>
                <tr class='border border-3'>
                    <td class='border border-3'>Images</td>
                    <td><?php echo " ". pro_img($image) . " "?></td>
                </tr>
            </div>
        </table>

        <div class="d-flex justify-content-end gap-2">
            <td class="d-flex justify-content-end gap-2">
                <a href='product_read.php' class='btn btn-danger'>Back to read products</a>
        </div>

        <!--we have our html table here where the record will be displayed-->

    </div>
    <!-- end .container -->


</body>

</html>