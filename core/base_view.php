<?php
namespace core;

use core\HTML;
use interfaces\View;

abstract class BaseView extends HTML implements View {

  protected $model;

  public function __construct($model) {
    $this->model = $model;
  }

  public function post() {
    $this->get();
  }
}
