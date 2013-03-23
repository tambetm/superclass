<?php
namespace controllers;

use core\Controller;

class Test extends Controller {
  public function table() {
    phpinfo();
  }
}
