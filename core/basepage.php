<?php

abstract class core_BasePage extends core_HTMLTemplate {

  protected $views;

  public function __construct($views = null) {
    $this->views = $views;
  }

  public function __call($name, $arguments) {
    if (is_array($this->views) && isset($this->views[$name])) {
      call_user_func_array(array($this->views[$name], 'render'), $arguments);  
    } else {
      parent::__call($name, $arguments);
    }
  }
}
