<?php
// default resource and action (controller and method)
define('DEFAULT_RESOURCE', 'yhistud');
define('DEFAULT_ACTION', 'table');
define('DEFAULT_METHOD', 'render');

// base classes
define('DEFAULT_LAYOUT_CLASS', 'layouts\Bootstrap');
define('DEFAULT_CONTROLLER_CLASS', 'core\BaseController');
define('DEFAULT_MODEL_CLASS', 'core\BaseModel');

// core classes
define('DATABASE_CLASS', 'core_Database');
define('CONTEXT_CLASS', 'core\Context');
define('ERROR_HANDLER_CLASS', 'core\ErrorHandler');

// namespaces (folders)
define('CONTROLLER_NAMESPACE', 'controllers');
define('MODEL_NAMESPACE', 'models');
define('VIEW_NAMESPACE', 'views');
define('FIELD_NAMESPACE', 'fields');

// separators
define('NAMESPACE_SEPARATOR', '\\');
define('SCHEMA_SEPARATOR', '.');
