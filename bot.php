<?php

require('vendor/autoload.php');

$access_token = 'SCV7PNRDb7/XCuNp5C7L3n25Sv4GsKuM9zRxy5+7cBCOl7QzhQloM1WUysJ/dytJOmAuNL9K/XAdrGrmieVADiWY/uIA4lZdgWF5LQUUosltryHc2JyEcz/dgujCXoF0joDF0Z84GLmydZituZPQRAdB04t89/1O/w1cDnyilFU=';

$server = "m14.cloudmqtt.com";     // change if necessary
$port = 19348;                     // change if necessary
$username = "vidaaruu";                   // set your username
$password = "Ro2sY3zEhY9W";                   // set your password
$client_id = "phpMQTT-publisher";
$topic = "/ESP/REMOTE";
$mqtt = new Bluerhinos\phpMQTT($server, $port, $client_id);

// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);

echo "connecting to MQTT Server\n";

if(!$mqtt->connect(true, NULL, $username, $password)) {
	echo "MQTT is connected\n";
  $topics[$topic] = array(
      "qos" => 0,
      "function" => "procmsg"
  );
  $mqtt->subscribe($topics,0);
  while($mqtt->proc()){

	}
	//$mqtt->proc()
  $mqtt->close();
}

function procmsg($topic, $msg){
  echo "Msg Recieved: $msg\n";
}

function replyLine($string, $replyToken){
// Build message to reply back
	$messages = [
		'type' => 'text',
		'text' => $string
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

			if (preg_match('/Off/', $text) || preg_match('/off/', $text)) {
				$text = 'Pump:Off';
			}

			if (preg_match('/On/', $text) || preg_match('/on/', $text)) {

				if ($mqtt->connect(true, NULL, $username, $password)) {
					echo "MQTT is Connecting";
					//$mqtt->publish("/ESP/REMOTE", "On", 0);
					$mqtt->publish("/ESP/REMOTE", $event['replyToken'], 0);
					$mqtt->close();
				} else {
				    echo "Time out!\n";
				}
				$text = 'Pump:On';
			}
			// Get replyToken
			$replyToken = $event['replyToken'];
			replyLine($text, $replyToken);
			/*
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
			*/
		}
	}
}
echo "OK";
?>
