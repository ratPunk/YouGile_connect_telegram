<?php
/*
 Этот файл get_company_id.php представляет собой PHP-скрипт,
который отправляет HTTP POST-запрос к API сервиса YouGile для авторизации и получения API-ключа.
API-ключ — это токен, который используется для аутентификации при взаимодействии с API сервиса.
 */


$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => "https://ru.yougile.com/api-v2/auth/companies",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "{\n  \"login\": \"palseholder@gmail.com\",\n  \"password\": \"123\",\n  \"name\": \"YoureName\"\n}",
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