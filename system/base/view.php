<?php
namespace base;

use core\HTML;

abstract class View extends HTML implements \interfaces\View {

  protected $model;

  public function __construct($model) {
    $this->model = $model;
  }

  public function post() {
    $this->get();
  }
}
