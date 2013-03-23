<?php
namespace databases;

use core\Database;

class Postgresql extends Database implements \interfaces\Database {

  protected function connect() {
    $connection_string = '';
    foreach ($this->config as $name => $value) {
      if ($connection_string != '') $connection_string .= ' ';
      $connection_string .= "$name=$value";
    }
    $this->connection = pg_connect($connection_string);
  }

  public function escape($value, $type = 'text') {
    if (is_null($value) || $value === '') {
      return 'null';
    }

    switch ($type) {
      case 'int2':
      case 'int4':
      case 'int8':
      case 'smallint':
      case 'integer':
      case 'bigint':
      case 'serial':
      case 'bigserial':
        return strval(intval($value));

      case 'real':
      case 'double precision':
        return strval(floatval($value));

      case 'bytea':
        return "'".pg_escape_bytea($this->connection, $value)."'";

      case 'decimal':
      case 'numeric':
        // TODO: better check for numeric, what to do when not?
        //return is_numeric($value) ? strval($value) : 'null';
      case 'varchar':
      case 'char':
      case 'text':
      case 'money':
      case 'timestamp':
      case 'timestamp with time zone':
      case 'date':
      case 'time':
      case 'interval':
      case 'boolean':
      default:
        return "'".pg_escape_string($this->connection, $value)."'";
    }
  }

  protected function query($sql) {
    return pg_query($this->connection, $sql);
  }

  protected function query_params($sql, $params) {
    return pg_query_params($this->connection, $sql, $params);
  }

  protected function fetch_all($result) {
    return pg_fetch_all($result);
  }

  protected function fetch_row($result) {
    return pg_fetch_assoc($result);
  }

  protected function affected_rows($result) {
    return pg_affected_rows($result);
  }

  public function __destruct() {
    pg_close($this->connection);
  }
}
