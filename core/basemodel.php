<?php

class core_BaseModel implements interfaces_Model {

  protected $table;
  protected $db;
  protected $meta_data;
  protected $fields;

  protected $select = '*';
  protected $distinct = '';
  protected $where = '';
  protected $order_by = '';
  protected $limit = 0;
  protected $offset = 0;

  public function __construct($table) {
    $this->table = $table;
    $this->db = core_Database::database();
    $this->meta = $this->db->meta_data($table);
  }

  public function meta() {
    return $this->meta;
  }

  protected function escape_select($field, $expression) {
    return "$expression as $field";
  }

  public function select($fields) {
    if (is_array($fields)) {
      $fields = array_map(array($this, 'escape_select'), array_keys($fields), $fields);
      $this->select = implode(', ', $fields);
    } else {
      $this->select = $fields;
    }
    return $this;
  }

  public function select_count($field = '1', $alias = 'count') {
    $this->select = "count($field) as $alias";
    return $this;
  }

  public function select_sum($field, $alias = 'sum') {
    $this->select = "sum($field) as $alias";
    return $this;
  }

  public function select_max($field, $alias = 'max') {
    $this->select = "max($field) as $alias";
    return $this;
  }

  public function select_min($field, $alias = 'min') {
    $this->select = "min($field) as $alias";
    return $this;
  }

  public function select_avg($field, $alias = 'avg') {
    $this->select = "avg($field) as $alias";
    return $this;
  }

  public function distinct() {
    $this->distinct = ' DISTINCT';
    return $this;
  }
/*
  public function from($table) {
    $this->table = $table;
    return $this;
  }

  public function join($table, $condition) {
  }

  public function left_join($table, $condition) {
  }

  public function right_join($table, $condition) {
  }

  public function full_join($table, $condition) {
  }
*/

  protected function escape_where($field, $value) {
    $type = $this->meta[$field]['udt_name'];
    if (is_array($value)) {
      $values = array_map(array($this->db, 'escape'), $value, array_fill_keys(array_keys($value), $type));
      $values = implode(', ', $values);
      return "$field in ($values)";
    } else {
      $value = $this->db->escape($value, $type);
      return "$field = $value";
    }
  }

  public function where($field, $value = null) {
    if (is_null($value)) {
      if (is_array($field)) {
        $fields = array_intersect_key($field, $this->meta);
        $fields = array_map(array($this, 'escape_where'), array_keys($fields), $fields);
        $this->where = implode(' AND ', $fields);
      } else {
        $this->where = $field;
      }
    } else {
      if ($this->where != '') $this->where .= ' AND ';
      $this->where .= $this->escape_where($field, $value);
    }
    return $this;
  }

  protected function escape_order_by($field, $direction) {
    if (strcasecmp($direction, 'ASC') == 0) {
      return "$field ASC";
    } else {
      return "$field DESC";
    }
  }

  public function order_by($field, $direction = null) {
    if (is_null($direction)) {
      if (is_array($field)) {
        $fields = array_intersect_key($field, $this->meta);
        $fields = array_map(array($this, 'escape_order_by'), array_keys($fields), $fields);
        $this->order_by = implode(', ', $fields);
      } else {
        $this->order_by = $field;
      }
    } else {
      if ($this->order_by != '') $this->order_by .= ', ';
      $this->order_by .= $this->escape_order_by($field, $$direction);
    }
    return $this;
  }

  public function limit($limit, $offset = null) {
    $this->limit = (int)$limit;
    if (!is_null($offset)) {
      $this->offset = (int)$offset;
    }
    return $this;
  }

  public function offset($offset) {
    $this->offset = (int)$offset;
    return $this;
  }

  protected function sql_select() {
    $sql = "SELECT$this->distinct $this->select FROM $this->table";
    if ($this->where != '') $sql .= " WHERE $this->where";
    if ($this->order_by != '') $sql .= " ORDER BY $this->order_by";
    if ($this->limit != 0) $sql .= " LIMIT $this->limit";
    if ($this->offset != 0) $sql .= " OFFSET $this->offset";
    return $sql;
  }

  public function get($where = null, $limit = null, $offset = null) {
    if (!is_null($where)) $this->where($where);
    if (!is_null($limit)) $this->limit($limit);
    if (!is_null($offset)) $this->offset($offset);
    $sql = $this->sql_select();
    return $this->db->query($sql);
  }

  public function field($name) {
    if (!isset($this->fields[$name])) {
      $class = 'types'.NAMESPACE_SEPARATOR.str_replace(' ', '', ucwords($this->meta[$name]['data_type']));
      $this->fields[$name] = new $class($this->meta[$name]);
    }
    return $this->fields[$name];
  }
}
