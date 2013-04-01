<?php
namespace core\base;

use core\Controller as _Controller;
use helpers\URL;

class SmartController extends _Controller {
  
  public function __call($name, $arguments) {
    if (isset($this->config[$name])) {
      parent::__call($name, $arguments);
    } else {
      // if no configuration, then use information from URL
      // to determine model, view and action. use default layout.
      $this->invoke($this->model_name, $this->view_name, $this->action);
    }
  }
}
