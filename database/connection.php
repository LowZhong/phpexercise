<?php
// used to connect to the database
$host = "localhost";
$db_name = "lz_online_store";
$username = "LZ_online_store";
$password = "ZrMDn.nQ.]j!jNKL";

try {
    $con = new
        PDO("mysql:host={$host};dbname={$db_name}", $username, $password);
    //echo "Connected successfully <br/>";
}

// show error
catch (PDOException $exception) {
    echo "Connection error: " . $exception->getMessage();
}