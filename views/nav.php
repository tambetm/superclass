<?php

class views_Nav extends core_BaseView {

  protected $menu;
  protected $config;

  /* internal loop variables */
  protected $url;
  protected $label;

  public function __construct($model) {
    parent::__construct($model);
    $this->menu = $model->data();
    include('config/nav.php');
    $this->config = $config;
  }

  public function render() {
    if (is_array($this->menu)) {
      $attributes = array();
      if (isset($this->config['class'])) {
        $attributes['class'] = $this->config['class'];
      }
      $this->_ul($attributes);
    }
  }

  public function ul() {
    $current_url = core_URL::current_url();
    foreach ($this->menu as $this->url => $this->label) {
      if (strpos($current_url, $this->url, 1) === 1) {
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
    echo htmlspecialchars($this->label);
  }
}
