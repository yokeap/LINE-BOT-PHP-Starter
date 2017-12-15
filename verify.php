<?php
$access_token = 'SCV7PNRDb7/XCuNp5C7L3n25Sv4GsKuM9zRxy5+7cBCOl7QzhQloM1WUysJ/dytJOmAuNL9K/XAdrGrmieVADiWY/uIA4lZdgWF5LQUUosltryHc2JyEcz/dgujCXoF0joDF0Z84GLmydZituZPQRAdB04t89/1O/w1cDnyilFU=;

$url = 'https://api.line.me/v2/oauth/verify';

$headers = array('Authorization: Bearer ' . $access_token);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
$result = curl_exec($ch);
curl_close($ch);

echo $result;
