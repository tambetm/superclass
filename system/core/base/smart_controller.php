<?php
namespace core\base;

use core\Controller as _Controller;
use helpers\URL;

class SmartController extends _Controller {
  
  public function __call($name, $arguments) {
    if (isset($this->config[$name])) {
      parent::__call($name, $arguments);
    } else {
      // if parent didn't find it, use information from URL
      // to determine model, view and method. use default layout.
      $this->invoke(URL::get_resource(), URL::get_action(), URL::get_method());
    }
  }
}
