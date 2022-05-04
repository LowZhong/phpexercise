<!DOCTYPE HTML>
<html>

<head>
    <title>Create Order</title>
    <!-- Latest compiled and minified Bootstrap CSS (Apply your Bootstrap here -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900%7CRoboto+Slab:400,700" />
    <!-- Nucleo Icons -->
    <link href="assets/css/nucleo-icons.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <!-- CSS Files -->
    <link href="assets/css/styles.css" rel="stylesheet" />
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
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Create An Order</li>
                    </ol>
                </nav>
        </nav>
        <div class="page-header">
            <h1>Create An Orders</h1>
        </div>

        <?php
        // include database connection
        include 'database/connection.php';
        include 'function/function.php';
        // define variables and set to empty values
        //$orderID = $username = $productID = $quantity = "";
        if ($_SERVER["REQUEST_METHOD"] == "POST") {


            // posted values
            $customerID = $_POST['customerID'];
            $productID = $_POST['productID'];
            $quantity = $_POST['quantity'];
            //echo count($productID);
            //var_dump($productID);
            //var_dump($quantity);

            //echo $customerID;
            if (!empty($customerID)) {
                if (!empty(array_filter($productID))) {
                    if (!empty(array_filter($quantity))) {

                        try {
                            // insert query
                            $query = "INSERT INTO order_summary (customerID) VALUES (?)";
                            // prepare query for execution
                            $stmt = $con->prepare($query);
                            // bind the parameters
                            $stmt->bindParam(1, $customerID);
                            // Execute the query
                            if ($stmt->execute()) {
                                $last_order_id = $con->lastInsertId();
                                if ($last_order_id > 0) {

                                    for ($i = 0; $i < count($productID); $i++) {

                                        try {
                                            $query = "INSERT INTO order_details (orderID, productID, quantity) VALUES (:lastorderid, :productID, :quantity)";

                                            //prepare query for execute
                                            $stmt = $con->prepare($query);
                                            //posted values
                                            $stmt->bindParam(":lastorderid", $last_order_id);
                                            $stmt->bindParam(":productID", $productID[$i]);
                                            $stmt->bindParam(":quantity", $quantity[$i]);
                                            //execute the query
                                            if ($stmt->execute()) {
                                            }
                                        } catch (PDOException $exception) {
                                            die('ERROR: ' . $exception->getMessage());
                                        }
                                    }
                                }
                                echo "<div class='alert alert-success'>Record was saved.</div>";
                            } else {
                                echo "<div class='alert alert-danger text-white'>Unable to save record.</div>";
                            }
                        } catch (PDOException $exception) {
                            die('ERROR: ' . $exception->getMessage());
                        }
                    } else {
                        echo "<div class='alert alert-danger text-white'>Please Enter Your Quantity.</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger text-white'>Please Select Your Products.</div>";
                }
            } else {
                echo "<div class='alert alert-danger text-white'>Please Select Your Username.</div>";
            }
        }

        ?>

        <!-- html form here where the product information will be entered -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <table class='table table-hover table-responsive table-bordered'>
                <div class="mb-3">

                    <?php

                    $query = "SELECT customerID, username FROM customer";
                    $stmt = $con->prepare($query);
                    // execute our query
                    $stmt->execute();
                    echo '<tr class="border border-3">
                            <td class="border border-3">Select username </td>
                            <td>
                            <div class="col">';
                    echo "<select class='form_select' name='customerID' >";
                    echo '<option value="">username</option>';
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        echo "<option value='" . $customerID . "' >" . $username . "</option>";
                    }
                    //for ($x = 1; $x <= 1; $x++) {

                    try {
                        // prepare select query
                        $query = "SELECT * FROM products";
                        $stmt = $con->prepare($query);

                        // execute our query
                        $stmt->execute();
                        echo '<tr class="productrow border border-3">
                                <td class="border border-3">Select Product<td>
                                <div class="col">';
                        echo '<select class="form_select" name="productID[]" >';
                        echo '<option value="">Product</option>';

                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            extract($row);
                            echo "<option value='" . $productID . "' >" . $name . "</option>";
                        }
                        echo "</select>
                            </div>
                                Quantity
                                <div class='input-group input-group-outline my-2'>
                                <input type='number' name='quantity[]' class='form-control' value='' /></div></td></tr>";
                    }
                    // show error
                    catch (PDOException $exception) {
                        die('ERROR: ' . $exception->getMessage());
                    }
                    //}
                    ?>


                </div>
            </table>
            <div class="d-flex justify-content-start gap-2">
                <div>
                    <button type="button" class="add">Add More Product</button>
                    <button type="button" class="del">Delete Last Product</button>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">

                <input type='submit' value='Save Changes' class='btn btn-primary' />
                <a href='order_listing.php' class='btn btn-danger'>Back to Order List</a>
            </div>
        </form>

    </div>
    <!-- end .container -->

    <script>
        document.addEventListener('click', function(event) {
            if (event.target.matches('.add')) {
                var element = document.querySelector('.productrow');
                var clone = element.cloneNode(true);
                element.after(clone);
            }
            if (event.target.matches('.del')) {
                var total = document.querySelectorAll('.productrow').length;
                if (total > 1) {
                    var element = document.querySelector('.productrow');
                    element.remove(element);
                }
            }
        }, false);
    </script>

</body>

</html>