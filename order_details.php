<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("location: index.php");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Order Details</title>
    <?php
    include 'head/head.php';
    ?>
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
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Order Details</li>
                    </ol>
                </nav>
            </div>
        </nav>
        <div class="page-header">
            <h1>Order Details</h1>
        </div>

        <!-- PHP read one record will be here -->
        <?php
        // connection
        include 'database/connection.php';
        include 'function/function.php';

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
            echo "<tr class='border border-3'>";
            echo "<th class='border border-3'>Order Details ID</th>";
            echo "<th class='border border-3'>Order ID</th>";
            echo "<th class='border border-3'>Product ID</th>";
            echo "<th class='border border-3'>Product Name</th>";
            echo "<th class='border border-3'>Quantity</th>";
            echo "<th class='border border-3'>Price</th>";
            echo "<th class='border border-3'>Total Price</th>";
            echo "</tr>";

            // retrieve table contents
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                extract($row);

                // creating new table row per record
                $totalprice = (int)$quantity * (int)$price;
                echo "<tr class='border border-3'>";
                echo "<td class='border border-3'>{$orderDetailsID}</td>";
                echo "<td class='border border-3'>{$orderID}</td>";
                echo "<td class='border border-3'>{$productID}</td>";
                echo "<td class='border border-3'>{$name}</td>";
                echo "<td class='border border-3'>{$quantity}</td>";
                echo "<td class='border border-3'>{$price}</td>";
                echo "<td class='border border-3'>{$totalprice}</td>";
            }

            // end table
            echo "</table>";
        } else {
            echo "<div class='alert alert-danger text-white'>No Records Found.</div>";
        }
        ?>

        <div class="d-flex justify-content-end gap-2">
            <td class="d-flex justify-content-end gap-2">
                <a href='order_listing.php' class='btn btn-danger'>Back to Order Lists</a>
        </div>

    </div>
    <!-- end .container -->
</body>

</html>