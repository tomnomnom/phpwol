<?php
namespace Test\Mock\Phpwol;

class Socket extends \Phpwol\Socket {
  public $lastData;
  public $lastBroadcastIP;
  public $lastPort;

  public function sendBroadcastUDP($data, $broadcastIP, $port){
    $this->lastData = $data;
    $this->lastBroadcastIP = $broadcastIP;
    $this->lastPort = $port;
  }
}
