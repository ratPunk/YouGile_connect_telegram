<?php


function writeToFile($line1, $line2)
{
    $filename = 'connect_mes_and_task.txt';

    // Открываем файл для записи (если файл не существует, он будет создан)
    $file = fopen($filename, 'a'); // 'a' - режим добавления в конец файла

    if (!$file) {
        // Если файл не удалось открыть, возвращаем false
        return false;
    }

    // Формируем строку для записи
    $data = $line1 . ':' . $line2 . PHP_EOL; // PHP_EOL - символ переноса строки

    // Записываем данные в файл
    $result = fwrite($file, $data);

    // Закрываем файл
    fclose($file);

    // Возвращаем true, если запись прошла успешно
    return $result !== false;
}






function findMessageId($TaskId)
{
    $filename = 'connect_mes_and_task.txt';
    // Открываем файл для чтения
    $file = fopen($filename, 'r');

    if (!$file) {
        error_log("Не удалось открыть файл: " . $filename);
        return null;
    }

    // Читаем файл построчно
    while (($line = fgets($file)) !== false) {
        // Убираем лишние пробелы и символы новой строки
        $line = trim($line);

        // Разделяем строку по символу ':'
        $parts = explode(':', $line);

        // Проверяем, что строка содержит два значения
        if (count($parts) === 2) {
            // Если левое значение совпадает, возвращаем правое
            if ($parts[0] === $TaskId) {
                fclose($file);
                return $parts[1];
            }
        }
    }

    // Закрываем файл
    fclose($file);

    // Если строка не найдена, возвращаем null
    return null;
}