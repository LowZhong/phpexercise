<?php
// include database connection
include 'database/connection.php';


try {
    // get record ID
    // isset() is a PHP function used to verify if a value is there or not
    $productID = isset($_GET['productID']) ? $_GET['productID'] :  die('ERROR: Record ID not found.');

    // delete query
    $query = "DELETE * FROM products 
                LEFT JOIN order_details
                ON order_details.productID = products.productID
                WHERE order_details.productID IS NULL";
    $stmt = $con->prepare($query);
    //$stmt->bindParam(1, $productID);

    if ($stmt->execute()) { 
        // redirect to read records page and
        // tell the user record was deleted
        header('Location: product_read.php?action=deleted');
    } else {
        die('Unable to delete record.');
    }
}
// show error
catch (PDOException $exception) {
    die('ERROR: ' . $exception->getMessage());
}
?>

