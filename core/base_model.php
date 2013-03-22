<?php
namespace core;

use interfaces\Model;
use core\Database;

class BaseModel implements Model {

  protected $table;
  protected $db;
  protected $meta;
  protected $fields;
  protected $primary_key;

  protected $where = '';
  protected $order_by = '';
  protected $limit = 0;
  protected $offset = 0;

  public function __construct($table) {
    $this->table = $table;
    $this->db = Database::database();
    $this->meta = $this->db->meta_data($table);
  }

  public function meta() {
    return $this->meta;
  }

  public function table() {
    return $this->table;
  }

  public function begin() {
    return $this->db->execute('begin');
  }

  public function commit() {
    return $this->db->execute('commit');
  }

  public function rollback() {
    return $this->db->execute('rollback');
  }

  public function field($name) {
    if (!isset($this->fields[$name])) {
      $class = TYPE_NAMESPACE.NAMESPACE_SEPARATOR.str_replace(' ', '', ucwords($this->meta[$name]['data_type']));
      $this->fields[$name] = new $class($this->meta[$name]);
    }
    return $this->fields[$name];
  }

  public function primary_key() {
    if (!$this->primary_key) {
      $this->primary_key = $this->db->primary_key($this->table);
    }
    return $this->primary_key;
  }

  protected function escape_value($field, $value) {
    $type = $this->meta[$field]['udt_name'];
    $value = $this->db->escape($value, $type);
    return $value;
  }

  public function values($field, $value = null) {
    if (is_array($field)) {
      $fields = array_intersect_key($field, $this->meta);
      $fields = array_map(array($this, 'escape_value'), array_keys($fields), $fields);
      $this->fields = implode(', ', array_keys($fields));
      $this->values = implode(', ', $fields);
    } elseif (isset($this->meta[$field])) {
      if ($this->values != '') $this->values .= ', ';
      $this->values .= $this->escape_value($field, $value);
      if ($this->fields != '') $this->fields .= ', ';
      $this->fields .= $field;
    }
    return $this;
  }

  protected function escape_set($field, $value) {
    $type = $this->meta[$field]['udt_name'];
    $value = $this->db->escape($value, $type);
    return "$field = $value";
  }

  public function set($field, $value = null) {
    if (is_array($field)) {
      $fields = array_intersect_key($field, $this->meta);
      $fields = array_map(array($this, 'escape_set'), array_keys($fields), $fields);
      $this->set = implode(', ', $fields);
    } elseif (isset($this->meta[$field])) {
      if ($this->set != '') $this->set .= ', ';
      $this->set .= $this->escape_set($field, $value);
    }
    return $this;
  }

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
    if (is_array($field)) {
      $fields = array_intersect_key($field, $this->meta);
      $fields = array_map(array($this, 'escape_where'), array_keys($fields), $fields);
      $this->where = implode(' AND ', $fields);
    } elseif (isset($this->meta[$field])) {
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
    if (is_array($field)) {
      $fields = array_intersect_key($field, $this->meta);
      $fields = array_map(array($this, 'escape_order_by'), array_keys($fields), $fields);
      $this->order_by = implode(', ', $fields);
    } elseif (isset($this->meta[$field])) {
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

  public function select($where = null, $order_by = null, $limit = 0, $offset = 0) {
    if (!is_null($where)) $this->where($where);
    if (!is_null($order_by)) $this->order_by($order_by);
    if ($limit !== 0) $this->limit($limit);
    if ($offset !== 0) $this->offset($offset);

    $sql = "SELECT * FROM $this->table";
    if ($this->where != '') $sql .= " WHERE $this->where";
    if ($this->order_by != '') $sql .= " ORDER BY $this->order_by";
    if ($this->limit != 0) $sql .= " LIMIT $this->limit";
    if ($this->offset != 0) $sql .= " OFFSET $this->offset";

    return $this->db->select($sql);
  }

  public function insert($data) {
    $this->values($data);
    $sql = "INSERT INTO $this->table ($this->fields) VALLUES ($this->values)";
    return $this->db->execute($sql);
  }

  public function update($data, $where) {
    $this->set($data);
    $this->where($where);
    $sql = "UPDATE $this->table SET $this->set WHERE $this->where";
    return $this->db->execute($sql);
  }

  public function delete($where) {
    $this->where($where);
    $sql = "DELETE FROM $this->table WHERE $this->where";
    return $this->db->execute($sql);
  }

  public function validate($data) {
  }
}
