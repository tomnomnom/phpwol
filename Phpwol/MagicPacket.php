<?php
namespace Phpwol;

class MagicPacket {
  protected $socket;
  protected $lastError = 0;

  const ERR_INVALID_IP         = 1;
  const ERR_INVALID_MAC        = 2;
  const ERR_INVALID_SUBNET     = 4;
  const ERR_SOCKET_SEND_FAILED = 8;

  public function __construct(Socket $socket = null){
    $this->socket = $socket;
  }

  public function send($mac, $ip, $subnet = null){
    // Reset the last error
    $this->lastError = 0;

    if (!filter_var($ip, FILTER_VALIDATE_IP)){
      $this->lastError = self::ERR_INVALID_IP;
      return false;
    }

    // If we're not given a subnet assume a broadcast IP
    if (is_null($subnet)){
      $broadcastIP = $ip;
    } else {

      if (!filter_var($subnet, FILTER_VALIDATE_IP)){
        $this->lastError = self::ERR_INVALID_SUBNET;
        return false;
      }
      $broadcastIP = $this->getBroadcastIP($ip, $subnet);
    }

    $hexMac = str_replace(':', '', $mac); 

    if (!ctype_xdigit($hexMac) || strlen($hexMac) != 12){
      $this->lastError = self::ERR_INVALID_MAC;
      return false;
    }

    
    $binMac = pack('H12', $hexMac);
    $prefix = pack('H12', str_repeat('FF', 6));

    $magicPacket = $prefix . str_repeat($binMac, 16);
    
    $sent = $this->socket->sendBroadcastUDP($magicPacket, $broadcastIP, 7);
    if (!$sent){
      $this->lastError = self::ERR_SOCKET_SEND_FAILED;
      return false;
    }

    return true;
  }

  public function getBroadcastIP($ip, $subnet){
    $ip = ip2long($ip);
    $subnet = ip2long($subnet);
    
    $broadcastIP = $ip | ~$subnet;

    return long2ip($broadcastIP);
  }

  public function getLastError(){
    return $this->lastError;
  }
}
