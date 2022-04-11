<?php
// used to connect to the database
$host = "localhost";
$db_name = "online_store";
$username = "online_store";
$password = "Bm)i@MnOM6D!J4_J";

try {
    $con = new
        PDO("mysql:host={$host};dbname={$db_name}", $username, $password);
    //echo "Connected successfully <br/>";
}

// show error
catch (PDOException $exception) {
    echo "Connection error: " . $exception->getMessage();
}