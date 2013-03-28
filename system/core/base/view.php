<?php
namespace core\base;

use core\HTML as _HTML;

abstract class View extends _HTML implements \core\interfaces\View {

  protected $model;

  public function __construct($model) {
    $this->model = $model;
  }

  public function get() {
  }

  public function post() {
  }
}
