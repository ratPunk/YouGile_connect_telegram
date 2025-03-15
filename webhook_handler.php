<?php

/*
 Этот файл webhook_handler.php представляет собой обработчик вебхука (webhook handler) на языке PHP.
Он предназначен для обработки входящих HTTP-запросов (обычно POST-запросов) от внешнего сервиса,
который отправляет данные о событиях (например, создание новой задачи).
В данном случае, скрипт обрабатывает событие создания задачи и отправляет уведомление в Telegram-канал.
 */

declare(strict_types=1);

require_once 'logging.php';
require_once 'APITokens.php';

$payload = file_get_contents('php://input');
error_log("YouAgile data: " . $payload);
$dataYo = json_decode($payload, true);


if ($dataYo['event'] === 'task-created') {
    $task = $dataYo['payload'];

    $message = "📋 Новая задача!\n\n"
        . "📌 Название: {$task['title']}\n"
        . "🕐 Создана: " . date('d.m.Y H:i', (int) ($task['timestamp'] / 1000)) . "\n"
        . "📊 Статус: " . ($task['completed'] ? '✅ Завершена' : '🔄 В работе') . "\n";

    $params = http_build_query([
      'chat_id' => $CHANNEL_ID,
      'message_thread_id' => $TOPIC_ID,
      'text' => $message,
      'parse_mode' => 'HTML'
    ]);

    // Создаем контекст для игнорирования проверки SSL
    $context = stream_context_create([
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false
        ]
    ]);

    // Отправляем запрос с контекстом
    $response = file_get_contents(
      "https://api.telegram.org/bot" . $BOT_TOKEN . "/sendMessage?" . $params,
      false,
      $context
    );

    error_log("Telegram API Response: " . $response);

    $dataTg = json_decode($response, true);
    $result = $dataTg['result'];

    writeToFile($task['id'], $result['message_id']);

}elseif ($dataYo['event'] === "task-deleted"){
    $task = $dataYo['payload'];

    $messageId = findMessageId($task['id']);

    $params = http_build_query([
        'chat_id' => $CHANNEL_ID,
        'message_thread_id' => $TOPIC_ID,
        'message_id' => $messageId,
        'parse_mode' => 'HTML'
    ]);

    $context = stream_context_create([
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false
        ]
    ]);

    $response = file_get_contents(
        "https://api.telegram.org/bot" . $BOT_TOKEN . "/deleteMessage?" . $params,
        false,
        $context
    );

    error_log("Telegram API Response: " . $response);
}elseif ($dataYo['event'] === 'task-renamed'){
    $task = $dataYo['payload'];

    $messageId = findMessageId($task['id']);

    $message = "📋 Новая задача!\n\n"
        . "📌 Название: {$task['title']}\n"
        . "🕐 Создана: " . date('d.m.Y H:i', (int) ($task['timestamp'] / 1000)) . "\n"
        . "📊 Статус: " . ($task['completed'] ? '✅ Завершена' : '🔄 В работе') . "\n";

    $params = http_build_query([
        'chat_id' => $CHANNEL_ID,
        'message_thread_id' => $TOPIC_ID,
        'message_id' => $messageId,
        'text' => $message,
        'parse_mode' => 'HTML'
    ]);

    $context = stream_context_create([
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false
        ]
    ]);

    $response = file_get_contents(
        "https://api.telegram.org/bot" . $BOT_TOKEN . "/editMessageText?" . $params,
        false,
        $context
    );
}

http_response_code(200);
echo json_encode(['status' => 'success']);

