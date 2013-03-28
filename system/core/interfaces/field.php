<?php
namespace core\interfaces;

interface Field {
  const KIND_STRING = 'string';
  const KIND_TEXT = 'text';
  const KIND_NUMBER = 'number';
  const KIND_BOOLEAN = 'boolean';
  const KIND_DATE = 'date';
  const KIND_TIME = 'time';
  const KIND_DATETIME = 'datetime';
  const KIND_ENUM = 'enum';
  const KIND_LOOKUP = 'lookup';
  
  public function __construct($column, $model);

  // meta data
  public function kind();
  public function label();
  public function database_type();
  public function default_value();
  public function is_updatable();

  // input, output and validation
  public function control($name, $value = '');
  public function output($value);
  public function format($value);
  public function validate(&$value);
}
