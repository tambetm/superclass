<?php
namespace views;

use core\HTML;
use helpers\URL;

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
    $current_url = URL::current_url();
    foreach ($this->menu as $this->url => $this->label) {
      if (strpos($current_url, $this->url) === 0) {
        $this->_ul_li(array('class' => 'active'));
      } else {
        $this->_ul_li();
      }
    }
  }

  public function ul_li() {
    $this->_ul_li_a(array('href' => $this->url));
  }

  public function ul_li_a() {
    echo self::escape($this->label);
  }
}
