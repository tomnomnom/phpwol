<?php
namespace Test\Unit\Phpwol;

class MagicPacketTest extends \PHPUnit_Framework_TestCase {
  public function testSimple(){
    $s = new \Test\Mock\Phpwol\Socket();
    $m = new \Phpwol\MagicPacket($s);

    $m->send('50:46:5C:53:94:25', '192.168.1.255');

    $this->assertEquals('192.168.1.255', $s->lastBroadcastIP, "Last Broadcast IP should have been [192.168.1.255]");
    $this->assertEquals(7, $s->lastPort, "Last port should have been 7");

    // 102 is the expected length of a WOL packet
    $this->assertEquals(102, strlen($s->lastData), "Last data should have been of length 102");
  }

  public function testInvalidMac(){
    $m = new \Phpwol\MagicPacket();
     
    $m->send('50:46:5C:53:94', '192.168.1.255');

    $this->assertEquals(\Phpwol\MagicPacket::ERR_INVALID_MAC, $m->getLastError(), "Last error should have been ERR_INVALID_MAC");
  }

  public function testInvalidIP(){
    $m = new \Phpwol\MagicPacket();
     
    $m->send('50:46:5C:53:94:25', '192.168.1');

    $this->assertEquals(\Phpwol\MagicPacket::ERR_INVALID_IP, $m->getLastError(), "Last error should have been ERR_INVALID_IP");
  }

  public function testInvalidSubnet(){
    $m = new \Phpwol\MagicPacket();
     
    $m->send('50:46:5C:53:94:25', '192.168.1.10', '255.255.');

    $this->assertEquals(\Phpwol\MagicPacket::ERR_INVALID_SUBNET, $m->getLastError(), "Last error should have been ERR_INVALID_SUBNET");
  }

  public function testGetBroadcastIP(){
    $m = new \Phpwol\MagicPacket(); 

    $bip = $m->getBroadcastIP('192.168.1.1', '255.255.255.0');
    $this->assertEquals('192.168.1.255', $bip, "Broadcast IP should have been [192.168.1.255]");

    $bip = $m->getBroadcastIP('192.168.1.1', '255.255.255.128');
    $this->assertEquals('192.168.1.127', $bip, "Broadcast IP should have been [192.168.1.127]");
  }

  public function testConvertToBroadcast(){
    $s = new \Test\Mock\Phpwol\Socket();
    $m = new \Phpwol\MagicPacket($s);

    $m->send('50:46:5C:53:94:25', '192.168.1.10', '255.255.255.0');

    $this->assertEquals('192.168.1.255', $s->lastBroadcastIP, "Last Broadcast IP should have been [192.168.1.255]");
    $this->assertEquals(7, $s->lastPort, "Last port should have been 7");

    // 102 is the expected length of a WOL packet
    $this->assertEquals(102, strlen($s->lastData), "Last data should have been of length 102");
  }

  public function testPacketData(){
    $s = new \Test\Mock\Phpwol\Socket();
    $m = new \Phpwol\MagicPacket($s);

    $mac = '50:46:5C:53:94:25';
    $m->send($mac, '192.168.1.255');

    $expected = strToLower(str_repeat('FF', 6).str_repeat(str_replace(':', '', $mac), 16));

    // 102 is the expected length of a WOL packet
    $nibbles = 102 * 2;
    $unpacked = unpack("H{$nibbles}hex", $s->lastData);
    $actual = strToLower($unpacked['hex']);

    $this->assertEquals($expected, $actual, "Last data should match expected");
  }

  public function testSocketSendFailed(){
    $s = new \Test\Mock\Phpwol\Socket();
    $s->socketShouldFail = true;
    $m = new \Phpwol\MagicPacket($s);

    $mac = '50:46:5C:53:94:25';
    $r = $m->send($mac, '192.168.1.255');

    $this->assertFalse($r, "Socket send should have failed");
    $this->assertEquals(\Phpwol\MagicPacket::ERR_SOCKET_SEND_FAILED, $m->getLastError(), "Last error should have been ERR_SOCKET_SEND_FAILED");
  }
}
