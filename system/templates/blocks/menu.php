<?php
namespace templates\blocks;

use core\HTML;
use helpers\URL;
use helpers\String;
use helpers\Config;

class Menu extends HTML {

  protected $name;
  protected $config;

  /* internal loop variables */
  protected $url;
  protected $label;

  public function __construct($name) {
    $this->name = $name;
    Config::load($this->config, "config/menus/$name.php");
  }

  public function render() {
    if (is_array($this->config['menu'])) {
      $attributes = array('id' => $this->name);
      $this->_ul(self::merge_attributes($attributes, $this->config['attributes']));
    }
  }

  public function ul() {
    $path = URL::site_path();
    foreach ($this->config['menu'] as $this->url => $this->label) {
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
