<!DOCTYPE HTML>
<html lang="en">


<head>
    <title>PDO - Log In</title>
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

<body class="bg-gray-200">
    <main class="main-content  mt-0">
        <div class="page-header align-items-start min-vh-100" style="background-image: url('https://images.unsplash.com/photo-1497294815431-9365093b7331?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1950&q=80');">
            <span class="mask bg-gradient-dark opacity-6"></span>
            <div class="container my-auto">
            
                <?php
                if ($_POST) {
                    // include database connection
                    include 'database/connection.php';
                    include 'function/function.php';
                    
                    //get the key first

                    $login_username = $login_password = "";

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
                        } else if ($row['status'] == "disabled") {
                            echo "<div class='alert alert-danger'>Account Disabled</div>";
                        } else {
                            header("Location:welcome.php?username=$login_username");
                        }
                    }
                }
                ?>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                    <div class="row">
                        <div class="col-lg-4 col-md-8 col-12 mx-auto">
                            <div class="card z-index-0 fadeIn3 fadeInBottom">
                                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                                    <div class="bg-gradient-primary shadow-primary border-radius-lg py-3 pe-1">
                                        <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">Sign in</h4>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <form role="form" class="text-start">
                                        <div class="input-group input-group-outline my-3">
                                            <label class="form-label">Username</label>
                                            <input type="text" name='username' class="form-control">
                                        </div>
                                        <div class="input-group input-group-outline mb-3">
                                            <label class="form-label">Password</label>
                                            <input type="password" name='password' class="form-control">
                                        </div>
                                        <div class="text-center">
                                            <input type="submit" class="btn bg-gradient-primary w-100 my-4 mb-2"/>
                                        </div>
                                        <p class="mt-4 text-sm text-center">
                                            Don't have an account?
                                            <a href="customer_create.php" class="text-primary text-gradient font-weight-bold">Sign up</a>
                                        </p>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>

</html>