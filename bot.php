<?php

require_once "config.php";
require_once "database.php";
require_once "functions.php";


/*
================================
Get Telegram Update
================================
*/

$content = file_get_contents("php://input");
$update = json_decode($content, true);

if(!$update){
    echo "Bot Running";
    exit;
}


/*
================================
Safe Variables
================================
*/

$chatId = $update["message"]["chat"]["id"] ?? "";
$message = $update["message"]["text"] ?? "";


/*
================================
User Data
================================
*/

$query = mysqli_query($conn,"SELECT * FROM users WHERE telegram_id='$chatId'");
$user = mysqli_fetch_assoc($query);

$locked = $user['locked'] ?? 1;
$waiting_pin = $user['waiting_pin'] ?? 0;


/*
================================
START
================================
*/

if($message == "/start"){

sendMessage($chatId,"🔐 Welcome to Secret Locker Bot

Send 4 digit PIN to create vault");

}


/*
================================
SET PIN
================================
*/

if(is_numeric($message) && strlen($message) == 4 && $waiting_pin == 0){

$pin = $message;

if(!$user){

mysqli_query($conn,"INSERT INTO users (telegram_id,pin,locked,waiting_pin) 
VALUES ('$chatId','$pin','1','0')");

sendMessage($chatId,"Vault Created 🔐

Use /unlock to open vault");

}else{

mysqli_query($conn,"UPDATE users SET pin='$pin' WHERE telegram_id='$chatId'");

sendMessage($chatId,"PIN Updated 🔐");

}

}


/*
================================
UNLOCK
================================
*/

if($message == "/unlock"){

mysqli_query($conn,"UPDATE users SET waiting_pin='1' WHERE telegram_id='$chatId'");

sendMessage($chatId,"Enter PIN 🔐");

}


/*
================================
VERIFY PIN
================================
*/

if($waiting_pin == 1 && is_numeric($message)){

if($message == $user['pin']){

mysqli_query($conn,"UPDATE users 
SET locked='0', waiting_pin='0' 
WHERE telegram_id='$chatId'");

sendMessage($chatId,"Vault Unlocked 🔓");

}else{

sendMessage($chatId,"Wrong PIN ❌");

}

}


/*
================================
LOCK
================================
*/

if($message == "/lock"){

mysqli_query($conn,"UPDATE users SET locked='1' WHERE telegram_id='$chatId'");

sendMessage($chatId,"Vault Locked 🔐");

}


/*
================================
SAVE NOTE
================================
*/

if(strpos($message,"/note") === 0){

if($locked == 1){

sendMessage($chatId,"Vault Locked 🔐
Use /unlock");

exit;

}

$note = trim(str_replace("/note","",$message));

mysqli_query($conn,"INSERT INTO vault_notes (telegram_id,note) 
VALUES ('$chatId','$note')");

sendMessage($chatId,"Note Saved 🔐");

}


/*
================================
PHOTO SAVE
================================
*/

if(isset($update["message"]["photo"])){

if($locked == 1){

sendMessage($chatId,"Vault Locked 🔐");
exit;

}

$photo = $update["message"]["photo"];
$file_id = end($photo)["file_id"];

mysqli_query($conn,"INSERT INTO vault_files 
(telegram_id,file_id,file_type) 
VALUES ('$chatId','$file_id','photo')");

sendMessage($chatId,"Photo Saved 🔐");

}


/*
================================
FILE SAVE
================================
*/

if(isset($update["message"]["document"])){

if($locked == 1){

sendMessage($chatId,"Vault Locked 🔐");
exit;

}

$file_id = $update["message"]["document"]["file_id"];

mysqli_query($conn,"INSERT INTO vault_files 
(telegram_id,file_id,file_type) 
VALUES ('$chatId','$file_id','file')");

sendMessage($chatId,"File Saved 🔐");

}

?>