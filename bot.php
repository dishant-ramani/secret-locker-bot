<?php

require_once "config.php";
require_once "database.php";
require_once "functions.php";

$content = file_get_contents("php://input");
$update = json_decode($content, true);

if(!$update){
    echo "Bot Running";
    exit;
}

$chatId = $update["message"]["chat"]["id"] ?? "";
$message = $update["message"]["text"] ?? "";


/*
=====================
START
=====================
*/

if($message == "/start"){

sendMessage($chatId,"🔐 Welcome to Secret Locker Bot

Send 4 digit PIN to create vault");

}


/*
=====================
SET PIN
=====================
*/

if(is_numeric($message) && strlen($message) == 4){

$pin = $message;

$query = "SELECT * FROM users WHERE telegram_id='$chatId'";
$result = mysqli_query($conn,$query);

if(mysqli_num_rows($result) == 0){

mysqli_query($conn,"INSERT INTO users (telegram_id,pin,locked) VALUES ('$chatId','$pin','1')");

sendMessage($chatId,"Vault Created 🔐

Use /unlock to open vault");

}else{

mysqli_query($conn,"UPDATE users SET pin='$pin' WHERE telegram_id='$chatId'");

sendMessage($chatId,"PIN Updated 🔐");

}

}


/*
=====================
UNLOCK
=====================
*/

if($message == "/unlock"){

mysqli_query($conn,"UPDATE users SET locked='0' WHERE telegram_id='$chatId'");

sendMessage($chatId,"Vault Unlocked 🔓");

}


/*
=====================
LOCK
=====================
*/

if($message == "/lock"){

mysqli_query($conn,"UPDATE users SET locked='1' WHERE telegram_id='$chatId'");

sendMessage($chatId,"Vault Locked 🔐");

}


/*
=====================
CHECK LOCK
=====================
*/

$user = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM users WHERE telegram_id='$chatId'"));

$locked = $user['locked'] ?? 1;


/*
=====================
SAVE NOTE
=====================
*/

if(strpos($message,"/note") === 0){

if($locked == 1){

sendMessage($chatId,"Vault Locked 🔐
Use /unlock");

exit;

}

$note = trim(str_replace("/note","",$message));

mysqli_query($conn,"INSERT INTO vault_notes (telegram_id,note) VALUES ('$chatId','$note')");

sendMessage($chatId,"Note Saved 🔐");

}


/*
=====================
PHOTO
=====================
*/

if(isset($update["message"]["photo"])){

if($locked == 1){

sendMessage($chatId,"Vault Locked 🔐");
exit;

}

$photo = $update["message"]["photo"];
$file_id = end($photo)["file_id"];

mysqli_query($conn,"INSERT INTO vault_files (telegram_id,file_id,file_type) 
VALUES ('$chatId','$file_id','photo')");

sendMessage($chatId,"Photo Saved 🔐");

}


/*
=====================
FILE
=====================
*/

if(isset($update["message"]["document"])){

if($locked == 1){

sendMessage($chatId,"Vault Locked 🔐");
exit;

}

$file_id = $update["message"]["document"]["file_id"];

mysqli_query($conn,"INSERT INTO vault_files (telegram_id,file_id,file_type) 
VALUES ('$chatId','$file_id','file')");

sendMessage($chatId,"File Saved 🔐");

}

?>