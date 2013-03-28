<?php
namespace core\interfaces;

interface Database {
  public function __construct($name); // TODO: $name => $config?

  // meta data
  public function columns($table);
  public function primary_key($table);

  // data query and manipulation
  public function execute($sql);  // return affected rows
  public function select_all($sql);
  public function select_row($sql);

  // should be implemented by driver
  public function escape($value, $type = 'text');
  public function begin();
  public function commit();
  public function rollback();

  // abstract functions, should be implemented by driver
  //abstract protected function connect();
  //abstract protected function query();
  //abstract protected function fetch_all();
  //abstract protected function fetch_row();
  //abstract protected function affected_rows();
}
