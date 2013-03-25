<?php
namespace interfaces;

interface View {
  public function __construct($model);
  public function get($params);
  public function post($params);
  public function title();
  public function render();
}
