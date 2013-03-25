<?php
namespace interfaces;

interface Model {
  public function __construct($table);

  // meta data
  public function table();
  public function caption();
  public function fields();
  public function primary_key();

  // query and manipulation
  public function select($where = null, $order_by = null, $limit = null, $offset = null);
  public function insert($data);
  public function update($data, $where);
  public function delete($where);
  public function validate(&$data);
  public function defaults();
}
