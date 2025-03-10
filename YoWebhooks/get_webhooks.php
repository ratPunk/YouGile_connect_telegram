<?php
/*
Этот файл get_webhooks.php представляет собой PHP-скрипт,
который отправляет HTTP GET-запрос к API сервиса YouGile для получения списка зарегистрированных вебхуков.
 */
$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => "https://ru.yougile.com/api-v2/webhooks",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer API_KEY_YouGile",
        "Content-Type: application/json"
    ],
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