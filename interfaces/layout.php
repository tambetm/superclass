<?php
namespace interfaces;

interface Layout {
  public function __construct($view);
  public function render();
}
