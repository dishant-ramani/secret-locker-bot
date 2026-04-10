<?php

// Include config file
require_once "config.php";

/* ==============================
   DATABASE CONNECTION
   ============================== */

$conn = mysqli_connect(
    $db_host,
    $db_user,
    $db_pass,
    $db_name
);

// Check Connection
if (!$conn) {
    die("Database Connection Failed");
}

?>