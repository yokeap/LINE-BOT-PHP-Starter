<?php
$access_token = 'SCV7PNRDb7/XCuNp5C7L3n25Sv4GsKuM9zRxy5+7cBCOl7QzhQloM1WUysJ/dytJOmAuNL9K/XAdrGrmieVADiWY/uIA4lZdgWF5LQUUosltryHc2JyEcz/dgujCXoF0joDF0Z84GLmydZituZPQRAdB04t89/1O/w1cDnyilFU=';

// Parse JSON
$events = json_decode($content, true);

require('phpMQTT.php');

mqtt = new phpMQTT('m14.cloudmqtt.com', 19348, 'phpMQTT Pub Example');

// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			// Get text sent
			$text = $event['message']['text'];
			if (preg_match('/Koy/', $text)) {
				$text = 'A lovely girl';
			}

			if (preg_match('/led/', $text)) {
				if ($mqtt->connect()) {
					$mqtt->publish('/ESP/REMOTE','LED'); 
					$mqtt->close();
				}
				$text = 'LED_test';
			}
			// Get replyToken
			$replyToken = $event['replyToken'];

			// Build message to reply back
			$messages = [
				'type' => 'text',
				'text' => $text
			];

			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);

			echo $result . "\r\n";
		}
	}
}
echo "OK";