<?php

require_once '../APITokens.php';

$curl = curl_init();
// сначала https://ru.yougile.com/api-v2/auth/keys потом https://ru.yougile.com/api-v2/auth/keys/get
//QZ4Htjhkx35rZA3tL+e-TmNbJheFBCdZAPga67xqfw0Ac82HFf0hETEDi9OlqO6O
curl_setopt_array($curl, [
    CURLOPT_URL => "https://ru.yougile.com/api-v2/auth/keys/get",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "{\n  \"login\": \"" . $email . "\",\n  \"password\": \"" . $password . "\",\n  \"companyId\": \"". $company_id . "\"\n}",
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