<?php
require __DIR__.'/WOL.php';

$macAddress = '50:46:5C:53:94:25';
$broadcastIP = '192.168.1.255';

$wol = new Wol();

$wol->sendMagicPacket($macAddress, $broadcastIP);
