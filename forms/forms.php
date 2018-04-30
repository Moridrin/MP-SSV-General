<?php

$currentDir = getcwd();
chdir(__DIR__ . '/../..');
define('SSV_FORMS_ACTIVATOR_PLUGIN', getcwd() . DIRECTORY_SEPARATOR . glob('ss?-*.php')[0]);
chdir($currentDir);
define('SSV_FORMS_PATH', plugin_dir_path(__FILE__));
define('SSV_FORMS_URL', plugins_url() . '/' . plugin_basename(__DIR__));

require_once 'SSV_Forms.php';
require_once 'Options.php';
require_once 'models/Form.php';
require_once 'models/Field.php';
require_once 'models/FormField.php';
require_once 'models/SharedField.php';
require_once 'models/SiteSpecificField.php';
require_once 'models/WordPressField.php';
require_once 'ajax.php';
