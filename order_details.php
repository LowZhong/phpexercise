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
        // connection
        include 'database/connection.php';
        include 'database/function.php';

        $orderID = isset($_GET['orderID']) ? $_GET['orderID'] : die('ERROR: Record User not found.');

        //select query 
        $query = "SELECT * FROM order_details 
                    INNER JOIN products 
                    ON order_details.productID = products.productID
                    WHERE OrderID = :OrderID";
                
        $stmt = $con->prepare($query);
        $stmt->bindParam(':OrderID', $orderID);
        $stmt->execute();
        $num = $stmt->rowCount();

        if ($num > 0) {
            echo "<table class='table table-hover table-responsive table-bordered'>"; 

            // create table head
            echo "<tr>";
            echo "<th>Order Details ID</th>";
            echo "<th>Order ID</th>";
            echo "<th>Product ID</th>";
            echo "<th>Product Name</th>";
            echo "<th>Quantity</th>";
            echo "<th>Price</th>";
            echo "<th>Total Price</th>";
            echo "</tr>";

            // retrieve table contents
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            
                extract($row);

                // creating new table row per record
                $totalprice = (int)$quantity * (int)$price;
                echo "<tr>";
                echo "<td>{$orderDetailsID}</td>";
                echo "<td>{$orderID}</td>";
                echo "<td>{$productID}</td>";
                echo "<td>{$name}</td>";
                echo "<td>{$quantity}</td>";
                echo "<td>{$price}</td>";
                echo "<td>{$totalprice}</td>";
            }

            // end table
            echo "</table>";
        }
        else {
            echo "<div class='alert alert-danger'>No Records Found.</div>";
        }
        ?>

    </div>
    <!-- end .container -->
</body>

</html>