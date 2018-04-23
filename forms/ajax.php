<?php

use mp_ssv_general\base\BaseFunctions;
use mp_ssv_general\base\SSV_Global;
use mp_ssv_general\forms\models\Field;
use mp_ssv_general\forms\SSV_Forms;

if (!function_exists('mp_ssv_general_forms_save_field')) {
    function mp_ssv_general_forms_save_field()
    {
        $shared     = BaseFunctions::sanitize($_POST['shared'], 'bool');
        $formId     = BaseFunctions::sanitize($_POST['formId'], 'int');
        $name       = BaseFunctions::sanitize($_POST['properties']['name'], 'text');
        $properties = BaseFunctions::sanitize($_POST['properties'], 'text');
        $oldName    = BaseFunctions::sanitize($_POST['oldName'], 'text');
        Field::save($shared, $formId, $name, $properties, $oldName);
        if (defined('DOING_AJAX') && DOING_AJAX) {
            wp_die(json_encode(['errors' => SSV_Global::getErrors() ?: false]));
        }
    }

    add_action('wp_ajax_mp_ssv_general_forms_save_field', 'mp_ssv_general_forms_save_field', 10, 0);
}

if (!function_exists('mp_ssv_general_forms_delete_field')) {
    function mp_ssv_general_forms_delete_field()
    {
        $shared     = BaseFunctions::sanitize($_POST['shared'], 'bool');
        $formId     = BaseFunctions::sanitize($_POST['formId'], 'int');
        $fieldNames = BaseFunctions::sanitize($_POST['fieldNames'], 'text');
        foreach ($fieldNames as $fieldName) {
            Field::delete($shared, $formId, $fieldNames);
        }
        if (defined('DOING_AJAX') && DOING_AJAX) {
            wp_die(json_encode(['errors' => SSV_Global::getErrors() ?: false]));
        }
    }

    add_action('wp_ajax_mp_ssv_general_forms_delete_field', 'mp_ssv_general_forms_delete_field');
}

if (!function_exists('mp_ssv_general_forms_delete_form')) {
    function mp_ssv_general_forms_delete_form()
    {
        $database = SSV_Global::getDatabase();
        $table    = SSV_Forms::SITE_SPECIFIC_FORMS_TABLE;
        $ids      = implode(', ', $_POST['formIds']);
        $database->query("DELETE FROM $table WHERE f_id IN ($ids)");
        if (defined('DOING_AJAX') && DOING_AJAX) {
            wp_die(json_encode(['errors' => SSV_Global::getErrors() ?: false]));
        }
    }

    add_action('wp_ajax_mp_ssv_general_forms_delete_form', 'mp_ssv_general_forms_delete_form');
}
