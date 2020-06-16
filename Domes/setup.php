<?php
    // Login information for database
    $dbServername = "www.math-cs.ucmo.edu";
    $dbUsername = "cs4130_sp2020";
    $dbPassword = "tempPWD!";
    $dbName = "cs4130_sp2020";

    $conn = new mysqli($dbServername, $dbUsername, $dbPassword, $dbName);

    if ($conn->connect_error)
        die("Connection failed: " . $conn->connect_error);
?>