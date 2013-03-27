<?php
// default resource and action
define('USE_COLLECTION', false);            // is first URL segment namespace or table?
define('DEFAULT_COLLECTION', 'public');     // database schema or controller subnamespace
define('DEFAULT_RESOURCE', 'home');      // database table or controller class
define('DEFAULT_ACTION', 'table');          // controller method or view template
define('DEFAULT_SUBACTION', 'render');      // view or layout method
define('DEFAULT_LAYOUT', 'simple');

// default classes
define('DEFAULT_CONTROLLER_CLASS', 'core\Controller');
define('DEFAULT_MODEL_CLASS', 'core\Model');

// system classes
define('ERROR_HANDLER_CLASS', 'core\DatabaseErrorHandler');

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
