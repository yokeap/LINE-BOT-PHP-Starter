<?php

$channelSecret = '15d90e2bb96166ade76b47a77313fdaf';

$userId = 'U9dfdf70cfb887d01e5ce9c92e33273b2';

$access_token = 'SCV7PNRDb7/XCuNp5C7L3n25Sv4GsKuM9zRxy5+7cBCOl7QzhQloM1WUysJ/dytJOmAuNL9K/XAdrGrmieVADiWY/uIA4lZdgWF5LQUUosltryHc2JyEcz/dgujCXoF0joDF0Z84GLmydZituZPQRAdB04t89/1O/w1cDnyilFU=';

$url = 'https://api.line.me/oauth2/v2.1/verify';

$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($access_token);
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $channelSecret]);
$response = $bot->getProfile($userId);
if ($response->isSucceeded()) {
    $profile = $response->getJSONDecodedBody();
    echo $profile['displayName'];
    echo $profile['pictureUrl'];
    echo $profile['statusMessage'];
}