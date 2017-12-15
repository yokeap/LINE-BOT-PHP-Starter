<?php
require('vendor/autoload.php');
use \LINE\LINEBot\HTTPClient\CurlHTTPClient;
use \LINE\LINEBot;

require('phpMQTT.php');

$mqtt = new phpMQTT('www.m14.cloudmqtt.com', 19348, 'phpMQTT Pub Example'); /

$token = 'SCV7PNRDb7/XCuNp5C7L3n25Sv4GsKuM9zRxy5+7cBCOl7QzhQloM1WUysJ/dytJOmAuNL9K/XAdrGrmieVADiWY/uIA4lZdgWF5LQUUosltryHc2JyEcz/dgujCXoF0joDF0Z84GLmydZituZPQRAdB04t89/1O/w1cDnyilFU='; 
$httpClient = new CurlHTTPClient($token);
$bot = new LINEBot($httpClient, [‘channelSecret’ => $token]);
// webhook
$jsonStr = file_get_contents(‘php://input’);
$jsonObj = json_decode($jsonStr);
print_r($jsonStr);
foreach ($jsonObj->events as $event) {
if(‘message’ == $event->type){
// debug
//file_put_contents('message.json', json_encode($event));
$text = $event->message->text;

if (preg_match('test', $text)) {
$text = 'Yokeap';
}

if (preg_match('/pump/', $text)) {     //หากในแชตที่ส่งมามีคำว่า เปิดทีวี ก็ให้ส่ง mqtt ไปแจ้ง server เราครับ
if ($mqtt->connect()) {
$mqtt->publish('/ESP/REMOTE','TV'); // ตัวอย่างคำสั่งเปิดทีวีที่จะส่งไปยัง mqtt server
$mqtt->close();
}
$text = 'เปิดทีวีให้แล้วคร้าบบบบ';
}
if (preg_match('/ปิดทีวี/', $text) and !preg_match('/เปิดทีวี/', $text)) {
if ($mqtt->connect()) {
$mqtt->publish('/ESP/REMOTE','TV');
$mqtt->close();
}
$text = 'จ่าปิดทีวีให้แล้วนะครับ!!';
}
$response = $bot->replyText($event->replyToken, $text); // ส่งคำ reply กลับไปยัง line application

}
}