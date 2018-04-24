<?php

use mp_ssv_general\forms\models\Field;
use mp_ssv_general\forms\models\Form;
use mp_ssv_general\forms\models\FormField;
use mp_ssv_general\forms\models\SharedBaseField;
use mp_ssv_general\forms\models\SiteSpecificBaseField;
use mp_ssv_general\forms\Options;
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

if (!class_exists(Options::class)) {
    require_once 'Options.php';
}

if (!class_exists(Form::class)) {
    require_once 'models/Form.php';
}

if (!class_exists(Field::class)) {
    require_once 'models/Field.php';
}

if (!class_exists(FormField::class)) {
    require_once 'models/FormField.php';
}

if (!class_exists(SharedBaseField::class)) {
    require_once 'models/SharedBaseField.php';
}

if (!class_exists(SiteSpecificBaseField::class)) {
    require_once 'models/SiteSpecificBaseField.php';
}

require_once 'ajax.php';
