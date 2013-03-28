<?php
namespace core\base;

use core\HTML as _HTML;

abstract class Layout extends _HTML implements \core\interfaces\Layout {

  protected $view;

  public function __construct($view) {
    $this->view = $view;
  }

}
