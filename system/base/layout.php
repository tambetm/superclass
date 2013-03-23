<?php
namespace base;

abstract class Layout extends \core\HTML implements \interfaces\Layout {

  protected $view;

  public function __construct($view) {
    $this->view = $view;
  }

}
