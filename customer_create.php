<!DOCTYPE HTML>
<html>

<head>
    <title>New Customer Sign In</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>

<body>
    <!-- container -->
    <div class="container">
        <?php include 'database/navbar.php'; ?>
        <div class="page-header">
            <h1>Customer Create Account</h1>
        </div>

        <?php
        // define variables and set to empty values
        //$usernameErr = $firstnameErr = $lastnameErr = $passwordErr = $inputconfirmPasswordErr = $birthdateErr = $genderErr = $statusErr = $starsignErr = "";
        $username = $firstname = $lastname = $password = $inputconfirmPassword = $birthdate = $gender = $status = $starsign = $email = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // include database connection
            include 'database/connection.php';
            include 'database/function.php';
            // posted values
            $username = $_POST['username'];
            $password = md5($_POST['password']); //md5 encrypt
            $inputconfirmPassword = md5($_POST['inputconfirmPassword']);
            $email = $_POST['email'];
            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $year = $_POST['year'];
            $month = $_POST['month'];
            $day = $_POST['day'];
            $birthdate = "$year/$month/$day";
            $stmt = $con->prepare("SELECT * FROM customer WHERE username= ?");
            //execute the statement
            $stmt->execute([$username]);
            //fetch result
            $user = $stmt->fetch();
            echo md5($_POST['password'])."</br>";
            echo md5($_POST['inputconfirmPassword'])."</br>";

            //function
            $error['username'] = validateUsername($username); //array call function
            $error['password'] = validatePassword($password, $inputconfirmPassword);
            $error['birthdate'] = validateAge($year, $birthdate);
            $error['gender'] = validateGender($gender);
            $error['status'] = validateStatus($status);

            $error = array_filter($error); //remove null value in the $error if there is no error msg, not have this will not update to database
            if (empty($error)) { //array里面会有nullvalue如果没有clear null value系统以为他不是empty

                try {
                    // insert query
                    $query = "INSERT INTO customer SET username=:username, password=:password, email=:email, firstname=:firstname, lastname=:lastname, birthdate=:birthdate, gender=:gender";
                    // prepare query for execution
                    $stmt = $con->prepare($query);
                    // bind the parameters
                    $stmt->bindParam(':username', $username);
                    $stmt->bindParam(':password', $password);
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':firstname', $firstname);
                    $stmt->bindParam(':lastname', $lastname);
                    $stmt->bindParam(':birthdate', $birthdate);
                    $stmt->bindParam(':gender', $gender);
                    //$stmt->bindParam(':status', $status);
                    //$stmt->bindParam(':starsign', $starsign);
                    // specify when this record was inserted to the database

                    // Execute the query
                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Record was saved.</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Unable to save record.</div>";
                    }
                }

                // show error
                catch (PDOException $exception) {
                    die('ERROR: ' . $exception->getMessage());
                }
            } else {
                foreach ($error as $value) {
                    echo "<div class='alert alert-danger'>$value <br/></div>"; //start print error msg
                }
            }
        }
        ?>

        <!-- html form here where the product information will be entered -->

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <table class='table table-hover table-responsive table-bordered'>

                <tr>
                    <td>Username</td>
                    <td><input type="text" name="username" id="username" class="form-control" placeholder="username" value="<?php echo $username; ?>"></td>
                </tr>
                <tr>
                    <td>Firstname</td>
                    <td><input type="text" name="firstname" id="firstname" class="form-control" placeholder="First name" value="<?php echo $firstname; ?>"></td>
                </tr>
                <tr>
                    <td>Lastname</td>
                    <td><input type="text" name="lastname" id="lastname" class="form-control" placeholder="Last name" value="<?php echo $lastname; ?>"></td>
                </tr>


                <tr>
                    <td>Gender</td>
                    <td><input class="form-check-input" type="radio" name="gender" id="gender" <?php if (isset($gender)&& $gender=="male") echo 'checked';?> value="male">
                        <label class="form-check-label" for="gender">Male</label>

                        <input class="form-check-input" type="radio" name="gender" id="gender" <?php if (isset($gender)&& $gender=="female") echo 'checked';?> value="female">
                        <label class="form-check-label" for="gender">female</label>
                    </td>
                </tr>

                <tr>
                    <td>Email</td>
                    <td><input type="email" class="form-control" name="email" id="email" placeholder="Email" value="<?php echo $email; ?>"></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><input type="password" class="form-control" name="password" id="password" placeholder="Password" value="<?php echo $password; ?>"></td>
                </tr>
                <tr>
                    <td>Comfirm Password</td>
                    <td><input type="password" class="form-control" id="inputconfirmPassword" name="inputconfirmPassword" placeholder="Password" value="<?php echo $inputconfirmPassword; ?>"></td>
                </tr>

                <tr>
                    <td>Date Of Birth</td>
                    <!--day-->
                    <td><?php
                        $birthdate = date('d'); //current day

                        echo '<select id="day" name="day">' . "\n";
                        for ($day = 1; $day <= 31; $day++) {
                            $selected = ($birthdate == $day ? ' selected' : '');
                            echo '<option value="' . $day . '"' . $selected . '>' . $day . '</option>' . "\n";
                        }
                        echo '</select>' . "\n";
                        ?>

                        <!--month-->
                        <?php
                        $birthdate = date('m'); //current month

                        echo '<select id="month" name="month">' . "\n";
                        for ($month = 1; $month <= 12; $month++) {
                            $selected = ($birthdate == $month ? ' selected' : '');
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
                        $selected = ($birthdate == $year ? ' selected' : '');
                        echo '<option value="' . $year . '"' . $selected . '>' . $year . '</option>' . "\n";
                    }
                    echo '</select>' . "\n";
                        ?>
                    </td>
                </tr>

                <tr>
                    <td>Account status</td>
                    <td><input class="form-check-input" type="radio" name="status" <?php if ($status == "active") echo 'checked' ?> value="active">
                        <label class="form-check-label" for="status">Active</label>

                        <input class="form-check-input" type="radio" name="status" <?php if ($status == "disabled") echo 'checked' ?>value="disabled">
                        <label class="form-check-label" for="status">Disabled</label>
                    </td>
                </tr>

                <tr>
                    <td>
                    <td>
                        <input type='submit' value='Save' class='btn btn-primary' />
                        <a href='customer_read.php' class='btn btn-danger'>Back to Customer Read</a>
                    </td>
                    </td>
                </tr>
            </table>

        </form>
    </div>
</body>

</html>