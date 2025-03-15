<?php
/*
который настраивает вебхук (webhook) для внешнего сервиса (в данном случае, сервиса YouGile ).
Вебхук — это механизм, который позволяет сервису автоматически отправлять HTTP-запросы (обычно POST)
на указанный URL при наступлении определенного события (например, создание задачи).
 */

//055e54dd-029a-49ba-9ce8-e2706061016d
$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => "https://ru.yougile.com/api-v2/webhooks",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "{\n  \"url\": \"" . $tunnel . "webhook_handler.php\",\n  \"event\": \"task-renamed\"\n}",
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer " . $AUTHORIZATION_ID,
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