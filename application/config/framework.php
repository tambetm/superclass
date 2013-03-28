<?php
// defaults
define('USE_COLLECTION', false);         // is first URL segment namespace?
define('DEFAULT_COLLECTION', 'public');  // controller subdirectory or model subdirectory. also determines database schema.
define('DEFAULT_RESOURCE', 'home');        // controller class or model class. also determines database table.
define('DEFAULT_ACTION', 'table');        // controller method or view template or (together with model) view name.
define('DEFAULT_METHOD', 'get');        // view method
define('DEFAULT_LAYOUT', 'simple');     // default layout

// default classes
define('DEFAULT_CONTROLLER_CLASS', 'core\SmartController');
define('DEFAULT_MODEL_CLASS', 'core\Model');

// system classes
define('ERROR_HANDLER_CLASS', 'core\DatabaseErrorHandler');
define('ERROR_LAYOUT_CLASS', 'templates\layouts\Error');

// namespaces (folders)
define('CONTROLLER_NAMESPACE', 'controllers');
define('MODEL_NAMESPACE', 'models');
define('VIEW_NAMESPACE', 'views');
define('FIELD_NAMESPACE', 'fields');
define('DOMAIN_NAMESPACE', 'domains');
define('TYPE_NAMESPACE', 'types');
define('DATABASE_DRIVER_NAMESPACE', 'drivers\database');
define('TEMPLATE_VIEW_NAMESPACE', 'templates\views');
define('TEMPLATE_LAYOUT_NAMESPACE', 'templates\layouts');

// separators
define('NAMESPACE_SEPARATOR', '\\');
define('SCHEMA_SEPARATOR', '.');
