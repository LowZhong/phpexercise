<?php
ob_start();
session_start();
if (!isset($_SESSION["username"])) {
    header("location: index.php");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Order Lists</title>
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
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Order Lists</li>
                    </ol>
                </nav>
        </nav>
        <div class="page-header">
            <h1>Order Lists</h1>
        </div>

        <div class="table-responsive">
            <table class="table">

                <!-- PHP code to read records will be here -->
                <?php
                // include database connection
                include 'database/connection.php';
                include 'function/function.php';

                if (isset($_SESSION['success'])) {
                    echo $_SESSION['success'];
                    unset($_SESSION['success']);
                }

                if (isset($_SESSION['success_update'])) {
                    echo $_SESSION['success_update'];
                    unset($_SESSION['success_update']);
                }

                // delete message prompt will be here
                $action = isset($_GET['action']) ? $_GET['action'] : "";

                // if it was redirected from delete.php
                if ($action == 'deleted') {
                    echo "<div class='alert alert-success text-white'>Record was deleted.</div>";
                } else if ($action == 'deleteerror') {
                    echo "<div class='alert alert-danger text-white'>Unable to delete record.</div>";
                }

                // select all data
                $query = "SELECT o.orderID, o.customerID, o.orderTime, c.username
        FROM order_summary o
        INNER JOIN customer c
        ON o.customerID = c.customerID
        ORDER BY o.orderID DESC";

                $stmt = $con->prepare($query);
                $stmt->execute();

                // this is how to get number of rows returned
                $num = $stmt->rowCount();


                // link to create record form
                echo "<a href='order_create.php' class='btn btn-primary m-b-1em'>Create New Order</a>";

                //check if more than 0 record found
                if ($num > 0) {

                    // data from database will be here
                    echo "<table class='table table-hover table-responsive table-bordered'>"; //start table

                    //creating our table heading
                    echo "<tr class='border border-3'>";
                    echo "<th class='border border-3'>order ID</th>";
                    echo "<th class='border border-3'>Customer ID</th>";
                    echo "<th class='border border-3'>Username</th>";
                    echo "<th class='border border-3'>Order Date & Time</th>";
                    echo "<th class='border border-3'>Action</th>";
                    echo "</tr>";

                    // table body will be here
                    // retrieve our table contents
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        // extract row
                        // this will make $row['firstname'] to just $firstname only

                        extract($row);
                        //function

                        // creating new table row per record
                        echo "<tr class='border border-3'>";
                        echo "<td class='border border-3'>{$orderID}</td>";
                        echo "<td class='border border-3'>{$customerID}</td>";
                        echo "<td class='border border-3'>{$username}</td>";
                        echo "<td class='border border-3'>{$orderTime}</td>";
                        echo "<td class='border border-3'>";

                        // read one record
                        echo "<a href='order_details.php?orderID={$orderID}' class='btn btn-info m-r-1em'>Read</a>";

                        // we will use this links on next part of this post
                        echo "<a href='order_update.php?orderID={$orderID}' class='btn btn-primary m-r-1em'>Edit</a>";

                        // we will use this links on next part of this post
                        echo "<a href='#' onclick='delete_order({$orderID});'  class='btn btn-danger'>Delete</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                    // end table
                    echo "</table>";
                }
                // if no records found
                else {
                    echo "<div class='alert alert-danger text-white'>No records Order ID found.</div>";
                }
                ?>
            </table>
        </div>
    </div> <!-- end .container -->

    <script>
        // confirm record deletion
        function delete_order(orderID) {

            var answer = confirm('Are you sure?');
            if (answer) {
                // if user clicked ok,
                // pass the id to delete.php and execute the delete query
                window.location = 'delete_order.php?orderID=' + orderID;
            }
        }
    </script>
</body>

</html>