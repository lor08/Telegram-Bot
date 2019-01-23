<?php

use App\Bot\CommonChatHandler;
use App\Bot\PrivateChatHandler;
use App\Handler;
use App\Storage\DB;
use Telegram\Bot\Api;

require __DIR__ .'/vendor/autoload.php';
$settings = require __DIR__ . '/config/settings.php';

$api = new Api($settings['token']);
$bot = new Handler($api);
$db = new DB($settings['db']);

if ($bot->getChatType() === "private") {
    (new PrivateChatHandler($bot, $db))->start();
} elseif ($bot->getChatType() === "supergroup") {
    (new CommonChatHandler($bot, $db))->start();
}



