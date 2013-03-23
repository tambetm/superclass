<?php
namespace interfaces;

interface View {
  public function __construct($model);
  public function get();
  public function post();
  public function title();
  public function render();
}
