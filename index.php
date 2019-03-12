<?php

include("Telegram.php");
include("config.php");
require_once('service.php');

$telegram = new Telegram(BOT_ID);
$service = new Service($telegram);

$text = $telegram->Text();
$chat_id = $telegram->ChatID();
$message = $telegram->Message();

if($message['new_chat_member']['id']>0) {
    $telegram->deleteMessage([
        'chat_id'=>$message['chat']['id'],
        'message_id'=>$message['message_id']
    ]);
}


if($message['forward_from']['id']>0) {
    $telegram->deleteMessage([
        'chat_id'=>$message['chat']['id'],
        'message_id'=>$message['message_id']
    ]);
}