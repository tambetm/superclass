<?php
// default resource and action (controller and method)
define('DEFAULT_RESOURCE', 'yhistud');
define('DEFAULT_ACTION', 'table');
define('DEFAULT_METHOD', 'render');

// default classes
define('DEFAULT_LAYOUT_CLASS', 'templates\layouts\Simple');
define('DEFAULT_CONTROLLER_CLASS', 'core\Controller');
define('DEFAULT_MODEL_CLASS', 'core\Model');

// system classes
define('ERROR_HANDLER_CLASS', 'core\DatabaseErrorHandler');

// namespaces (folders)
define('CONTROLLER_NAMESPACE', 'controllers');
define('MODEL_NAMESPACE', 'models');
define('VIEW_NAMESPACE', 'views');
define('FIELD_NAMESPACE', 'fields');
define('DATABASE_NAMESPACE', 'drivers\database');
define('TEMPLATE_VIEW_NAMESPACE', 'templates\views');

// separators
define('NAMESPACE_SEPARATOR', '\\');
define('SCHEMA_SEPARATOR', '.');
