<?php

class pages_Bootstrap extends pages_HTML5 {

  protected $viewport = 'width=device-width, initial-scale=1.0';

  public function head() {
    $this->_base(array('href' => core_Context::base_url()));
    parent::head();
  }

  public function head_title() {
    echo 'Bootstrap, from Twitter';
  }

  public function head_metas() {
    parent::head_metas();
    $this->_meta(array('name' => 'viewport', 'content' => $this->viewport));
  }

  public function head_links() {
    parent::head_links();
    $this->_link(array('href' => 'assets/css/bootstrap.min.css', 'rel' => 'stylesheet', 'media' => 'screen'));
  }

  public function body() {
    $this->_navbar('div', array('class' => 'navbar navbar-inverse navbar-static-top'));
    $this->main();
    parent::body();
  }

  public function navbar() {
    $this->_navbar_inner('div', array('class' => 'navbar-inner'));
  }

  public function navbar_inner() {
    $this->_navbar_container('div', array('class' => 'container'));
  }

  public function navbar_container() {
    $this->_navbar_button(array('type' => 'button', 'class' => 'btn btn-navbar', 'data-toggle' => 'collapse', 'data-target' => '.nav-collapse'));
    $this->_brand('a', array('class' => 'brand', 'href' => core_Context::base_url()));
    $this->_global_menu('div', array('class' => 'nav-collapse collapse'));
  }

  public function navbar_button() {
    $this->_span(array('class' => 'icon-bar'));
    $this->_span(array('class' => 'icon-bar'));
    $this->_span(array('class' => 'icon-bar'));
  }

  public function brand() {
    echo 'Project name';
  }

  public function global_menu() {
    $model = new models_GlobalMenu();
    $view = new views_Nav($model);
    $view->render();
  }

  public function body_scripts() {
    parent::body_scripts();
    $this->_script(array('src' => 'http://code.jquery.com/jquery.js'));
    $this->_script(array('src' => 'assets/js/bootstrap.min.js'));
  }

}
