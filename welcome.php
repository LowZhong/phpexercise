<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Create a Record - PHP CRUD Tutorial</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<header>
    <?php include 'database/navbar.php'; ?>
</header>

<body>
    <!-- container -->
    <div class="container">
        <div class="container-fluid h1 text-center m-2" class="text-primary">
            <?php
            echo "Welcome!" . "<br/>";
            echo $_GET['username'] . "<br/>";
            ?>
        </div>
        <h1>Today Total Orders</h1>
        <?php
        // include database connection
        include 'database/connection.php';
        include 'function/function.php';

        // delete message prompt will be here

        // select all data
        $query = "SELECT customer.customerID, SUM(order_summary.orderID) AS NumberOfOrders
        FROM order_summary
        LEFT JOIN customer ON order_summary.CustomerID = customer.customerID
        GROUP BY customerID;";
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
            echo "<th>Customer ID</th>";
            echo "<th>Number Of Orders</th>";
            echo "</tr>";

            // table body will be here
            // retrieve our table contents
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // extract row
                // this will make $row['firstname'] to just $firstname only

                extract($row);
                //function

                // creating new table row per record
                echo "<tr>";
                echo "<td>{$customerID}</td>";
                echo "<td>{$NumberOfOrders}</td>";
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

        <h2>product Not Yet Purchased</h2>
        <?php

        // select all data
        $query = "SELECT products.productID 
        FROM products
        LEFT JOIN order_details 
        ON products.productID = order_details.productID
        WHERE order_details.productID is NULL";
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
            echo "<th>Product ID</th>";
            //echo "<th>Product Name</th>";
            echo "</tr>";

            // table body will be here
            // retrieve our table contents
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // extract row
                // this will make $row['firstname'] to just $firstname only

                extract($row);
                //function

                // creating new table row per record
                echo "<tr>";
                echo "<td>{$productID}</td>";
                //echo "<td>{$name}</td>";
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

        <h3>Customer Not Purchase</h3>
        <?php

        // select all data
        $query = "SELECT customer.customerID
                    FROM customer
                    LEFT JOIN order_summary
                    ON customer.customerID = order_summary.customerID
                    WHERE order_summary.customerID is NULL;";
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
            echo "<th>Customer ID</th>";
            //echo "<th>Customer Name</th>";
            echo "</tr>";

            // table body will be here
            // retrieve our table contents
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // extract row
                // this will make $row['firstname'] to just $firstname only

                extract($row);
                //function

                // creating new table row per record
                echo "<tr>";
                echo "<td>{$customerID}</td>";
                //echo "<td>{$}</td>";
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

        <h3>Top 3 best selling products</h3>
        <?php

        // select all data
        $query = "SELECT products.productID, products.name, SUM(order_details.Quantity) AS ProductTotals
                    FROM products
                    INNER JOIN order_details
                    ON products.productID = order_details.productID
                    GROUP BY products.productID
                    ORDER BY ProductTotals DESC
                    LIMIT 3;";
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
            echo "<th>Product ID</th>";
            echo "<th>product name</th>";
            echo "<th>Product Totals</th>";
            echo "</tr>";

            // table body will be here
            // retrieve our table contents
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // extract row
                // this will make $row['firstname'] to just $firstname only

                extract($row);
                //function

                // creating new table row per record
                echo "<tr>";
                echo "<td>{$productID}</td>";
                echo "<td>{$name}</td>";
                echo "<td>{$ProductTotals}</td>";
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
</body>

</html>