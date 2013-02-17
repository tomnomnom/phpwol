<?php
namespace Test\Unit\Phpwol;

class MagicPacketTest extends \PHPUnit_Framework_TestCase {
  public function testSimple(){
    $s = new \Test\Mock\Phpwol\Socket();
    $m = new \Phpwol\MagicPacket($s);

    $m->send('50:46:5C:53:94:25', '192.168.1.255');

    $this->assertEquals($s->lastBroadcastIP, '192.168.1.255', "Last Broadcast IP should have been [192.168.1.255]");
    $this->assertEquals($s->lastPort, 7, "Last port should have been 7");
    $this->assertEquals(strlen($s->lastData), 102, "Last data should have been of length 102");
  }

  public function testInvalidMac(){
    $s = new \Test\Mock\Phpwol\Socket();
    $m = new \Phpwol\MagicPacket($s);
     
    $m->send('50:46:5C:53:94', '192.168.1.255');

    $this->assertEquals($m->getLastError(), \Phpwol\MagicPacket::ERR_INVALID_MAC, "Last error should have been ERR_INVALID_MAC");
  }

  public function testInvalidIP(){
    $s = new \Test\Mock\Phpwol\Socket();
    $m = new \Phpwol\MagicPacket($s);
     
    $m->send('50:46:5C:53:94:25', '192.168.1');

    $this->assertEquals($m->getLastError(), \Phpwol\MagicPacket::ERR_INVALID_IP, "Last error should have been ERR_INVALID_IP");
  }
}
