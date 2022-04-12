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
        <?php include 'database/navbar.php'; ?>
        <div class="page-header">
            <h1>Order Details</h1>
        </div>

        <!-- PHP read one record will be here -->
        <?php
        // get passed parameter value, in this case, the record ID
        // isset() is a PHP function used to verify if a value is there or not
        //$orderDetailsID = isset($_GET['orderID']) ? $_GET['orderID'] : die('ERROR: Record Order Detail ID not found.');

        //include database connection
        include 'database/connection.php';
        include 'database/function.php';

        // select all data
        $query = "SELECT orderDetailsID, orderID, name, price, quantity
                    FROM order_details 
                    INNER JOIN products
                    ON order_details.productID = products.productID;";
        $stmt = $con->prepare($query);
        $stmt->execute();

        // this is how to get number of rows returned
        $num = $stmt->rowCount();

        //check if more than 0 record found
        if ($num > 0) {

            // data from database will be here
            echo "<table class='table table-hover table-responsive table-bordered'>"; //start table

            //creating our table heading
            echo "<tr>";
            echo "<th>orderDetails ID</th>";
            echo "<th>Orders ID</th>";
            echo "<th>Product Name</th>";
            echo "<th>Product Prices</th>";
            echo "<th>Total Prices</th>";
            echo "<th>Product Quantity</th>";
            echo "</tr>";

            // table body will be here
            // retrieve our table contents
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // extract row
                // this will make $row['firstname'] to just $firstname only

                extract($row);
                //function

                // creating new table row per record
                $totalprice = (int)$quantity * (int)$price;
                echo "<tr>";
                echo "<td>{$orderDetailsID}</td>";
                echo "<td>{$orderID}</td>";
                echo "<td>{$name}</td>";
                echo "<td>{$price}</td>";
                echo "<td>{$totalprice}</td>";
                echo "<td>{$quantity}</td>";
                echo "</td>";
                echo "</tr>";
            }
            // end table
            echo "</table>";
        }
        // if no records found
        else {
            echo "<div class='alert alert-danger'>No records Order ID found.</div>";
        }
        ?>
    </div>
    <!-- end .container -->
</body>

</html>