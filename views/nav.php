<?php

class views_Nav extends core_BaseView {

  protected $menu;
  protected $url;
  protected $label;

  public function __construct($model) {
    parent::__construct($model);
    $this->menu = $model->data();
  }

  public function render() {
    if (is_array($this->menu)) {
      $this->_ul(array('class' => 'nav'));
    }
  }

  public function ul() {
    $current_url = core_Context::current_url();
    echo $current_url;
    foreach ($this->menu as $this->url => $this->label) {
      echo $this->url;
      echo 'a'.strpos($this->url, $current_url).'b'; 
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
