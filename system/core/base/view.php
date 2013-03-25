<?php
namespace core\base;

use core\HTML as _HTML;

abstract class View extends _HTML implements \interfaces\View {

  protected $model;

  public function __construct($model) {
    $this->model = $model;
  }

  public function post($params) {
    $this->get($_GET);
  }
}
