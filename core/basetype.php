<?php

abstract class core_BaseType extends core_HTMLTemplate implements core_Type {
  protected $meta;
  
  public function __construct($meta) {
    $this->meta = $meta;
  }
}
