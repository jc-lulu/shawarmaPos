<?php

$serverName = "localhost";
$username = "root";
$password = "";
$databaseName = "posSystem";

// -----Connection-----
$connection = new mysqli($serverName, $username, $password, $databaseName);
// -----Check connection-----
if ($connection->connect_error) {
    echo "Connection Failed: ", $connection->connect_error;
}
// echo "success";
