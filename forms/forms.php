<?php

$currentDir = getcwd();
chdir(__DIR__ . '/../..');
define('SSV_FORMS_ACTIVATOR_PLUGIN', getcwd() . DIRECTORY_SEPARATOR . basename(getcwd()) . '.php');
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
switch (true) {
    case current_theme_supports('materialize'):
        require_once 'templates/materialize/materialize.php';
        break;
    default:
        require_once 'templates/base/base.php';
        break;
}
