<?php
// include database connection
include 'database/connection.php';

$customerID = isset($_GET['customerID']) ? $_GET['customerID'] :  die('ERROR: Record ID not found.');
$query = "SELECT customerID FROM order_summary WHERE customerID = :customerID";
$stmt = $con->prepare($query);
$stmt->bindParam(':customerID', $customerID);
//$row = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt->execute();
$num = $stmt->rowCount();

if ($num > 0) {
    header('Location: customer_read.php?action=deleteerror');
} else {

    try {
        // get record ID
        // isset() is a PHP function used to verify if a value is there or not
        $customerID = isset($_GET['customerID']) ? $_GET['customerID'] :  die('ERROR: Record ID not found.');
        // delete query
        $query = "DELETE FROM customer WHERE customerID = ?";
        $stmt = $con->prepare($query);
        $stmt->bindParam(1, $customerID);
        
        if ($stmt->execute()) {
            // redirect to read records page and
            // tell the user record was deleted
            header('Location: customer_read.php?action=deleted');
        } else {
            die('Unable to delete record.');
        }
    }
    // show error
    catch (PDOException $exception) {
        die('ERROR: ' . $exception->getMessage());
    }
}