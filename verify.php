<?php
//echo "MQTT Testing\n";

//require("phpMQTT.php");

include ("phpMQTT.php");

echo "MQTT Testing2\n";

$server = "m14.cloudmqtt.com";     // change if necessary
$port = 19348;                     // change if necessary
$username = "vidaaruu";                   // set your username
$password = "TEST";                   // set your password
$client_id = "phpMQTT-publisher"; // make sure this is unique for connecting to sever - you could use uniqid()
$mqtt = new phpMQTT($server, $port, $client_id);
echo "connecting to MQTT Server\n";
if ($mqtt->connect(true, NULL, $username, $password)) {
	$mqtt->publish("/ESP/REMOTE", "LED", 0);
	$mqtt->close();
} else {
    echo "Time out!\n";
}