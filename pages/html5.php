<?php

abstract class pages_HTML5 extends core_BasePage {

  protected $charset = 'utf-8';

  public function render() {
    $this->doctype();
    $locale = core_Locale::instance();
    $this->_html(array('lang' => $locale->get_primary_language()));
  }

  public function doctype() {
    echo "<!DOCTYPE html>";
  }

  public function html() {
    $this->_head();
    $this->_body();
  }

  public function head() {
    $this->_head_title();
    $this->head_metas();
    $this->head_links();
    $this->head_scripts();
  }

  abstract public function head_title();

  public function head_metas() {
    $this->_meta(array('charset' => $this->charset));
  }

  public function head_links() {
  }

  public function head_scripts() {
  }

  public function body() {
    $this->body_scripts();
  }

  public function body_scripts() {
  }
}
