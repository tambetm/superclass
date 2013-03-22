<?php
namespace core;

use core\HTML;
use interfaces\Layout;

abstract class BaseLayout extends HTML implements Layout {

  protected $view;

  public function __construct($view) {
    $this->view = $view;
  }

}
