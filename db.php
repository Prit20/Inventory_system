<?php
function connectDB($dbName) {
    $serverName = "localhost";
    $connectionOptions = array(
        "Database" => $dbName,
        "Uid" => "",
        "PWD" => ""
    );

    $conn = sqlsrv_connect($serverName, $connectionOptions);

    if (!$conn) {
        die(print_r(sqlsrv_errors(), true));
    }

    return $conn;
}

// Example:
$connWorkBook = connectDB("WorkBook");
$connRM = connectDB("RM_software");
$connInventory = connectDB("Inventory_System");

function getInventorySystemConnection() {
    return connectDB('Inventory_System');
}

?>