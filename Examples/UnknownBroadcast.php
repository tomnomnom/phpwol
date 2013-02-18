<?php
// ./Examples/UnknownBroadcast.php
require __DIR__.'/../Phpwol/Init.php';

$f = new \Phpwol\Factory();
$magicPacket = $f->magicPacket();

$macAddress = '50:46:5C:53:94:25';
$ip = '192.168.1.10';
$subnet = '255.255.255.0';

$result = $magicPacket->send($macAddress, $ip, $subnet);

if ($result){
  echo "Worked\n";
} else {
  echo "Failed\n";
}

