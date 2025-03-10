<?php

/*
 Этот файл webhook_handler.php представляет собой обработчик вебхука (webhook handler) на языке PHP.
Он предназначен для обработки входящих HTTP-запросов (обычно POST-запросов) от внешнего сервиса,
который отправляет данные о событиях (например, создание новой задачи).
В данном случае, скрипт обрабатывает событие создания задачи и отправляет уведомление в Telegram-канал.
 */
/*

*/

// Включаем строгую типизацию для повышения надежности кода и предотвращения неявных преобразований типов
declare(strict_types=1);

// Конфигурация
const BOT_TOKEN = 'токен_бота_телеграмма';
const CHANNEL_ID = 'айди_канала_ в_телеграмме';
const TOPIC_ID = 'айди_топика_в_канале_в_телеграмме';

// Получаем данные
$payload = file_get_contents('php://input');
$data = json_decode($payload, true);

// Формируем сообщение если это новая задача
if ($data['event'] === 'task-created') {
  $task = $data['payload'];

  $message = "📋 Новая задача!\n\n"
    . "📌 Название: {$task['title']}\n"
    . "🕐 Создана: " . date('d.m.Y H:i', (int) ($task['timestamp'] / 1000)) . "\n"
    . "📊 Статус: " . ($task['completed'] ? '✅ Завершена' : '🔄 В работе') . "\n";

  $params = http_build_query([
    'chat_id' => CHANNEL_ID,
    'message_thread_id' => TOPIC_ID,
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
    "https://api.telegram.org/bot" . BOT_TOKEN . "/sendMessage?" . $params,
    false,
    $context
  );

  // Логируем ответ для отладки
  error_log("Telegram API Response: " . $response);
}

http_response_code(200);
echo json_encode(['status' => 'success']);
