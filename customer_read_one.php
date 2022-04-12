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
            <?php include 'database/navbar.php'; ?>
            <h1>Customer Details</h1>
        </div>

        <!-- PHP read one record will be here -->
        <?php
        // get passed parameter value, in this case, the record ID
        // isset() is a PHP function used to verify if a value is there or not

        $customerID = isset($_GET['customerID']) ? $_GET['customerID'] : die('ERROR: Record ID not found.');
        //include database connection
        include 'database/connection.php';
        include 'database/function.php';

        // read current record's data
        try {
            // prepare select query
            $query = "SELECT customerID, username, password, email, firstname, lastname, gender, DAY(birthdate) as day, MONTH(birthdate) as month, YEAR(birthdate) as year, status FROM customer WHERE customerID = ?";
            $stmt = $con->prepare($query);

            // this is the first question mark
            $stmt->bindParam(1, $customerID);

            // execute our query
            $stmt->execute();

            // store retrieved row to a variable
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // values to fill up our form
            $username = $row['username'];
            $password = $row['password'];
            //$inputconfirmPassword = $row['inputconfirmPassword'];
            $email = $row['email'];
            $firstname = $row['firstname'];
            $lastname = $row['lastname'];
            $year = $row['year'];
            $month = $row['month'];
            $day = $row['day'];
            $birthdate = "$year/$month/$day";
            $gender = $row['gender'];
            $status = $row['status'];
        }

        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>


        <!-- HTML read one record table will be here -->

        <table class='table table-hover table-responsive table-bordered'>
            <tr>
                <td>Customer ID</td>
                <td><?php echo htmlspecialchars($customerID, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Username</td>
                <td><?php echo htmlspecialchars($username, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><?php echo htmlspecialchars($email, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Password</td>
                <td><?php echo htmlspecialchars($password, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Firstname</td>
                <td><?php echo htmlspecialchars($firstname, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Lastname</td>
                <td><?php echo htmlspecialchars($lastname, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Gender</td>
                <td><?php echo htmlspecialchars($gender, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Status</td>
                <td><?php echo htmlspecialchars($status, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Date Of Birth</td>
                <td><?php echo htmlspecialchars($birthdate, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Star Sign</td>
                <td><?php starsign($month, $day);  ?></td>
            </tr>
            <tr>
                <td>Animal Year</td>
                <td><?php animalYear($year); ?></td>
            </tr>
            <a href='customer_read.php' class='btn btn-danger'>Back to Customer List</a>
            </td>
            </tr>
        </table>
        <!--we have our html table here where the record will be displayed-->

    </div>
    <!-- end .container -->


</body>

</html>