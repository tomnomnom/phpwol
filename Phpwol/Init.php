<?php
namespace PhpWol;

spl_autoload_register(function($class){
  $class = ltrim($class, '\\');
  $class = str_replace('\\', '/', $class);
  require __DIR__."/../{$class}.php";
});
