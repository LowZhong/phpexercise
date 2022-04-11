<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Read One Record - PHP CRUD Tutorial</title>
    <!-- Latest compiled and minified Bootstrap CSS → -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Order Details</h1>
        </div>

        <!-- PHP read one record will be here -->
        <?php
        // get passed parameter value, in this case, the record ID
        // isset() is a PHP function used to verify if a value is there or not
        $orderDetailsID = isset($_GET['orderID']) ? $_GET['orderID'] : die('ERROR: Record Order Detail ID not found.');

        //include database connection
        include 'database/connection.php';
        include 'database/function.php';

        // read current record's data
        try {
            // prepare select query
            $query = "SELECT orderDetailsID, orderID, productID, quantity  FROM order_details WHERE orderID = ? LIMIT 0,1";
            $stmt = $con->prepare($query);
            // this is the first question mark
            $stmt->bindParam(1, $orderDetailsID);
            // execute our query
            $stmt->execute();
            // store retrieved row to a variable
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            // values to fill up our form
            $orderDetailsID = $row["orderDetailsID"];
            $orderID = $row["orderID"];
            $productID = $row["productID"];
            $quantity = $row["quantity"];
            
        }

        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>

        <!-- HTML read one record table will be here -->
        <table class='table table-hover table-responsive table-bordered'>
            <tr>
                <td>Order Detail ID</td>
                <td><?php echo htmlspecialchars($orderDetailsID, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Order ID</td>
                <td><?php echo htmlspecialchars($orderID, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Username</td>
                <td><?php echo htmlspecialchars($username, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>product</td>
                <td><?php echo htmlspecialchars($productID, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Quantity</td>
                <td><?php echo htmlspecialchars($quantity, ENT_QUOTES);  ?></td>
            </tr>

            
            <a href='order_listing.php' class='btn btn-danger'>Back to order list</a>
            </td>
            </tr>
        </table>
        <!--we have our html table here where the record will be displayed-->
    </div>
    <!-- end .container -->
</body>

</html>