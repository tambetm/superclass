<?php
namespace interfaces;

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

  // internal functions
  //protected function connect();
  //protected function query();
  //protected function fetch_all();
  //protected function fetch_row();
  //protected function affected_rows();
}
