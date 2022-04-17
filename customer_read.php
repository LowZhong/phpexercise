<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Create a Record - PHP CRUD Tutorial</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <!-- container -->
    <div class="container">
        <div class="page-header">
            <?php include 'database/navbar.php'; ?>
            <h1>Customer Page</h1>
        </div>

        <!-- PHP code to read records will be here -->
        <?php
        // include database connection
        include 'database/connection.php';
        include 'database/function.php';

        // delete message prompt will be here

        // select all data
        $query = "SELECT customerID, username, email, firstname, lastname, gender FROM customer ORDER BY customerID DESC";
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
            echo "<tr>";
            echo "<th>Customer ID</th>";
            echo "<th>Username</th>";
            echo "<th>Email</th>";
            echo "<th>Firstname</th>";
            echo "<th>Lastname</th>";
            echo "<th>Gender</th>";
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
                echo "<td>{$username}</td>";
                echo "<td>{$email}</td>";
                echo "<td>{$firstname}</td>";
                echo "<td>{$lastname}</td>";
                echo "<td>" . sex($gender) . "</td>";
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
        ?>

        <!-- confirm delete record will be here -->
        <?php
        // include database connection
        include 'database/connection.php';
        try {
            // get record ID
            // isset() is a PHP function used to verify if a value is there or not
            $customerID = isset($_GET['customerID']) ? $_GET['customerID'] :  die('ERROR: Record ID not found.');

            // delete query
            $query = "DELETE FROM customer WHERE customerID = ?";
            $stmt = $con->prepare($query);
            $stmt->bindParam(1, $customerID);

            if ($stmt->execute()) {
                // redirect to read records page and
                // tell the user record was deleted
                header('Location: customer_read.php?action=deleted');
            } else {
                die('Unable to delete record.');
            }
        }
        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
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
                window.location = 'customer_read.php?customerID=' + customerID;
            }
        }
    </script>
</body>

</html>