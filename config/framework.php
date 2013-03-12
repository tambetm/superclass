<?php

// separators
define('NAMESPACE_SEPARATOR', '_');
define('SCHEMA_SEPARATOR', '.');

// core classes
define('ERROR_HANDLER_CLASS', 'core_ErrorHandler');
define('CONTEXT_CLASS', 'core_Context');

// base classes
define('DEFAULT_CONTROLLER_CLASS', 'core_BaseController');
define('DEFAULT_MODEL_CLASS', 'core_BaseModel');
define('DEFAULT_PAGE_CLASS', 'pages_Bootstrap');

// namespaces (folders)
define('CONTROLLER_NAMESPACE', 'controllers');
define('MODEL_NAMESPACE', 'models');
define('VIEW_NAMESPACE', 'views');

// default resource and action (controller and method)
define('DEFAULT_CONTROLLER', 'Home');
define('DEFAULT_METHOD', 'index');
define('DEFAULT_REGION', 'main');
