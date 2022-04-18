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
            <h1>Order Create</h1>
        </div>

        <?php
        // include database connection
        include 'database/connection.php';
        include 'database/function.php';
        // define variables and set to empty values
        //$orderID = $username = $productID = $quantity = "";
        if ($_SERVER["REQUEST_METHOD"] == "POST") {


            // posted values
            $customerID = $_POST['customerID'];
            $productID = $_POST['productID'];
            $quantity = $_POST['quantity'];



            //$error['username'] = validateOrderusername($username);
            //$error['username'] = validateOrder($productID);
            //$error = array_filter($error);
            if (empty($error)) {


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
                        echo "<div class='alert alert-danger'>Unable to save record.</div>";
                    }
                } catch (PDOException $exception) {
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
        <table class='table table-hover table-responsive table-bordered'>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <table class='table table-hover table-responsive table-bordered'>

                    <?php

                    $query = "SELECT customerID, username FROM customer";
                    $stmt = $con->prepare($query);
                    // execute our query
                    $stmt->execute();
                    echo '<tr>
                            <td>Select username </td>
                            <td>
                            <div class="col">';
                    echo "<select class='form_select' name='customerID' >";
                    echo '<option selected>username</option>';
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
                            echo '<tr class="productrow">
                                <td>Select Product ' . $x . '</td>
                                <td>
                                <div class="col">';
                            echo "<select class='form_select' name='productID[]' >";
                            echo '<option selected>Product ' . $x . '</option>';
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                extract($row);
                                echo "<option value='" . $productID . "' >" . $name . "</option>";
                            }
                            echo "</select>
                            </div>
                                Quantity
                                <input type='number' name='quantity[]' class='form-control' value='" . $quantity[$x] . "' />";
                        }
                        // show error
                        catch (PDOException $exception) {
                            die('ERROR: ' . $exception->getMessage());
                        }
                    //}
                    ?>

                    <tr>
                        <td></td>
                        <td>
                            <input type='submit' value='Save' class='btn btn-primary' />
                            <a href='order_listing.php' class='btn btn-danger'>Back To Order List</a>
                        </td>
                    </tr>
                </table>

                <tr>
                    <td colspan="2">
                        <div>
                            <div>
                                <button type="button" class="add">Add More Product</button>
                                <button type="button" class="del">Delete Last Product</button>
                            </div>
                        </div>
                    </td>
                </tr>
            </form>

    </div>
    <!-- end .container -->

    <script>
        document.addEventListener('click', function (event) {
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