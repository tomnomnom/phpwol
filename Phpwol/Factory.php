<?php
namespace Phpwol;

class Factory {
  public function magicPacket(){
    return new MagicPacket(new Socket());
  }
}
