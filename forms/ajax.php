<?php

use mp_ssv_general\base\BaseFunctions;
use mp_ssv_general\base\SSV_Global;
use mp_ssv_general\base\User;
use mp_ssv_general\forms\SSV_Forms;

if (!function_exists('mp_ssv_general_forms_save_field')) {
    function mp_ssv_general_forms_save_field()
    {
        $shared = BaseFunctions::sanitize($_POST['shared'], 'bool');
        $formId = BaseFunctions::sanitize($_POST['formId'], 'int');
        $name = BaseFunctions::sanitize($_POST['properties']['name'], 'text');
        $database = SSV_Global::getDatabase();
        if ($formId !== null) {
            $table = SSV_Forms::CUSTOMIZED_FIELDS_TABLE;
            $values = [
                'f_id' => $formId,
                'bf_name' => $name,
                'bf_properties' => json_encode(BaseFunctions::sanitize($_POST['properties'], 'text')),
            ];
        } elseif ($shared) {
            $table = SSV_Forms::SHARED_BASE_FIELDS_TABLE;
            $values = [
                'bf_name' => $name,
                'bf_properties' => json_encode(BaseFunctions::sanitize($_POST['properties'], 'text')),
            ];
        } else {
            $table = SSV_Forms::SITE_SPECIFIC_BASE_FIELDS_TABLE;
            $values = [
                'bf_name' => $name,
                'bf_properties' => json_encode(BaseFunctions::sanitize($_POST['properties'], 'text')),
            ];
        }
        $oldName = BaseFunctions::sanitize($_POST['oldName'], 'text');
        if (empty($oldName)) {
            $database->insert($table, $values);
        } elseif ($oldName !== $values['bf_name']) {
            $userIds = $database->get_col('SELECT ID FROM ' . $database->getUsersTable());
            foreach ($userIds as $userId) {
                $user = User::getByID($userId);
                $value = $user->getMeta($oldName);
                $success = $user->updateMeta($values['bf_name'], $value);
                if ($success) {
                    $user->removeMeta($values['bf_name']);
                }
            }
            $database->update($table, $values, ['bf_name' => BaseFunctions::sanitize($_POST['oldName'], 'text')]);
        } else {
            $database->replace($table, $values);
        }
        if (defined('DOING_AJAX') && DOING_AJAX) {
            wp_die(SSV_Global::getErrors());
        }
    }

    add_action('wp_ajax_mp_ssv_general_forms_save_field', 'mp_ssv_general_forms_save_field', 10, 0);
}

if (!function_exists('mp_ssv_general_forms_delete_fields')) {
    function mp_ssv_general_forms_delete_fields(bool $shared = null, int $formId = null)
    {
        if ($shared === null && isset($_POST['shared'])) {
            $shared = BaseFunctions::sanitize($_POST['shared'], 'bool');
        }
        if ($formId === null && isset($_POST['formId'])) {
            $formId = BaseFunctions::sanitize($_POST['formId'], 'int');
        }
        $fieldNames = BaseFunctions::sanitize($_POST['fieldNames'], 'text');
        $database = SSV_Global::getDatabase();
        if ($formId !== null) {
            $table = SSV_Forms::SITE_SPECIFIC_FORMS_TABLE;
            $formFields = json_decode($database->get_var("SELECT f_fields FROM $table WHERE f_id = $formId"), true);
            $formFields = array_diff($formFields, $fieldNames);
            $database->update($table, ['f_fields' => $formFields], ['f_id' => $formId]);
        } else {
            if ($shared) {
                $table = SSV_Forms::SHARED_BASE_FIELDS_TABLE;
            } else {
                $table = SSV_Forms::SITE_SPECIFIC_BASE_FIELDS_TABLE;
            }
            $fieldNames   = '\'' . implode('\', \'', $fieldNames) . '\'';
            $database->query("DELETE FROM $table WHERE bf_name IN ($fieldNames)");
        }
        if (defined('DOING_AJAX') && DOING_AJAX) {
            wp_die(SSV_Global::getErrors());
        }
    }

    add_action('wp_ajax_mp_ssv_general_forms_delete_fields', 'mp_ssv_general_forms_delete_fields');
}

if (!function_exists('mp_ssv_general_forms_delete_shared_forms')) {
    function mp_ssv_general_forms_delete_shared_forms(bool $ajaxRequest = true)
    {
        $database = SSV_Global::getDatabase();
        $table = SSV_Forms::SITE_SPECIFIC_FORMS_TABLE;
        $ids   = implode(', ', $_POST['formIds']);
        $database->query("DELETE FROM $table WHERE f_id IN ($ids)");
        if ($ajaxRequest) {
            wp_die(SSV_Global::getErrors());
        }
    }

    add_action('wp_ajax_mp_ssv_general_forms_delete_shared_forms', 'mp_ssv_general_forms_delete_shared_forms');
}
