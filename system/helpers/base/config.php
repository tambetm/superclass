<?php
namespace helpers\base;

class Config {

  static public function load(&$config, $filename) {
    if (function_exists('stream_resolve_include_path')){
      $filepath = stream_resolve_include_path($filename);
    } else {
      $filepath = false;
      $paths = explode(PATH_SEPARATOR, get_include_path());
      foreach ($paths as $path) {
        if (is_readable($path.DIRECTORY_SEPARATOR.$filename)) {
          $filepath = $path.DIRECTORY_SEPARATOR.$filename;
          break;
        }
      }
    }
    if ($filepath !== false) {
      // included file should have $config assignments, 
      // which are assigned to referenced $config.
      include_once $filepath;
    }
  }
}
