<?php

interface interfaces_Action {
  public function __construct($model);
  public function process();
}