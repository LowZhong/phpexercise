<!DOCTYPE HTML>
<html>

<head>
    <title>Customer Details</title>
    <!-- Latest compiled and minified Bootstrap CSS (Apply your Bootstrap here -->
    <meta charset="utf-8" />
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
    <link href="assets/css/styles.css" rel="stylesheet" />
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
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Customer Details</li>
                    </ol>
                </nav>
            </div>
        </nav>
        <div class="page-header">
            <h1>Customer Details</h1>
        </div>

        <!-- PHP read one record will be here -->
        <?php
        // get passed parameter value, in this case, the record ID
        // isset() is a PHP function used to verify if a value is there or not

        $customerID = isset($_GET['customerID']) ? $_GET['customerID'] : die('ERROR: Record ID not found.');
        //include database connection
        include 'database/connection.php';
        include 'function/function.php';

        // read current record's data
        try {
            // prepare select query
            $query = "SELECT customerID, username, password, email, firstname, lastname, gender, DAY(birthdate) as day, MONTH(birthdate) as month, YEAR(birthdate) as year, status, user_image FROM customer WHERE customerID = ?";
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
            $user_image = $row['user_image'];
        }

        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>


        <!-- HTML read one record table will be here -->

        <table class='table table-hover table-responsive table-bordered'>
            <div class="mb-3">
                <tr class='border border-3'>
                    <td class='border border-3'>Customer ID</td>
                    <td><?php echo htmlspecialchars($customerID, ENT_QUOTES);  ?></td>
                </tr>
                <tr class='border border-3'>
                    <td class='border border-3'>Username</td>
                    <td><?php echo htmlspecialchars($username, ENT_QUOTES);  ?></td>
                </tr>
                <tr class='border border-3'>
                    <td class='border border-3'>Email</td>
                    <td><?php echo htmlspecialchars($email, ENT_QUOTES);  ?></td>
                </tr>
                <tr class='border border-3'>
                    <td class='border border-3'>Password</td>
                    <td><?php echo htmlspecialchars($password, ENT_QUOTES);  ?></td>
                </tr>
                <tr class='border border-3'>
                    <td class='border border-3'>Firstname</td>
                    <td><?php echo htmlspecialchars($firstname, ENT_QUOTES);  ?></td>
                </tr>
                <tr class='border border-3'>
                    <td class='border border-3'>Lastname</td>
                    <td><?php echo htmlspecialchars($lastname, ENT_QUOTES);  ?></td>
                </tr>
                <tr class='border border-3'>
                    <td class='border border-3'>Gender</td>
                    <td><?php echo htmlspecialchars($gender, ENT_QUOTES);  ?></td>
                </tr>
                <tr class='border border-3'>
                    <td class='border border-3'>Status</td>
                    <td><?php echo htmlspecialchars($status, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td class='border border-3'>Date Of Birth</td>
                    <td><?php echo htmlspecialchars($birthdate, ENT_QUOTES);  ?></td>
                </tr>
                <tr class='border border-3'>
                    <td class='border border-3'>Star Sign</td>
                    <td><?php starsign($month, $day);  ?></td>
                </tr>
                <tr class='border border-3'>
                    <td class='border border-3'>Animal Year</td>
                    <td><?php animalYear($year); ?></td>
                </tr>
                <tr class='border border-3'>
                    <td class='border border-3'>Images</td>
                    <td><?php echo " " . user_img($user_image) . " " ?></td>
                </tr>
            </div>

        </table>
        <div class="d-flex justify-content-end gap-2">
            <td class="d-flex justify-content-end gap-2">
                <a href='customer_read.php' class='btn btn-danger'>Back to Customer List</a>
        </div>
        <!--we have our html table here where the record will be displayed-->

    </div>
    <!-- end .container -->


</body>

</html>