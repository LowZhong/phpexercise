<?php
// include database connection
include 'database/connection.php';

$productID = isset($_GET['productID']) ? $_GET['productID'] :  die('ERROR: Record ID not found.');
$query = "SELECT productID FROM order_details WHERE productID = :productID";
$stmt = $con->prepare($query);
$stmt->bindParam(':productID', $productID);
//$row = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt->execute();
$num = $stmt->rowCount();

if ($num > 0) {
    header('Location: product_read.php?action=deleteerror');
} else {

    try {
        // get record ID
        // isset() is a PHP function used to verify if a value is there or not
        $productID = isset($_GET['productID']) ? $_GET['productID'] :  die('ERROR: Record ID not found.');
        // delete query
        $query = "DELETE FROM products WHERE productID = ?";
        $stmt = $con->prepare($query);
        $stmt->bindParam(1, $productID);
        
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
}
