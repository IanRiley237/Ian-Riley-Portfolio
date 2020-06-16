<?php
    // Login information for database
    $dbServername = "www.math-cs.ucmo.edu";
    $dbUsername = "S20gato";
    $dbPassword = "S20otag";
    $dbName = "S20gato";

    $conn = new mysqli($dbServername, $dbUsername, $dbPassword, $dbName);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>