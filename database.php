<?php

/*
================================
DATABASE CONNECTION FILE
Railway MySQL Connection
================================
*/

require_once "config.php";

/*
--------------------------------
STEP 1:
Railway se DATABASE_URL variable read karo
Ye variable hum Railway dashboard me add kar chuke hain
--------------------------------
*/

$database_url = getenv("DATABASE_URL");


/*
--------------------------------
STEP 2:
URL ko parts me convert karo
Example URL:

mysql://user:pass@host:3306/dbname

Isko hum split kar rahe hain
--------------------------------
*/

$parsed = parse_url($database_url);


/*
--------------------------------
STEP 3:
Database details extract karo
Kuch bhi replace nahi karna
--------------------------------
*/

$db_host = $parsed['host'];        // Database Host
$db_user = $parsed['user'];        // Database Username
$db_pass = $parsed['pass'];        // Database Password
$db_name = ltrim($parsed['path'], '/'); // Database Name


/*
--------------------------------
STEP 4:
Database connect karo
--------------------------------
*/

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);


/*
--------------------------------
STEP 5:
Connection error check
--------------------------------
*/

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}


/*
--------------------------------
Connection Success
--------------------------------
*/

?>