<?php

/*
================================
Secret Locker Telegram Bot
================================
*/

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


/*
================================
If opened from browser
================================
*/

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
START COMMAND
================================
*/

if($message == "/start"){

sendMessage($chatId,"🔐 Welcome to Secret Locker Bot

Send 4 digit PIN to create vault");

}


/*
================================
PIN SET
================================
*/

if(is_numeric($message) && strlen($message) == 4){

$pin = $message;

$query = "SELECT * FROM users WHERE telegram_id='$chatId'";
$result = mysqli_query($conn,$query);

if(mysqli_num_rows($result) == 0){

mysqli_query($conn,"INSERT INTO users (telegram_id,pin) VALUES ('$chatId','$pin')");

sendMessage($chatId,"Vault Created 🔐

Use /unlock to open vault");

}else{

mysqli_query($conn,"UPDATE users SET pin='$pin' WHERE telegram_id='$chatId'");

sendMessage($chatId,"PIN Updated 🔐");

}

}


/*
================================
Unlock Command
================================
*/

if($message == "/unlock"){

sendMessage($chatId,"Enter PIN");

}


/*
================================
Save Note
================================
*/

if(strpos($message,"/note") === 0){

$note = trim(str_replace("/note","",$message));

mysqli_query($conn,"INSERT INTO vault_notes (telegram_id,note) VALUES ('$chatId','$note')");

sendMessage($chatId,"Note Saved 🔐");

}


/*
================================
Photo Upload
================================
*/

if(isset($update["message"]["photo"])){

$photo = $update["message"]["photo"];
$file_id = end($photo)["file_id"];

mysqli_query($conn,"INSERT INTO vault_files (telegram_id,file_id,file_type) 
VALUES ('$chatId','$file_id','photo')");

sendMessage($chatId,"Photo Saved 🔐");

}


/*
================================
Document Upload
================================
*/

if(isset($update["message"]["document"])){

$file_id = $update["message"]["document"]["file_id"];

mysqli_query($conn,"INSERT INTO vault_files (telegram_id,file_id,file_type) 
VALUES ('$chatId','$file_id','file')");

sendMessage($chatId,"File Saved 🔐");

}

?>