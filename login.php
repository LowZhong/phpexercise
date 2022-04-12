<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Create a Record - PHP CRUD Tutorial</title>
    <!-- Latest compiled and minified Bootstrap CSS (Apply your Bootstrap here -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Login Your Account</h1>
        </div>

        <?php
        if ($_POST) {
            // include database connection
            include 'database/connection.php';
            include 'database/function.php';
            //get the key first

            $login_username = $login_password ="";

            $login_username = $_POST['username'];
            $login_password = $_POST['password'];

            if (empty($login_username) || empty($login_password)) { 
                echo "<div class='alert alert-danger'>Cannot Be Left Blank.</div>";
            } else {
                // select all data
                $query = "SELECT * FROM customer WHERE username = ?";
                $stmt = $con->prepare($query);
                $stmt->bindParam(1, $login_username);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($stmt->rowCount() == 0) { //if find error
                    echo "<div class='alert alert-danger'>User Not Found</div>";
                } else if ($login_password != $row['password']) {
                    echo "<div class='alert alert-danger'>Wrong Password</div>";
                } else if ($row['status'] == "disabled"){
                    echo "<div class='alert alert-danger'>Account Disabled</div>";
                } else {
                    header("Location:welcome.php?username=$login_username");
                }
            }
        }
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post"> 
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Username</td>
                    <td>
                        <input type='text' name='username' class='form-control'  />
                    </td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><input type='password' name='password' class='form-control' />
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' name='login' value='login' class='btn btn-primary' />

                    </td>
                </tr>
            </table>
        </form>


    </div>
    <!-- end .container -->
</body>

</html>