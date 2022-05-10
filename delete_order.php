<?php
// include database connection
include 'database/connection.php';

$orderID = isset($_GET['orderID']) ? $_GET['orderID'] :  die('ERROR: Record ID not found.');
$query = "SELECT orderID FROM order_summary WHERE orderID = :orderID";
$stmt = $con->prepare($query);
$stmt->bindParam(':orderID', $orderID);
//$row = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt->execute();
$num = $stmt->rowCount();

if ($num > 0) {
    header('Location: order_listing.php?action=deleteerror');
} else {

    try {
        // get record ID
        // isset() is a PHP function used to verify if a value is there or not
        $orderID = isset($_GET['orderID']) ? $_GET['orderID'] :  die('ERROR: Record ID not found.');
        // delete query
        $query = "DELETE order_summary.orderID, order_details.orderID FROM order_summary INNER JOIN order_details ON order_summary.orderID = order_details.orderID WHERE order_summary.orderID = ?";
        $stmt = $con->prepare($query);
        $stmt->bindParam(1, $orderID);
        
        if ($stmt->execute()) {
            // redirect to read records page and
            // tell the user record was deleted
            header('Location: order_listing.php?action=deleted');
        } else {
            die('Unable to delete record.');
        }
    }
    // show error
    catch (PDOException $exception) {
        die('ERROR: ' . $exception->getMessage());
    }
}