<?php
//echo "MQTT Testing\n";

require('vendor/autoload.php');

//include ("phpMQTT.php");

$server = "m14.cloudmqtt.com";     // change if necessary
$port = 19348;                     // change if necessary
$username = "vidaaruu";                   // set your username
$password = "Ro2sY3zEhY9W";                   // set your password
$client_id = "phpMQTT-publisher"; 
$mqtt = new Bluerhinos\phpMQTT($server, $port, $client_id);
echo "connecting to MQTT Server\n";
if ($mqtt->connect(true, NULL, $username, $password)) {
	$mqtt->publish("/ESP/REMOTE", "LED", 0);
	$mqtt->close();
} else {
    echo "Time out!\n";
}
