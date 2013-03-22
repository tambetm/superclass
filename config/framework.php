<?php

// separators
define('NAMESPACE_SEPARATOR', '\\');
define('SCHEMA_SEPARATOR', '.');

// core classes
define('ERROR_HANDLER_CLASS', 'core\ErrorHandler');
define('CONTEXT_CLASS', 'core\Context');
define('DATABASE_CLASS', 'core_Database');

// base classes
define('DEFAULT_CONTROLLER_CLASS', 'core\BaseController');
define('DEFAULT_MODEL_CLASS', 'core\BaseModel');
define('DEFAULT_LAYOUT_CLASS', 'layouts\Bootstrap');

// namespaces (folders)
define('CONTROLLER_NAMESPACE', 'controllers');
define('MODEL_NAMESPACE', 'models');
define('VIEW_NAMESPACE', 'views');
define('TYPE_NAMESPACE', 'types');
define('ACTION_NAMESPACE', 'actions');

// default resource and action (controller and method)
define('DEFAULT_CONTROLLER', 'Yhistud');
define('DEFAULT_METHOD', 'table');
define('DEFAULT_REGION', 'content');
