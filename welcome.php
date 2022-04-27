<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("location: index.php");
    echo "record is here.";
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Welcome</title>
    <!-- Latest compiled and minified Bootstrap CSS (Apply your Bootstrap here -->
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
    <link id="pagestyle" href="assets/css/material-dashboard.css?v=3.0.2" rel="stylesheet" />
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
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Welcome</li>
                    </ol>
                </nav>
        </nav>
        <div class="container-fluid h1 text-center m-2" class="text-primary">
            <?php
            echo "<div class ='text-success display-1'>Welcome !</div>";
            echo $_GET['username'];
            ?>
        </div>

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

            // table body will be here
            // retrieve our table contents
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // extract row
                // this will make $row['firstname'] to just $firstname only

                extract($row);
                //function

            }
        }
        // if no records found
        else {
            echo "<div class='alert alert-danger'>No records Order ID found.</div>";
        }
        ?>

        <div class="container-fluid py-4 d-flex">
            <div class="row">
                <div class="col-xl-15 col-sm-5 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-header p-30 pt-2">

                            <div class="text-start pt-2">
                                <p class="fs-1 mb-0 text-capitalize">Today's Order</p>
                                <h4 class="mb-0"><?php echo $NumberOfOrders; ?></h4>
                            </div>
                        </div>
                        <hr class="dark horizontal my-0">
                        <div class="card-footer p-3">
                            <p class="mb-0"><span class="text-success text-sm font-weight-bolder">Order From Customers </span></p>
                        </div>
                    </div>
                </div>

                <?php
                //product not purchased
                // select all data
                $query = "SELECT products.name
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

                    // table body will be here
                    // retrieve our table contents
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        // extract row
                        // this will make $row['firstname'] to just $firstname only
                        extract($row);
                        //function
                    }
                }
                // if no records found
                else {
                    echo "<div class='alert alert-danger'>No records Order ID found.</div>";
                }
                ?>
                <div class="container-fluid py-4">
                    <div class="row">
                        <div class="col-xl-15 col-sm-5 mb-xl-0 mb-4">
                            <div class="card">
                                <div class="card-header p-30 pt-2">

                                    <div class="text-start pt-2">
                                        <p class="fs-2 mb-0 text-capitalize">Products Not Purchased Yet</p>
                                        <h4 class="mb-0"><?php echo $name; ?></h4>
                                    </div>
                                </div>
                                <hr class="dark horizontal my-0">
                                <div class="card-footer p-3">
                                    <p class="mb-0"><span class="text-danger text-sm font-weight-bolder">Name of product not purchase </span></p>
                                </div>
                            </div>
                        </div>

                        <?php
                        // select all data
                        $query = "SELECT customer.username
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

                            // table body will be here
                            // retrieve our table contents
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                // extract row
                                // this will make $row['firstname'] to just $firstname only

                                extract($row);
                                //function
                            }
                        }

                        // if no records found
                        else {
                            echo "<div class='alert alert-danger'>No records Order ID found.</div>";
                        }
                        ?>
                        <div class="container-fluid py-4">
                            <div class="row">
                                <div class="col-xl-15 col-sm-5 mb-xl-0 mb-4">
                                    <div class="card">
                                        <div class="card-header p-30 pt-2">

                                            <div class="text-start pt-2">
                                                <p class="fs-2 mb-0 text-capitalize">Customer Not Purchased yet</p>
                                                <h4 class="mb-0"><?php echo $username; ?></h4>
                                            </div>
                                        </div>
                                        <hr class="dark horizontal my-0">
                                        <div class="card-footer p-3">
                                            <p class="mb-0"><span class="text-danger text-sm font-weight-bolder">Name of the customer that didn't purchased yet </span></p>
                                        </div>
                                    </div>
                                </div>

                                <?php
                                // select all data
                                $query = "SELECT products.name, SUM(order_details.Quantity) AS ProductTotals
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

                                    // table body will be here
                                    // retrieve our table contents
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        // extract row
                                        // this will make $row['firstname'] to just $firstname only

                                        extract($row);
                                        //function
                                    }
                                }
                                // if no records found
                                else {
                                    echo "<div class='alert alert-danger'>No records Order ID found.</div>";
                                }
                                ?>
                                <div class="container-fluid py-4">
                                    <div class="row">
                                        <div class="col-xl-15 col-sm-5 mb-xl-0 mb-4">
                                            <div class="card">
                                                <div class="card-header p-30 pt-2">

                                                    <div class="text-start pt-2">
                                                        <p class="fs-2 mb-0 text-capitalize">Top 3 Selling Products</p>
                                                        <h4 class="mb-0"><?php echo $name; ?></h4>
                                                    </div>
                                                </div>
                                                <hr class="dark horizontal my-0">
                                                <div class="card-footer p-3">
                                                    <p class="mb-0"><span class="text-success text-sm font-weight-bolder">Top sell products !</span></p>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
</body>

</html>