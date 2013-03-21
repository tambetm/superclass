<?php

interface interfaces_Type {
  const KIND_STRING = 'string';
  const KIND_TEXT = 'text';
  const KIND_NUMBER = 'number';
  const KIND_BOOLEAN = 'boolean';
  const KIND_DATE = 'date';
  const KIND_TIME = 'time';
  const KIND_DATETIME = 'datetime';
  
  public function __construct($meta);
  public function widget($name, $default = '');
  public function format($value);
  public function validate(&$value);
  public function kind();
  public function label();
}