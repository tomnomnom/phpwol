<?php
namespace Test\Mock\Phpwol;

class Socket extends \Phpwol\Socket {
  public $lastData;
  public $lastBroadcastIP;
  public $lastPort;

  public $socketShouldFail = false;

  public function sendBroadcastUDP($data, $broadcastIP, $port){
    $this->lastData = $data;
    $this->lastBroadcastIP = $broadcastIP;
    $this->lastPort = $port;

    if ($this->socketShouldFail){
        return false;
    }
    return true;
  }
}
