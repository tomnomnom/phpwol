<?php
namespace Phpwol;

class MagicPacket {
  protected $socket;
  protected $lastError = 0;

  const ERR_INVALID_IP  = 1;
  const ERR_INVALID_MAC = 2;

  public function __construct(Socket $socket){
    $this->socket = $socket;
  }

  public function send($mac, $broadcastIP){
    // Reset the last error
    $this->lastError = 0;

    $hexMac = str_replace(':', '', $mac); 

    if (!ctype_xdigit($hexMac) || strlen($hexMac) != 12){
      $this->lastError = self::ERR_INVALID_MAC;
      return false;
    }

    if (!filter_var($broadcastIP, FILTER_VALIDATE_IP)){
      $this->lastError = self::ERR_INVALID_IP;
      return false;
    }
    
    $binMac = pack('H12', $hexMac);
    $prefix = pack('H12', str_repeat('FF', 6));

    $magicPacket = $prefix . str_repeat($binMac, 16);
    
    $this->socket->sendBroadcastUDP($magicPacket, $broadcastIP, 7);

    return true;
  }

  public function getLastError(){
    return $this->lastError;
  }
}
