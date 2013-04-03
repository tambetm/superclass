<?php
namespace core\base;

use core\Database;
use helpers\String;
use helpers\Config;
use helpers\Arrays;
use helpers\Messages;

class Model implements \core\interfaces\Model {
  protected $name;
  protected $config;

  private $db;
  private $fields;
  private $primary_key;

  protected $table;
  protected $caption;
  protected $where = '';
  protected $order_by = '';
  protected $limit = 0;
  protected $offset = 0;
  protected $set = '';
  protected $names = '';
  protected $values = '';

  public function __construct($name) {
    $this->name = $name;
    Config::load($this->config, MODEL_NAMESPACE.DIRECTORY_SEPARATOR."_$name.php");

    $this->table = isset($this->config['table']) ? $this->config['table'] : Resolver::get_database_table($name);
    $this->caption = isset($this->config['caption']) ? $this->config['caption'] : String::human($name);
  }

  public function db() {
    if (is_null($this->db)) {
      if (isset($this->config['database'])) {
        $this->db = Database::database($this->config['database']);
      } else {
        $this->db = Database::database();
      }
    }
    return $this->db;
  }

  public function name() {
    return $this->name;
  }

  public function caption() {
    return $this->caption;
  }

  protected function load_fields() {
    if (is_null($this->fields)) {
      $columns = $this->db()->columns($this->table);
      if (!is_array($columns) || count($columns) == 0) {
        throw new \UnexpectedValueException("Missing column metadata for table '$this->table'");
      }

      foreach ($columns as $name => $column) {
        // load configuration outside of field class, because configuration values might affect field class
        if ($column['domain_name']) {
          Config::load($column, DOMAIN_NAMESPACE.DIRECTORY_SEPARATOR."_{$column['domain_name']}.php");
        }
        Config::load($column, FIELD_NAMESPACE.DIRECTORY_SEPARATOR."_{$this->name}_{$column['column_name']}.php");
        if (isset($this->config['columns'][$column['column_name']])) {
          // apply model config to column, because it must override domain or field config
          $column = array_merge($column, $this->config['columns'][$column['column_name']]);
        }

        $field_class = Resolver::get_field_class($column);
        $this->fields[$name] = new $field_class($column, $this);
      }
    }
  }

  protected function field($field) {
    $this->load_fields();
    return $this->fields[$field];
  }

  public function fields() {
    $this->load_fields();
    return $this->fields;
  }

  protected function load_primary_key() {
    if (is_null($this->primary_key)) {
       if (isset($this->config['primary_key'])) {
         $this->primary_key = $this->config['primary_key'];
       } else {
         $this->primary_key = $this->db()->primary_key($this->table);
         if (!is_array($this->primary_key) || count($this->primary_key) == 0) throw new \UnexpectedValueException("Missing primary key for table '$this->table'");
       }
    }
  }

  public function primary_key() {
    $this->load_primary_key();
    return $this->primary_key;
  }

  protected function escape_value($field, $value) {
    $type = $this->field($field)->database_type();
    $value = $this->db()->escape($value, $type);
    return $value;
  }

  public function values($field, $value = null) {
    if (is_array($field)) {
      $fields = array_intersect_key($field, $this->fields());
      $this->names = implode(', ', array_keys($fields));
      $fields = array_map(array($this, 'escape_value'), array_keys($fields), $fields);
      $this->values = implode(', ', $fields);
    } elseif ($this->field($field)) {
      if ($this->values != '') $this->values .= ', ';
      $this->values .= $this->escape_value($field, $value);
      if ($this->names != '') $this->names .= ', ';
      $this->names .= $field;
    }
    return $this;
  }

  protected function escape_set($field, $value) {
    $type = $this->field($field)->database_type();
    $value = $this->db()->escape($value, $type);
    return "$field = $value";
  }

  public function set($field, $value = null) {
    if (is_array($field)) {
      $fields = array_intersect_key($field, $this->fields());
      $fields = array_map(array($this, 'escape_set'), array_keys($fields), $fields);
      $this->set = implode(', ', $fields);
    } elseif ($this->field($field)) {
      if ($this->set != '') $this->set .= ', ';
      $this->set .= $this->escape_set($field, $value);
    }
    return $this;
  }

  protected function escape_where($field, $value) {
    $type = $this->field($field)->database_type();
    if (is_array($value)) {
      $values = array_map(array($this->db(), 'escape'), $value, array_fill_keys(array_keys($value), $type));
      $values = implode(', ', $values);
      return "$field in ($values)";
    } else {
      if (is_null($value)) {
        return "$field IS NULL";
      } else {
        $value = $this->db()->escape($value, $type);
        return "$field = $value";
      }
    }
  }

  public function where($field, $value = null) {
    if (is_array($field)) {
      $fields = array_intersect_key($field, $this->fields());
      $fields = array_map(array($this, 'escape_where'), array_keys($fields), $fields);
      $this->where = implode(' AND ', $fields);
    } elseif ($this->field($field)) {
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
      $fields = array_intersect_key($field, $this->fields());
      $fields = array_map(array($this, 'escape_order_by'), array_keys($fields), $fields);
      $this->order_by = implode(', ', $fields);
    } elseif ($this->field($field)) {
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
    return $this->db()->select_all($sql);
  }

  public function insert($data) {
    $this->values($data);
    $sql = "INSERT INTO $this->table ($this->names) VALUES ($this->values)";
    // database errors trigger error anyway
    return $this->db()->execute($sql);
  }

  public function update($data, $where) {
    $this->set($data);
    $this->where($where);
    $sql = "UPDATE $this->table SET $this->set WHERE $this->where";
    // database errors trigger error anyway
    return $this->db()->execute($sql);
  }

  public function delete($where) {
    $this->where($where);
    $sql = "DELETE FROM $this->table WHERE $this->where";
    // database errors trigger error anyway
    return $this->db()->execute($sql);
  }

  public function validate(&$data) {
    $data = array_intersect_key($data, $this->fields());
    $success = true;
    foreach($data as $name => &$value) {
      $field = $this->field($name);
      Messages::item_field($name);
      if (!$field->validate($value)) {
        $success = false;
      }
    }
    Messages::item_field(null);
    return $success;
  }

  protected function escape_default($name, $value) {
    return "$value AS $name";
  }

  public function defaults() {
    foreach($this->fields() as $name => $field) {
      $defaults[$name] = $field->default_value();
    }
    // exclude nulls to conserve database bandwidth
    $defaults = array_filter($defaults);
    $defaults = array_map(array($this, 'escape_default'), array_keys($defaults), $defaults);
    $defaults = implode(', ', $defaults);
    $sql = "SELECT $defaults";
    $defaults = $this->db()->select_row($sql);
    if (!is_array($defaults)) throw new \InvalidArgumentException("Unable to load defaults for '$this->table'.");
    // add nulls for all fields that didn't have default value
    $nulls = array_fill_keys(array_keys($this->fields()), null);
    $defaults = array_merge($nulls, $defaults);
    return $defaults;
  }
}
