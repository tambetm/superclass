<?php
namespace templates\blocks;

use core\HTML;
use helpers\URL;
use helpers\String;

class Menu extends HTML {

  protected $name;
  protected $menu;
  protected $config;

  /* internal loop variables */
  protected $url;
  protected $label;

  public function __construct($name) {
    $this->name = $name;
    include('config/menu.php');
    $this->menu = $menu[$name];
    $this->config = $config[$name];
  }

  public function render() {
    if (is_array($this->menu)) {
      $attributes = array('id' => $this->name);
      if (isset($this->config['class'])) {
        $attributes['class'] = $this->config['class'];
      }
      $this->_ul($attributes);
    }
  }

  public function ul() {
    $path = URL::relative_path();
    foreach ($this->menu as $this->url => $this->label) {
      if (String::starts_with($path, $this->url)) {
        $this->_ul_li(array('class' => 'active'));
      } else {
        $this->_ul_li();
      }
    }
  }

  public function ul_li() {
    $this->_ul_li_a(array('href' => URL::base_path().$this->url));
  }

  public function ul_li_a() {
    echo self::escape($this->label);
  }
}
