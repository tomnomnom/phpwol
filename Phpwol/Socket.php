<?php
namespace Phpwol;

class Socket {
  public function sendBroadcastUDP($data, $broadcastIP, $port){
    $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
    socket_set_option($socket, SOL_SOCKET, SO_BROADCAST, true);

    // If, for example, the broadcast IP is not on a network that the machine is attached to then
    // socket_sendto will fail with an error. It's difficult to check for that scenario when a machine has
    // multiple interfaces so just silence the error and return false.
    $sentBytes = @socket_sendto($socket, $data, strlen($data), 0, $broadcastIP, $port);
    socket_close($socket);

    if ($sentBytes === false){
      return false;
    }
    return true;
  }
}
