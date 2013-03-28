<?php
namespace core\interfaces;

interface Model {
  public function __construct($name);

  // meta data
  public function name();
  public function caption();
  public function fields();
  public function primary_key();

  // query elements
  public function where($field, $value);
  public function order_by($field, $asc_desc);
  public function limit($limit);
  public function offset($offset);

  // query and manipulation
  public function select($where = null, $order_by = null, $limit = null, $offset = null);
  public function insert($data);
  public function update($data, $where);
  public function delete($where);
  public function validate(&$data);
  public function defaults();
}
