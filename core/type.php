<?php

interface core_Type {
  public function __construct($meta);
  public function input($default);
  public function output($value);
  public function validate($value);
}
