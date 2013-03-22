<?php
namespace controllers;

use core\BaseController;
use core\String;

class Test extends BaseController {
  public function index() {
    echo String::camelize('table_edit');
    echo String::uncamelize('ThisIsATest');
  }
}
