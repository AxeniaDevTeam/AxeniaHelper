<?php

include("Telegram.php");
include("config.php");
require_once('service.php');

$telegram = new Telegram(BOT_ID);
$service = new Service($telegram);

$text = $telegram->Text();
$chat_id = $telegram->ChatID();
$message = $telegram->Message();

if ($message['new_chat_member']['id'] > 0) {
    $telegram->deleteMessage([
        'chat_id' => $message['chat']['id'],
        'message_id' => $message['message_id']
    ]);
}

if ($message['forward_from']['id'] > 0) {
    $telegram->deleteMessage([
        'chat_id' => $message['chat']['id'],
        'message_id' => $message['message_id']
    ]);
}

if (in_array($message['from']['id'], SU)) {
    if ($text == "/reboot") {
        $service->rebootAxenia();
        $telegram->sendMessage(['chat_id' => $message['from']['id'], 'text' => 'Очередь очищена']);
    }
}

$axenia = new Telegram(AXENIA_BOT_ID);
$webhookInfoResult = $axenia->getWebhookInfo()['result'];
if ($webhookInfoResult['pending_update_count'] > 1000)
    foreach (SU as $admin) {
        $telegram->sendMessage([
            'chat_id' => $admin,
            'text' => "Слишком большая очередь (" . $webhookInfoResult['pending_update_count'] . ")\r\n" .
                "Последняя ошибка: " . $webhookInfoResult['last_error_message'] . " " . $webhookInfoResult['last_error_date'] . "\r\n\r\n" .
                "Рекомендуется сброс очереди /reboot"
        ]);
    }

if ($webhookInfoResult['pending_update_count'] > 1500) {
    $service->rebootAxenia();
    $telegram->sendMessage(['chat_id' => SU[0], 'text' => 'Автоматический сброс очереди']);
}
