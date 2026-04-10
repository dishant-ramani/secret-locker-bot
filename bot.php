<?php

require_once "config.php";
require_once "database.php";
require_once "functions.php";

/* ==============================
   GET TELEGRAM UPDATE
   ============================== */

$update = file_get_contents("php://input");
$update = json_decode($update, TRUE);

// Get user info
$chatId = $update["message"]["chat"]["id"];
$message = $update["message"]["text"];


/* ==============================
   START COMMAND
   ============================== */

if($message == "/start"){

sendMessage($chatId,"🔐 Welcome to Private Vault Bot

Send 4 digit PIN to create your secure vault");

}


/* ==============================
   SET / UPDATE PIN
   ============================== */

if(is_numeric($message) && strlen($message) == 4){

$pin = $message;

$query = "SELECT * FROM users WHERE telegram_id='$chatId'";
$result = mysqli_query($conn,$query);

if(mysqli_num_rows($result) == 0){

mysqli_query($conn,"INSERT INTO users (telegram_id,pin) 
VALUES ('$chatId','$pin')");

sendMessage($chatId,"✅ Vault Created Successfully

Use /unlock to open vault");

}else{

mysqli_query($conn,"UPDATE users SET pin='$pin' 
WHERE telegram_id='$chatId'");

sendMessage($chatId,"✅ PIN Updated Successfully");

}

}


/* ==============================
   UNLOCK COMMAND
   ============================== */

if($message == "/unlock"){

sendMessage($chatId,"Enter your PIN");

}


/* ==============================
   LOCK COMMAND
   ============================== */

if($message == "/lock"){

sendMessage($chatId,"🔒 Vault Locked");

}


/* ==============================
   SAVE NOTE COMMAND
   ============================== */

if(strpos($message,"/note") === 0){

$note = str_replace("/note","",$message);

mysqli_query($conn,"INSERT INTO vault_notes 
(telegram_id,note) 
VALUES ('$chatId','$note')");

sendMessage($chatId,"📝 Note Saved Securely");

}


/* ==============================
   PHOTO UPLOAD
   ============================== */

if(isset($update["message"]["photo"])){

$photo = $update["message"]["photo"];
$file_id = end($photo)["file_id"];

mysqli_query($conn,"INSERT INTO vault_files 
(telegram_id,file_id,file_type)
VALUES 
('$chatId','$file_id','photo')");

sendMessage($chatId,"📷 Photo Saved Securely");

}


/* ==============================
   DOCUMENT UPLOAD
   ============================== */

if(isset($update["message"]["document"])){

$file_id = $update["message"]["document"]["file_id"];

mysqli_query($conn,"INSERT INTO vault_files 
(telegram_id,file_id,file_type)
VALUES 
('$chatId','$file_id','file')");

sendMessage($chatId,"📁 File Saved Securely");

}

?>