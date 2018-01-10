<?php

use mp_ssv_general\Field;
use mp_ssv_general\forms\options\Forms;
use mp_ssv_general\forms\SSV_Forms;

if (!class_exists(SSV_Forms::class)) {
    $currentDir = getcwd();
    chdir(__DIR__ . '/../..');
    define('SSV_FORMS_ACTIVATOR_PLUGIN', getcwd() . DIRECTORY_SEPARATOR . glob('ss?-*.php')[0]);
    chdir($currentDir);
    define('SSV_FORMS_PATH', plugin_dir_path(__FILE__));
    define('SSV_FORMS_URL', plugins_url() . '/' . plugin_basename(__DIR__));
    global $wpdb;
    define('SSV_FORMS_SHARED_BASE_FIELDS_TABLE', $wpdb->base_prefix . 'ssv_shared_base_fields');
    define('SSV_FORMS_SITE_SPECIFIC_BASE_FIELDS_TABLE', $wpdb->prefix . 'ssv_base_fields');
    define('SSV_FORMS_CUSTOMIZED_FIELDS', $wpdb->prefix . 'ssv_customized_fields');
    define('SSV_FORMS_SITE_SPECIFIC_FORMS_TABLE', $wpdb->prefix . 'ssv_forms');

    require_once 'SSV_Forms.php';
}

if (!class_exists(Forms::class)) {
    require_once 'models/Forms.php';
}

if (!class_exists(Field::class)) {
    require_once 'models/Field.php';
}

require_once 'ajax.php';
