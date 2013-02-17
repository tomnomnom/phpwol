<?php

class WOL {
  public function sendMagicPacket($mac, $broadcastIP){
    $hexMac = str_replace(':', '', $mac); 

    if (!ctype_xdigit($hexMac)){
      throw new \InvalidArgumentException("[{$mac}] is not a valid MAC address");
    }

    if (!filter_var($broadcastIP, FILTER_VALIDATE_IP)){
      throw new \InvalidArgumentException("[{$broadcastIP}] is not a valid broadcast IP");
    }
    
    $binMac = pack('H12', $hexMac);
    $prefix = pack('H12', str_repeat('FF', 6));

    $magicPacket = $prefix . str_repeat($binMac, 16);

    $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
    socket_set_option($socket, SOL_SOCKET, SO_BROADCAST, true);

    $magicPacketLength = strlen($magicPacket);
    $sentBytes = socket_sendto($socket, $magicPacket, $magicPacketLength, MSG_DONTROUTE, $broadcastIP, 7);
    socket_close($socket);

    if (!$sentBytes){
      throw new \RuntimeException("Failed to send magic packet to [udp://{$broadcastIP}:7]");
    }

    return true;
  }
}
