<?php

/* ==============================
   TELEGRAM BOT CONFIGURATION
   ============================== */

// Telegram Bot Token
$bot_token = "8774149133:AAF505151Jbu_Am6Nfo8szSH_7oCoaUdjSE";

// Telegram API URL
$api_url = "https://api.telegram.org/bot".$bot_token;


/* ==============================
   DATABASE CONFIGURATION (Railway)
   ============================== */

$db_host = getenv("mysql.railway.internal");
$db_user = getenv("root");
$db_pass = getenv("ZOMZTpNOLKNKHbuIdqqpkCXpLLgYwoiQ");
$db_name = getenv("railway");

?>