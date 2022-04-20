<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Read Records - PHP CRUD Tutorial</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Latest compiled and minified Bootstrap CSS -->

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
        <div class="page-header">
            <h1>Update Order</h1>
        </div>
        <!-- PHP read record by ID will be here -->
        <?php
        // get passed parameter value, in this case, the record ID
        // isset() is a PHP function used to verify if a value is there or not
        $orderID = isset($_GET['orderID']) ? $_GET['orderID'] : die('ERROR: Record ID not found.');

        //include database connection
        include 'database/connection.php';
        include 'function/function.php';

        // check if form was submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // posted values
            $orderDetailsID = htmlspecialchars(strip_tags($_POST['orderDetailsID']));
            $orderID = htmlspecialchars(strip_tags($_POST['orderID']));
            $product = htmlspecialchars(strip_tags($_POST['productID']));
            $quantity = htmlspecialchars(strip_tags($_POST['quantity']));

            try {
                // write update query
                // in this case, it seemed like we have so many fields to pass and
                // it is better to label them and not use question marks
                $query = "UPDATE order_summary, order_details SET * WHERE orderDetailsID = :orderDetailsID";
                // prepare query for excecution
                $stmt = $con->prepare($query);

                // bind the parameters
                $stmt->bindParam(':orderDetailsID', $orderDetailsID);
                $stmt->bindParam(':customerID', $customerID);
                $stmt->bindParam(':orderID', $orderID);
                $stmt->bindParam(':productID', $productID);
                $stmt->bindParam(':quantity', $quantity);

                // Execute the query
                if ($stmt->execute()) {
                    echo "<div class='alert alert-success'>Record was updated.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Unable to update record. Please try again.</div>";
                }
            }
            // show errors
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        }

        // read current record's data
        try {
            // prepare select query
            $query = "SELECT * FROM order_details WHERE orderID = ? LIMIT 0,1";
            $stmt = $con->prepare($query);

            // this is the first question mark
            $stmt->bindParam(1, $orderID);

            // execute our query
            $stmt->execute();

            // store retrieved row to a variable
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // values to fill up our form
            $orderDetailsID = $row['orderDetailsID'];
            $orderID = $row['orderID'];
            $productID = $row['productID'];
            $quantity = $row['quantity'];
        }

        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>

        <!-- HTML form to update record will be here -->
        <!-- PHP post to update record will be here -->



        <!--we have our html form here where new record information can be updated-->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "? orderDetailsID={$orderDetailsID}"); ?>" method="post">
            <table class='table table-hover table-responsive table-bordered'>

                <?php
                //for ($x = 1; $x <= 3; $x++) {
                    try {
                        // prepare select query
                        $query = "SELECT * FROM products";
                        $stmt = $con->prepare($query);
                        
                        // execute our query
                        $stmt->execute();
                        echo '<tr class="productrow">
                            <td>Select Product</td>
                            <td>
                            <div class="col">';
                        echo "<select class='form_select' name='productID[]' >";
                        echo '<option selected>Product</option>';
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            extract($row);
                            echo "<option value='" . $productID . "' >" . $name . "</option>";
                        }
                        echo "</select>
                        </div>
                            Quantity
                            <input type='number' name='quantity[]' class='form-control' value='' />";
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
                        <input type='submit' value='Save Changes' class='btn btn-primary' />
                        <a href='order_listing.php' class='btn btn-danger'>Back to Order List</a>
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