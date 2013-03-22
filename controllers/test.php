<?php
namespace controllers;

use core\BaseController;
use core\String;

class Test extends BaseController {
  public function table() {
    phpinfo();
  }
}
