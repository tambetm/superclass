<?php
namespace interfaces;

interface Layout {
  public function __construct($views);
  public function render();
}
