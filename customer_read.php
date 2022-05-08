<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("location: index.php");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Customer Lists</title>
    <?php
    include 'head/head.php';
    ?>
</head>

<body>
    <!-- container -->
    <div class="container ms-5">
        <?php include 'navbar/navbar.php'; ?>
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Customer Lists</li>
                    </ol>
                </nav>
            </div>
        </nav>
        <div class="page-header">
            <h1>Customer Page</h1>
        </div>

        <!-- PHP code to read records will be here -->
        <?php
        // include database connection
        include 'database/connection.php';
        include 'function/function.php';

        // delete message prompt will be here

        // select all data
        $query = "SELECT customerID, username, email, firstname, lastname, gender, user_image FROM customer ORDER BY customerID ASC";
        $stmt = $con->prepare($query);
        $stmt->execute();

        // this is how to get number of rows returned
        $num = $stmt->rowCount();


        // link to create record form
        echo "<a href='customer_create.php' class='btn btn-primary m-b-1em'>Create New Customer Account</a>";

        //check if more than 0 record found
        if ($num > 0) {

            // data from database will be here
            echo "<table class='table table-hover table-responsive table-bordered'>"; //start table

            //creating our table heading
            echo "<tr class='border border-3'>";
            echo "<th class='border border-3'>Customer ID</th>";
            echo "<th class='border border-3'>Username</th>";
            echo "<th class='border border-3'>Email</th>";
            echo "<th class='border border-3'>Firstname</th>";
            echo "<th class='border border-3'>Lastname</th>";
            echo "<th class='border border-3'>Gender</th>";
            echo "<th class='border border-3'>Profile Picture</th>";
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
                echo "<td class='border border-3'>{$customerID}</td>";
                echo "<td class='border border-3'>{$username}</td>";
                echo "<td class='border border-3'>{$email}</td>";
                echo "<td class='border border-3'>{$firstname}</td>";
                echo "<td class='border border-3'>{$lastname}</td>";
                echo "<td class='border border-3'>" . sex($gender) . "</td>";
                echo "<td class='border border-3'>" . user_img($user_image) . "</td>";
                echo "<td>";

                // read one record
                echo "<a href='customer_read_one.php?customerID={$customerID}' class='btn btn-info m-r-1em'>Read</a>";

                // we will use this links on next part of this post
                echo "<a href='customer_update.php?customerID={$customerID}' class='btn btn-primary m-r-1em'>Edit</a>";

                // we will use this links on next part of this post
                echo "<a href='#' onclick='delete_user({$customerID});'  class='btn btn-danger'>Delete</a>";
                echo "</td>";
                echo "</tr>";
            }


            // end table
            echo "</table>";
        }
        // if no records found
        else {
            echo "<div class='alert alert-danger'>No records found.</div>";
        }

        $action = isset($_GET['action']) ? $_GET['action'] : "";

        // if it was redirected from delete.php
        if ($action == 'deleted') {
            echo "<div class='alert alert-success'>Record was deleted.</div>";
        }
        ?>

    </div> <!-- end .container -->

    <script>
        // confirm record deletion
        function delete_user(customerID) {

            var answer = confirm('Are you sure?');
            if (answer) {
                // if user clicked ok,
                // pass the id to delete.php and execute the delete query
                window.location = 'delete_user.php?customerID=' + customerID;
            }
        }
    </script>
</body>

</html>