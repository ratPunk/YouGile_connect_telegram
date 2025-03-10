<?php
$curl = curl_init();
// сначала нужно установить ключ через https://ru.yougile.com/api-v2/auth/keys потом получить егоо через этот запрс https://ru.yougile.com/api-v2/auth/keys/get

curl_setopt_array($curl, [
    CURLOPT_URL => "https://ru.yougile.com/api-v2/auth/keys",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "{\n  \"login\": \"plaseholder@gmail.com\",\n  \"password\": \"123\",\n  \"companyId\": \"youre_company_id\"\n}",
    CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
]);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    echo $response;
}


$login = "plaseholder@gmail.com";
$password = "123";
$companyId = "youre_company_id";

$data = json_encode([
    "login" => $login,
    "password" => $password,
    "companyId" => $companyId
]);

$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => "https://ru.yougile.com/api-v2/auth/keys/get",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => $data,
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json"
    ],
    CURLOPT_SSL_VERIFYHOST => 0, // Отключить проверку SSL (не рекомендуется для продакшена)
    CURLOPT_SSL_VERIFYPEER => 0  // Отключить проверку SSL (не рекомендуется для продакшена)
]);

$response = curl_exec($curl);
$err = curl_error($curl);

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    if ($httpCode === 200) {
        // Преобразуем ответ в массив или объект
        $responseData = json_decode($response, true);

        // Логируем ответ для отладки
        error_log("Response Data: " . print_r($responseData, true));

        // Проверяем, что ответ не пустой
        if (empty($responseData)) {
            echo "Ответ пустой. Проверьте учетные данные и параметры запроса.";
        } else {
            // Если ответ — массив, перебираем его
            if (is_array($responseData)) {
                foreach ($responseData as $item) {
                    print_r($item);
                }
            } else {
                echo "Ответ не является массивом: " . print_r($responseData, true);
            }
        }
    } else {
        echo "Ошибка: HTTP код " . $httpCode . ", ответ: " . $response;
    }
}