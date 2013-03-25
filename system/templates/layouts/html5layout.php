<?php
namespace templates\layouts;

use core\Layout;
use helpers\Locale;

class HTML5Layout extends Layout {

  public function render() {
    $this->doctype();
    $this->_html(array('lang' => Locale::get_primary_language()));
  }

  public function doctype() {
    echo "<!DOCTYPE html>";
  }

  public function html() {
    $this->_head();
    $this->_body();
  }

  public function head() {
    $this->_title();
    $this->head_metas();
    $this->head_links();
    $this->head_scripts();
  }

  public function title() {
    echo self::escape($this->view->title());
  }

  public function head_metas() {
    $this->_meta(array('charset' => Locale::get_charset()));
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
