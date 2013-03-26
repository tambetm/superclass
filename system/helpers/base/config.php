<?php
namespace helpers\base;

class Config {

  static public function load(&$config, $filename) {
    $filepath = stream_resolve_include_path($filename);
    if ($filepath !== false) {
      // included file should have $config assignments, 
      // which are assigned to referenced $config.
      include_once $filepath;
    }
  }
}
