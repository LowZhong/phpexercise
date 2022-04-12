<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Order Create</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</head>



<!-- nav bar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">

    <div class="container-fluid">
        <a class="navbar-brand" href="login.php"> <img src='images/phplogo.png' width="110" height="45" alt="php"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Customer
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="customers_read.php">My Customers</a></li>
                        <li><a class="dropdown-item" href="customers_create.php">Create New</a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Product
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="product_read.php">My Products</a></li>
                        <li><a class="dropdown-item" href="product_create.php">Create New</a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Order
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="order_summary.php">My Orders</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="order_create.php">New Order</a></li>
                    </ul>
                </li>
            </ul>

            <!-- Search Bar
      <form class="d-flex">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>-->
        </div>
    </div>
</nav>



<body>
    <div class="container">
        <div class="page-header mt-3 pb-2">
            <h1>Create Order</h1>
        </div>

        <?php
        include 'onlineshop_database/connection.php';
        include 'function/function.php';

        if ($_POST) {
            // posted values
            $Username = $_POST['Username']; // fr customers
            $product_id = $_POST['product_id']; // fr products
            // (make $Qty1 - $Qty3 in array)
            $Qty = $_POST['Qty'];


            //selectedproduct = (submit)product
            $selectedproduct = array($product_id[0], $product_id[1], $product_id[2]);
            //count total of array 
            $totalsp = count($selectedproduct);

            // now totalsp store 3 products in this array


            if (empty($_POST['Username']) || empty($_POST['product_id']) || empty($_POST['Qty'])) {
                echo "<div class='alert alert-danger'>Please Fill In blanks.</div>";
            } else if (count(array_unique($selectedproduct)) != $totalsp) {
                // if user didn't select 3 items which is equal to the totalsp array we created
                echo "<div class='alert alert-danger'>Please Choose 3 different Products</div>";
            } else if ($selectedproduct = 0) {
                // if no product select, print error select product
                echo "<div class='alert alert-danger'>Please Select Products</div>";
            } else {

                if (count($Qty) != 0) {
                    try {
                        $query = "INSERT INTO order_summary (Customer_id) VALUES (?)";

                        // prepare query for execute
                        $stmt = $con->prepare($query);

                        // bindparam
                        $stmt->bindParam(1, $Username);

                        // Execute the query
                        if ($stmt->execute()) {
                            $Order_id = $con->lastInsertId();


                            for ($x = 0; $x < 3; $x++) {
                                $query = "INSERT INTO order_details (Order_id, Product_id, Qty) VALUES (?, ?, ?)";

                                $stmt = $con->prepare($query);

                                $stmt->bindParam(1, $Order_id);


                                // input [$x] to loop the foreach func 1-3 **
                                $stmt->bindParam(2, $product_id[$x]);

                                // input [$x] to loop the Qty [0-2] **
                                $stmt->bindParam(3, $Qty[$x]);


                                // == same content [0-2] == 2
                                if ($stmt->execute()) {
                                    if ($x == 2) {
                                        echo "<div class='alert alert-success'>Record saved.</div>";
                                    }
                                } else if ($x < 2) {
                                    echo "<div class='alert alert-danger'>Unabled to save record.</div>";
                                }
                            }
                        }
                    } catch (PDOException $exception) {
                        die('ERROR: ' . $exception->getMessage());
                    }
                }
            }
        }

        // nothing problems -> move on 

        $products = array();
        $query = "SELECT product_id, name FROM products";

        $stmt = $con->prepare($query);

        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $products[] = $row;
        }


        $query = "SELECT Username FROM customers";

        $stmt = $con->prepare($query);

        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $user[] = $row;
        }
        ?>



        <!-- html form area -->

        <!-- option value code sample : bit.ly/3reu6TR -->
        <!-- How to write For each : https://bit.ly/3jkz6SK -->
        <!-- foreach ($customer as $c){ -->

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <table class='table table-hover table-responsive table-bordered'>

                <!-- Username Field -->
                <tr>
                    <div class="<d-flex flex-row mb-2">
                        <td>Username</td>
                        <td class="btn-group w-100">
                            <div class="w-50 p-2">
                                <?php
                                $selected = isset($_POST["Username"]) ? $_POST["Username"] : "";
                                ?>
                                <select name='Username' class="form-control">
                                    <option value="">--- Your Username ---</option>
                                    <?php
                                    foreach ($user as $u) { ?>
                                        <option value="<?php echo $u['Username']; ?>" <?php if ($u['Username'] == $selected) echo "selected"; ?> value=$selected>
                                            <?php echo $u['Username']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                    </div>
                </tr>


                <!-- Product1 & Qty1 Field -->
                <tr>
                    <td>Products</td>
                    <td class="btn-group w-100">
                        <div class="w-50 p-2">
                            <label for="P">Product</label>
                            <?php
                            $selected = isset($_POST['product_id']) ? $product_id[0] : "";
                            // attach array fr line 29
                            ?>
                            <select name='product_id[]' class="form-control">
                                <option value="">--- Choose item ---</option>
                                <?php
                                foreach ($products as $p) { ?>
                                    <option value="<?php echo $p['product_id']; ?>" <?php if ($p['name'] == $selected) echo "selected"; ?> value=$selected>
                                        <?php echo $p['name']; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class=p-2>
                            <label for="Qty">Quantity</label>
                            <input type='number' min=0 name='Qty[]' class="form-control" value="<?php echo isset($_POST['Qty']) ? $Qty[0] : ""; ?>" />
                        </div>
                </tr>


                <!-- Product2 & Qty2 Field -->
                <tr>
                    <td></td>
                    <td class="btn-group w-100">
                        <div class="w-50 p-2">
                            <?php
                            $selected = isset($_POST['product_id']) ? $product_id[1] : "";
                            ?>
                            <select name='product_id[]' class="form-control">
                                <option value="">--- Choose item ---</option>
                                <?php
                                foreach ($products as $p) { ?>
                                    <option value="<?php echo $p['product_id']; ?>" <?php if ($p['name'] == $selected) echo "selected"; ?> value=$selected>
                                        <?php echo $p['name']; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class=p-2>
                            <input type='number' min=0 name='Qty[]' class="form-control" value="<?php echo isset($_POST['Qty']) ? $Qty[1] : ""; ?>" />
                        </div>
    </div>
    </tr>


    <!-- Product3 & Qty3 Field -->
    <tr>
        <td></td>
        <td class="btn-group w-100">
            <div class="w-50 p-2">
                <?php
                $selected = isset($_POST['product_id']) ? $product_id[2] : "";
                ?>
                <select name='product_id[]' class="form-control">
                    <option value="">--- Choose item ---</option>
                    <?php
                    foreach ($products as $p) { ?>
                        <option value="<?php echo $p['product_id']; ?>" <?php if ($p['name'] == $selected) echo "selected"; ?> value=$selected>
                            <?php echo $p['name']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class=p-2>
                <input type='number' min=0 name='Qty[]' class="form-control" value="<?php echo isset($_POST['Qty']) ? $Qty[2] : ""; ?>" />
            </div>
            </div>
    </tr>
    </div>

    <!--submit btn for order-->
    <tr>
        <td></td>
        <td>
            <input type='submit' value='Save' class='btn btn-primary' />
            <a href='product_read.php' class='btn btn-danger'>Back</a>
        </td>
    </tr>

    </table>
    </form>
    </div>

</body>

</html>

<!-- Task Left To Do-->
<!-- 1) Check Button 
     2) Make 3Fields below here in for loop type
     3) Add Header to all page -->