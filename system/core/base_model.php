<?php
namespace core;

use interfaces\Model;
use core\Database;
use helpers\String;

class BaseModel implements Model {

  protected $db;
  protected $table;
  protected $fields;
  protected $primary_key;

  protected $where = '';
  protected $order_by = '';
  protected $limit = 0;
  protected $offset = 0;
  protected $set = '';
  protected $names = '';
  protected $values = '';

  public function __construct($table) {
    $this->table = $table;
    $this->db = Database::database();
  }

  public function db() {
    return $this->db;
  }

  public function table() {
    return $this->table;
  }

  public function caption() {
    return String::human($this->table);
  }

  protected function field($column) {
    $class = FIELD_NAMESPACE.NAMESPACE_SEPARATOR.String::camelcase($column['data_type']);
    return new $class($column);
  }

  public function fields() {
    if (!$this->fields) {
      $columns = $this->db->columns($this->table);
      if (!is_array($columns)) throw new \UnexpectedValueException("Invalid metadata for columns for table '$this->table'");

      foreach ($columns as $name => $column) {
        $this->fields[$name] = $this->field($column);
      }
    }
    return $this->fields;
  }

  public function primary_key() {
    if (!$this->primary_key) {
       $primary_key = $this->db->primary_key($this->table);
       if (!is_array($primary_key)) throw new \UnexpectedValueException("Invalid metadata for primary key for table '$this->table'");
       $this->primary_key = $primary_key;
    }
    return $this->primary_key;
  }

  protected function escape_value($field, $value) {
    $type = $this->fields[$field]->database_type();
    $value = $this->db->escape($value, $type);
    return $value;
  }

  public function values($field, $value = null) {
    if (is_array($field)) {
      $fields = array_intersect_key($field, $this->fields);
      $this->names = implode(', ', array_keys($fields));
      $fields = array_map(array($this, 'escape_value'), array_keys($fields), $fields);
      $this->values = implode(', ', $fields);
    } elseif (isset($this->fields[$field])) {
      if ($this->values != '') $this->values .= ', ';
      $this->values .= $this->escape_value($field, $value);
      if ($this->names != '') $this->names .= ', ';
      $this->names .= $field;
    }
    return $this;
  }

  protected function escape_set($field, $value) {
    $type = $this->fields[$field]->database_type();
    $value = $this->db->escape($value, $type);
    return "$field = $value";
  }

  public function set($field, $value = null) {
    if (is_array($field)) {
      $fields = array_intersect_key($field, $this->fields);
      $fields = array_map(array($this, 'escape_set'), array_keys($fields), $fields);
      $this->set = implode(', ', $fields);
    } elseif (isset($this->fields[$field])) {
      if ($this->set != '') $this->set .= ', ';
      $this->set .= $this->escape_set($field, $value);
    }
    return $this;
  }

  protected function escape_where($field, $value) {
    $type = $this->fields[$field]->database_type();
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
      $fields = array_intersect_key($field, $this->fields);
      $fields = array_map(array($this, 'escape_where'), array_keys($fields), $fields);
      $this->where = implode(' AND ', $fields);
    } elseif (isset($this->fields[$field])) {
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
      $fields = array_intersect_key($field, $this->fields);
      $fields = array_map(array($this, 'escape_order_by'), array_keys($fields), $fields);
      $this->order_by = implode(', ', $fields);
    } elseif (isset($this->fields[$field])) {
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

    // database errors trigger error anyway
    return $this->db->select_all($sql);
  }

  public function insert($data) {
    $this->values($data);
    $sql = "INSERT INTO $this->table ($this->names) VALUES ($this->values)";
    // database errors trigger error anyway
    return $this->db->execute($sql);
  }

  public function update($data, $where) {
    $this->set($data);
    $this->where($where);
    $sql = "UPDATE $this->table SET $this->set WHERE $this->where";
    // database errors trigger error anyway
    return $this->db->execute($sql);
  }

  public function delete($where) {
    $this->where($where);
    $sql = "DELETE FROM $this->table WHERE $this->where";
    // database errors trigger error anyway
    return $this->db->execute($sql);
  }

  public function validate(&$data, &$errors, $prefix = '') {
    $data = array_intersect_key($data, $this->fields);
    $success = true;
    foreach($data as $name => &$value) {
      $field = $this->fields[$name];
      if (!$field->validate($value, $error, $prefix)) {
        $success = false;
        $errors[] = $prefix.$error;
      }
    }
    return $success;
  }

  protected function escape_default($name, $value) {
    return "$value AS $name";
  }

  public function defaults() {
    foreach($this->fields as $name => $field) {
      $defaults[$name] = $field->default_value();
    }
    // exclude nulls to conserve database bandwidth
    $defaults = array_filter($defaults);
    $defaults = array_map(array($this, 'escape_default'), array_keys($defaults), $defaults);
    $defaults = implode(', ', $defaults);
    $sql = "SELECT $defaults";
    $defaults = $this->db->select_row($sql);
    if (!is_array($defaults)) throw new \InvalidArgumentException("Unable to load defaults for '$this->table'.");
    // add nulls for all fields that didn't have default value
    $nulls = array_fill_keys(array_keys($this->fields), null);
    $defaults = array_merge($nulls, $defaults);
    return $defaults;
  }
}
