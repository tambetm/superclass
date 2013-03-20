<?php

class models_GlobalMenu {

  protected $menu;

  public function __construct($menu = null) {
    if (!$menu) {
      include('config/globalmenu.php');
    }
    $this->menu = $menu;
  }

  public function get() {
    return $this->menu;
  }
}
