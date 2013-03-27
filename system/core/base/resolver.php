<?php
namespace core\base;

use helpers\URL;
use helpers\String;

class Resolver {

  static public function get_class_base($resource) {
    if (String::contains($resource, '/')) {
      list($namespace, $class) = explode('/', $resource);
      return $namespace.NAMESPACE_SEPARATOR.String::camelcase($class);
    } else {
      return String::camelcase($resource);
    }
  }

  static public function get_controller_class($resource) {
    $class_base = self::get_class_base($resource);

    $controller_class = CONTROLLER_NAMESPACE.NAMESPACE_SEPARATOR.$class_base;
    if (class_exists($controller_class)) return $controller_class;

    $controller_class = DEFAULT_CONTROLLER_CLASS;
    if (class_exists($controller_class)) return $controller_class;

    throw new \InvalidArgumentException("Controller class does not exist for '$resource'");
  }

  static public function get_controller_method($action) {
    return $action;
  }

  static public function get_view_class($resource, $action) {
    $action = String::camelcase($action);
    $class_base = self::get_class_base($resource);

    $view_class = VIEW_NAMESPACE.NAMESPACE_SEPARATOR.$class_base.$action;
    if (class_exists($view_class)) return $view_class;

    $view_class = VIEW_NAMESPACE.NAMESPACE_SEPARATOR.$class_base;
    if (class_exists($view_class)) return $view_class;

    $view_class = TEMPLATE_VIEW_NAMESPACE.NAMESPACE_SEPARATOR.$action;
    if (class_exists($view_class)) return $view_class;

    throw new \InvalidArgumentException("View template class does not exist for '$action'");
  }

  static public function get_model_class($resource) {
    $class_base = self::get_class_base($resource);

    $model_class = MODEL_NAMESPACE.NAMESPACE_SEPARATOR.$class_base;
    if (class_exists($model_class)) return $model_class;

    $model_class = DEFAULT_MODEL_CLASS;
    if (class_exists($model_class)) return $model_class;

    throw new \InvalidArgumentException("Model class does not exist for '$resource'");
  }

  static public function get_database_table($resource) {
    return str_replace('/', SCHEMA_SEPARATOR, $resource);
  }

  static public function get_layout_class($layout) {
    $layout_class = TEMPLATE_LAYOUT_NAMESPACE.NAMESPACE_SEPARATOR.String::camelcase($layout);
    if (class_exists($layout_class)) return $layout_class;

    throw new \InvalidArgumentException("Layout class does not exist for '$layout'");
  }

  static public function get_database_class($driver) {
    $driver_class = DATABASE_DRIVER_NAMESPACE.NAMESPACE_SEPARATOR.String::camelcase($driver);
    if (class_exists($driver_class)) return $driver_class;

    throw new \InvalidArgumentException("Database driver class does not exist for '$driver'");
  }

  static public function get_field_class($column) {
    $field_class = FIELD_NAMESPACE.NAMESPACE_SEPARATOR.String::camelcase($column['table_name']).String::camelcase($column['column_name']);
    if (class_exists($field_class)) return $field_class;

    if ($column['domain_name']) {
      $field_class = DOMAIN_NAMESPACE.NAMESPACE_SEPARATOR.String::camelcase($column['domain_name']);
      if (class_exists($field_class)) return $field_class;
    }

    $field_class = TYPE_NAMESPACE.NAMESPACE_SEPARATOR.String::camelcase($column['data_type']);
    if (class_exists($field_class)) return $field_class;

    throw new \InvalidArgumentException("Could not find class for field '$column[column_name]'");
  }
}
