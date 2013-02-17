<?php
namespace Phpwol;

class Socket {
  public function sendBroadcastUDP($data, $broadcastIP, $port){
    $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
    socket_set_option($socket, SOL_SOCKET, SO_BROADCAST, true);

    $sentBytes = socket_sendto($socket, $data, strlen($data), MSG_DONTROUTE, $broadcastIP, $port);
    socket_close($socket);
    return true;
  }
}
