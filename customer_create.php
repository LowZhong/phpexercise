<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("location: index.php");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>New Customer Sign In</title>
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
    <div class="container">
        <?php include 'navbar/navbar.php'; ?>
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Create Account</li>
                    </ol>
                </nav>
        </nav>
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
            include 'function/function.php';
            // posted values
            $username = $_POST['username'];
            $password = $_POST['password']; //md5 encrypt
            $passwordmd5 = md5($password);
            $inputconfirmPassword = $_POST['inputconfirmPassword'];
            $email = $_POST['email'];
            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $status = isset($_POST['status']) ? $_POST['status'] : "";
            $year_ = $_POST['year'];
            $month_ = $_POST['month'];
            $day_ = $_POST['day'];
            $gender = isset($_POST['gender']) ? $_POST['gender'] : "";
            $birthdate = "$year_/$month_/$day_";
            $stmt = $con->prepare("SELECT * FROM customer WHERE customerID= ?");
            //execute the statement
            //$stmt->execute($customerID);
            //fetch result
            $customerID = $stmt->fetch();

            //function
            $error['username'] = validateUsername($username); //array call function
            $error['password'] = validatePassword($password, $inputconfirmPassword);
            $error['birthdate'] = validateAge($year_, $birthdate);
            $error['gender'] = validateGender($gender);
            $error['status'] = validateStatus($status);

            $error = array_filter($error); //remove null value in the $error if there is no error msg, not have this will not update to database
            if (empty($username) || empty($password) || empty($inputconfirmPassword) || empty($email) || empty($firstname) || empty($lastname) || empty($status) || empty($birthdate) || empty($gender)) {
                echo "<div class='alert alert-danger text-white'>Cannot Be Left Blank.</div>";
            } else if (empty($error)) { //array里面会有nullvalue如果没有clear null value系统以为他不是empty

                try {
                    // insert query
                    $query = "INSERT INTO customer SET username=:username, password=:password, email=:email, firstname=:firstname, lastname=:lastname, birthdate=:birthdate, gender=:gender, status=:status, user_image=:user_image";
                    // prepare query for execution
                    $stmt = $con->prepare($query);

                    // new 'image' field
                    $user_image = !empty($_FILES["user_image"]["name"])
                        ? sha1_file($_FILES['user_image']['tmp_name']) . "-" . basename($_FILES["user_image"]["name"])
                        : "";
                    $user_image = htmlspecialchars(strip_tags($user_image));

                    // bind the parameters
                    $stmt->bindParam(':username', $username);
                    $stmt->bindParam(':password', $password);
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':firstname', $firstname);
                    $stmt->bindParam(':lastname', $lastname);
                    $stmt->bindParam(':birthdate', $birthdate);
                    $stmt->bindParam(':gender', $gender);
                    $stmt->bindParam(':status', $status);
                    $stmt->bindParam(':user_image', $user_image);
                    // specify when this record was inserted to the database

                    // Execute the query
                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Record was saved.</div>";
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
                        echo "<div class='alert alert-danger text-white'>Unable to save record.</div>";
                    }
                }
                // show error
                catch (PDOException $exception) {
                    die('ERROR: ' . $exception->getMessage());
                }
            } else {
                foreach ($error as $value) {
                    echo "<div class='alert alert-danger text-white'>$value <br/></div>"; //start print error msg
                }
            }
        }

        ?>



        <!-- html form here where the product information will be entered -->

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <table class='table table-hover table-responsive table-bordered'>

                <div class="mb-3">
                    <tr class="border border-3">
                        <td class="border border-3 p-4">Username</td>
                        <td class="input-group input-group-outline my-2"><input type='text' name='username' class="form-control" placeholder="username" value="<?php echo $username; ?>"></td>
                    </tr>
                    <tr class="border border-3">
                        <td class="border border-3 p-4">Firstname</td>
                        <td class="input-group input-group-outline my-2"><input type='text' name='firstname' class="form-control" placeholder="First name" value="<?php echo $firstname; ?>"></td>
                    </tr>
                    <tr class="border border-3">
                        <td class="border border-3 p-4">Lastname</td>
                        <td class="input-group input-group-outline my-2"><input type='text' name='lastname' class="form-control" placeholder="Last name" value="<?php echo $lastname; ?>"></td>
                    </tr>


                    <tr class="border border-3">
                        <td class="border border-3 p-4">Gender</td>
                        <td class="p-4"><input class="form-check-input" type="radio" name="gender" <?php if ($gender == "male") echo "checked" ?> value="male">
                            <label class="form-check-label" for="gender">Male</label>

                            <input class="form-check-input" type="radio" name="gender" <?php if ($gender == "female") echo "checked" ?> value="female">
                            <label class="form-check-label" for="gender">female</label>
                        </td>
                    </tr>

                    <tr class="border border-3">
                        <td class="border border-3 p-4">Email</td>
                        <td class="input-group input-group-outline my-2"><input type='email' class='form-control' name='email' placeholder='Email' value="<?php echo $email; ?>"></td>
                    </tr>
                    <tr class="border border-3">
                        <td class="border border-3 p-4">Password</td>
                        <td class="input-group input-group-outline my-2"><input type='password' class='form-control' name='password' placeholder='Password' value="<?php echo $password; ?>"></td>
                    </tr>
                    <tr class="border border-3">
                        <td class="border border-3 p-4">Confirm Password</td>
                        <td class="input-group input-group-outline my-2"><input type='password' class='form-control' name='inputconfirmPassword' placeholder='Confirm Password' value="<?php echo $inputconfirmPassword; ?>"></td>
                    </tr>

                    <tr class="border border-3">
                        <td class="border border-3 p-4">Date Of Birth</td>
                        <!--day-->
                        <td class="p-4"><?php


                            echo '<select id="day" name="day">' . "\n";
                            for ($day = 1; $day <= 31; $day++) {
                                $selected = ($day_ == $day ? ' selected' : '');
                                echo '<option value="' . $day . '"' . $selected . '>' . $day . '</option>' . "\n";
                            }
                            echo '</select>' . "\n";
                            ?>

                            <!--month-->
                            <?php
                            $birthdate = date('m'); //current month

                            echo '<select id="month" name="month">' . "\n";
                            for ($month = 1; $month <= 12; $month++) {
                                $selected = ($month_ == $month ? ' selected' : '');
                                echo '<option value="' . $month . '"' . $selected . '>' . date('F', mktime(0, 0, 0, $month)) . '</option>' . "\n";
                            }
                            echo '</select>' . "\n";
                            if ((isset($_GET['month'])) && ($value))
                            ?>

                            <!--year-->
                            <?php
                        $year_start  = 2022;


                        echo '<select id="year" name="year">' . "\n";
                        for ($year = $year_start; $year >= 1990; $year--) {
                            $selected = ($year_ == $year ? ' selected' : '');
                            echo '<option value="' . $year . '"' . $selected . '>' . $year . '</option>' . "\n";
                        }
                        echo '</select>' . "\n";
                            ?>
                        </td>
                    </tr>

                    <tr class="border border-3">
                        <td class="border border-3 p-4">Account status</td>
                        <td class="p-4"><input class="form-check-input" type="radio" name="status" <?php if ($status == "active") echo "checked" ?> value="active">
                            <label class="form-check-label" for="status">Active</label>

                            <input class="form-check-input" type="radio" name="status" <?php if ($status == "disabled") echo "checked" ?> value="disabled">
                            <label class="form-check-label" for="status">Disabled</label>
                        </td>
                    </tr>

                    <tr class="border border-3">
                        <td class="border border-3 p-4">Upload Your Profile Image</td>
                        <td class="p-4">
                            <input type="file" name="user_image" />
                        </td>
                    </tr>


                </div>
            </table>
            <div class="d-flex justify-content-end gap-2">
                <input type='submit' value='Save' class='btn btn-primary' />
                <a href='customer_read.php' class='btn btn-danger'>Go to Customer Lists</a>
            </div>
        </form>
    </div>
</body>

</html>