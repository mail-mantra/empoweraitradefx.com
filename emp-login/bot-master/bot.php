<?php
die;
require_once 'classes/Database.php';
require_once 'classes/Telegram.php';
require_once 'classes/BotHandler.php';

$input = file_get_contents("php://input");
$update = json_decode($input, true);

$bot = new BotHandler($update);
$bot->handle();
