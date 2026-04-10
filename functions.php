<?php

require_once "config.php";

/* ==============================
   SEND MESSAGE FUNCTION
   ============================== */

function sendMessage($chatId, $message){

global $api_url;

$url = $api_url."/sendMessage?chat_id=".$chatId."&text=".urlencode($message);

file_get_contents($url);

}


/* ==============================
   SEND PHOTO FUNCTION
   ============================== */

function sendPhoto($chatId, $file_id){

global $api_url;

$url = $api_url."/sendPhoto?chat_id=".$chatId."&photo=".$file_id;

file_get_contents($url);

}


/* ==============================
   SEND DOCUMENT FUNCTION
   ============================== */

function sendDocument($chatId, $file_id){

global $api_url;

$url = $api_url."/sendDocument?chat_id=".$chatId."&document=".$file_id;

file_get_contents($url);

}

?>