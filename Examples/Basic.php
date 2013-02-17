<?php
// ./Examples/Basic.php
require __DIR__.'/../Phpwol/Init.php';

$f = new \Phpwol\Factory();
$magicPacket = $f->magicPacket();

$macAddress = '50:46:5C:53:94:25';
$broadcastIP = '192.168.1.255';

$result = $magicPacket->send($macAddress, $broadcastIP);

if ($result){
  echo "Worked\n";
} else {
  echo "Failed\n";
}

