<?php
// include database connection
include 'database/delete.php';
try {     
    // get record ID
    // isset() is a PHP function used to verify if a value is there or not
    $productID=isset($_GET['productID']) ? $_GET['productID'] :  die('ERROR: Record ID not found.');

    // delete query
    $query = "DELETE FROM products WHERE productID = ?";
    $stmt = $con->prepare($query);
    $stmt->bindParam(1, $productID);
     
    if($stmt->execute()){
        // redirect to read records page and
        // tell the user record was deleted
        header('Location: product_read.php?action=deleted');
    }else{
        die('Unable to delete record.');
    }
}
// show error
catch(PDOException $exception){
    die('ERROR: ' . $exception->getMessage());
}
?>

<?php
// include database connection
include 'database/delete.php';
try {     
    // get record ID
    // isset() is a PHP function used to verify if a value is there or not
    $customerID=isset($_GET['customerID']) ? $_GET['customerID'] :  die('ERROR: Record ID not found.');

    // delete query
    $query = "DELETE FROM customer WHERE customerID = ?";
    $stmt = $con->prepare($query);
    $stmt->bindParam(1, $customerID);
     
    if($stmt->execute()){
        // redirect to read records page and
        // tell the user record was deleted
        header('Location: customer_read.php?action=deleted');
    }else{
        die('Unable to delete record.');
    }
}
// show error
catch(PDOException $exception){
    die('ERROR: ' . $exception->getMessage());
}
?>

<?php
// include database connection
include 'database/delete.php';
try {     
    // get record ID
    // isset() is a PHP function used to verify if a value is there or not
    $orderID=isset($_GET['orderID']) ? $_GET['orderID'] :  die('ERROR: Record ID not found.');

    // delete query
    $query = "DELETE FROM order_summary, order_details WHERE orderID = ?";
    $stmt = $con->prepare($query);
    $stmt->bindParam(1, $orderID);
     
    if($stmt->execute()){
        // redirect to read records page and
        // tell the user record was deleted
        header('Location: order_listing.php?action=deleted');
    }else{
        die('Unable to delete record.');
    }
}
// show error
catch(PDOException $exception){
    die('ERROR: ' . $exception->getMessage());
}
?>

