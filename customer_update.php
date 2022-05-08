<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Read Records - PHP CRUD Tutorial</title>
    <?php
    include 'head/head.php';
    ?>
    <!-- custom css -->
    <style>
        .m-r-1em {
            margin-right: 1em;
        }

        .m-b-1em {
            margin-bottom: 1em;
        }

        .m-l-1em {
            margin-left: 1em;
        }

        .mt0 {
            margin-top: 0;
        }
    </style>
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
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Update Customers</li>
                    </ol>
                </nav>
            </div>
        </nav>
        <div class="page-header">
            <h1>Update Customer</h1>
        </div>
        <!-- PHP read record by ID will be here -->
        <?php
        // get passed parameter value, in this case, the record ID
        // isset() is a PHP function used to verify if a value is there or not
        $customerID = isset($_GET['customerID']) ? $_GET['customerID'] : die('ERROR: Record ID not found.');
        //$username = $firstname = $lastname = $password = $inputconfirmPassword = $birthdate = $gender = $status = $starsign = $email = "";

        //include database connection
        include 'database/connection.php';
        include 'function/function.php';

        //-- PHP post to update record will be here --

        // check if form was submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // posted values 
            $username = htmlspecialchars(strip_tags($_POST['username']));
            $email = htmlspecialchars(strip_tags($_POST['email']));
            $password = htmlspecialchars(strip_tags($_POST['password']));
            $firstname = htmlspecialchars(strip_tags($_POST['firstname']));
            $lastname = htmlspecialchars(strip_tags($_POST['lastname']));
            $year = htmlspecialchars(strip_tags($_POST['year']));
            $gender = htmlspecialchars(strip_tags($_POST['gender']));
            $status = htmlspecialchars(strip_tags($_POST['status']));
            $birthdate =  htmlspecialchars(strip_tags($_POST['year'])) . "-" . htmlspecialchars(strip_tags($_POST['month'])) . "-" . htmlspecialchars(strip_tags($_POST['day']));
            /*echo $status = $_POST['status']."</br>";
            echo $gender = $_POST['gender'];*/


            $error['username'] = validateUsername($username); //array call function
            $error['password'] = validateOrderPassword($password);
            $error['birthdate'] = validateAge($year, $birthdate);
            $error['birthdate'] = validateStatus($status);
            $error = array_filter($error);

            if (empty($error)) {
                try {
                    // write update query
                    $query = "UPDATE customer SET username=:username, email=:email, password=:password, firstname=:firstname, lastname=:lastname, gender=:gender, birthdate=:birthdate, status=:status WHERE customerID = :customerID";
                    // prepare query for excecution
                    $stmt = $con->prepare($query);

                    // new 'image' field
                    $user_image = !empty($_FILES["user_image"]["name"])
                        ? sha1_file($_FILES['user_image']['tmp_name']) . "-" . basename($_FILES["user_image"]["name"])
                        : "";
                    $user_image = htmlspecialchars(strip_tags($user_image));

                    // bind the parameters
                    $stmt->bindParam(':customerID', $customerID);
                    $stmt->bindParam(':username', $username);
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':password', $password);
                    $stmt->bindParam(':firstname', $firstname);
                    $stmt->bindParam(':lastname', $lastname);
                    $stmt->bindParam(':gender', $gender);
                    $stmt->bindParam(':birthdate', $birthdate);
                    $stmt->bindParam(':status', $status);
                    $stmt->bindParam(':status', $user_image);

                    // Execute the query
                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Record was updated.</div>";
                        if ($user_image) {
                            $target_directory = "uploads/";
                            // make sure the 'uploads' folder exists
                            // if not, create it
                            if (!is_dir($target_directory)) {
                                mkdir($target_directory, 0777, true);
                            }
                            $target_file = $target_directory . $user_image;

                            // make sure file does not exist
                            if (file_exists($target_file)) {
                                $file_upload_error_messages .= "<div>Image already exists. Try to change file name.</div>";
                            }

                            // check the extension of the upload file
                            $file_type = pathinfo($target_file, PATHINFO_EXTENSION);
                            // make sure certain file types are allowed
                            $allowed_file_types = array("jpg", "png");
                            if (!in_array($file_type, $allowed_file_types)) {
                                $file_upload_error_messages .= "<div>Only JPG or PNG files are allowed.</div>";
                            }
                            // make sure submitted file is not too large
                            if ($_FILES['user_image']['size'] > (5242880)) {
                                $file_upload_error_messages .= "<div>Image must be less than 5 MB in size.</div>";
                            }
                            // make sure the 'uploads' folder exists
                            // if not, create it
                            if (!is_dir($target_directory)) {
                                mkdir($target_directory, 0777, true);
                            }
                            // if $file_upload_error_messages is still empty
                            if (empty($file_upload_error_messages)) {
                                // it means there are no errors, so try to upload the file
                                if (move_uploaded_file($_FILES["user_image"]["tmp_name"], $target_file)) {
                                    // it means photo was uploaded
                                } else {
                                    echo "<div class='alert alert-danger text-white'>";
                                    echo "<div>Unable to upload photo.</div>";
                                    echo "<div>Update the record to upload photo.</div>";
                                    echo "</div>";
                                }
                            }
                            // if $file_upload_error_messages is NOT empty
                            else {
                                // it means there are some errors, so show them to user
                                echo "<div class='alert alert-danger text-white'>";
                                echo "<div>{$file_upload_error_messages}</div>";
                                echo "<div>Update the record to upload photo.</div>";
                                echo "</div>";
                            }
                        } else {
                            echo "no file selected.";
                        }
                    } else {
                        echo "<div class='alert alert-danger text-white'>Unable to update record. Please try again.</div>";
                    }
                }
                // show errors
                catch (PDOException $exception) {
                    die('ERROR: ' . $exception->getMessage());
                }
            } else {
                foreach ($error as $value) {
                    echo "<div class='alert alert-danger text-white'>$value <br/></div>"; //start print error msg
                }
            }
        }


        // read current record's data
        try {
            // prepare select query
            $query = "SELECT customerID, username, password, email, firstname, lastname, gender, DAY(birthdate) as day, MONTH(birthdate) as month, YEAR(birthdate) as year, status FROM customer WHERE customerID = ? ";
            $stmt = $con->prepare($query);

            // this is the first question mark
            $stmt->bindParam(1, $customerID);
            // execute our query
            $stmt->execute();
            // store retrieved row to a variable
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            // values to fill up our form
            $username = $row['username'];
            $email = $row['email'];
            $password = $row['password'];
            $firstname = $row['firstname'];
            $lastname = $row['lastname'];
            $gender = $row['gender'];
            $lastname = $row['lastname'];
            $dobyear = $row['year'];
            $dobmonth = $row['month'];
            $dobday = $row['day'];
            $status = $row['status'];
        }
        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>

        <!-- HTML form to update record will be here -->
        <!--we have our html form here where new record information can be updated-->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?customerID={$customerID}"); ?>" method="post">
            <table class='table table-hover table-responsive table-bordered'>
                <div class="mb-3">
                    <tr class='border border-3'>
                        <td class="border border-3 p-4">Username</td>
                        <td class="input-group input-group-outline my-2"><input name='username' <?php echo htmlspecialchars($username, ENT_QUOTES); ?> class='form-control' value="<?php echo $username; ?>" /></td>
                    </tr>
                    <tr class='border border-3'>
                        <td class="border border-3 p-4">Email</td>
                        <td class="input-group input-group-outline my-2"><input name='email' <?php echo htmlspecialchars($email, ENT_QUOTES); ?> class='form-control' value="<?php echo $email; ?>" /></td>
                    </tr>
                    <tr class='border border-3'>
                        <td class="border border-3 p-4">Password</td>
                        <td class="input-group input-group-outline my-2"><input name='password' <?php echo htmlspecialchars($password, ENT_QUOTES); ?> class='form-control' value="<?php echo $password; ?>" /></td>
                    </tr>
                    <tr class='border border-3'>
                        <td class="border border-3 p-4">Firstname</td>
                        <td class="input-group input-group-outline my-2"><input name='firstname' <?php echo htmlspecialchars($firstname, ENT_QUOTES); ?> class='form-control' value="<?php echo $firstname; ?>" /></td>
                    </tr>
                    <tr class='border border-3'>
                        <td class="border border-3 p-4">lastname</td>
                        <td class="input-group input-group-outline my-2"><input name='lastname' <?php echo htmlspecialchars($lastname, ENT_QUOTES); ?> class='form-control' value="<?php echo $lastname; ?>" /></td>
                    </tr>
                    <tr class='border border-3'>
                        <td class="border border-3 p-4">Gender</td>
                        <td class="p-4"><input class="form-check-input" type="radio" name="gender" id="gender" <?php if ($gender == "male") echo "checked" ?> value="male">
                            <label class="form-check-label" for="gender">
                                Male
                            </label>
                            <input class="form-check-input" type="radio" name="gender" id="gender" <?php if ($gender == "female") echo "checked" ?> value="female">
                            <label class="form-check-label" for="gender">
                                Female
                            </label>
                        </td>
                    </tr>

                    <tr class='border border-3'>
                        <td class="border border-3 p-4">Account Status</td>
                        <td class="p-4"><input class="form-check-input" type="radio" name="status" id="status" <?php if ($status == "active") echo "checked" ?> value="active">
                            <label class="form-check-label" for="status">
                                Active
                            </label>
                            <input class="form-check-input" type="radio" name="status" id="status" <?php if ($status == "disabled") echo "checked" ?> value="disabled">
                            <label class="form-check-label" for="status">
                                Disabled
                            </label>
                        </td>
                    </tr>

                    <tr class='border border-3'>
                        <td class="border border-3 p-4">Date Of Birth</td>
                        <!--day-->
                        <td class="p-4">
                            <?php

                            echo '<select id="day" name="day">' . "\n";
                            for ($day = 1; $day <= 31; $day++) {
                                $selected = ($dobday == $day ? ' selected' : '');
                                echo '<option value="' . $day . '"' . $selected . '>' . $day . '</option>' . "\n";
                            }
                            echo '</select>' . "\n";
                            ?>

                            <!--month-->
                            <?php

                            echo '<select id="month" name="month">' . "\n";
                            for ($month = 1; $month <= 12; $month++) {
                                $selected = ($dobmonth == $month ? ' selected' : '');
                                echo '<option value="' . $month . '"' . $selected . '>' . date('F', mktime(0, 0, 0, $month)) . '</option>' . "\n";
                            }
                            echo '</select>' . "\n";
                            if ((isset($_GET['month'])) && ($value))
                            ?>

                            <!--year-->
                            <?php
                        $year_start  = 2022;
                        $birthdate = 2022;

                        echo '<select id="year" name="year">' . "\n";
                        for ($year = $year_start; $year >= 1990; $year--) {
                            $selected = ($dobyear == $year ? ' selected' : '');
                            echo '<option value="' . $year . '"' . $selected . '>' . $year . '</option>' . "\n";
                        }
                        echo '</select>' . "\n";
                            ?>
                        </td>
                    <tr>

                    <tr class='border border-3'>
                        <td class="border border-3 p-4">Update Your Profile Picture</td>
                        <td class="p-4">
                            <input type="file" name="image" />
                        </td>
                    </tr>
                </div>
            </table>
            <div class="d-flex justify-content-end gap-2">
                <td class="d-flex justify-content-end gap-2">
                    <input type='submit' value='Save Changes' class='btn btn-primary' />
                    <a href='customer_read.php' class='btn btn-danger'>Back to Customer List</a>
            </div>
        </form>




    </div>
    <!-- end .container -->
</body>

</html>