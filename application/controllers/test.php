<?php
namespace controllers;

use core\BaseController;

class Test extends BaseController {
  public function table() {
    echo dirname(__DIR__);
  }
}
