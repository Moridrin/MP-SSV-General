<?php

use mp_ssv_general\forms\SSV_Forms;
use mp_ssv_general\base\BaseFunctions;

if (!function_exists('mp_ssv_general_forms_save_shared_base_field')) {
    function mp_ssv_general_forms_save_shared_base_field(bool $die = true)
    {
        /** @var wpdb $wpdb */
        global $wpdb;
        if (!empty($_POST['values']['bf_options'])) {
            $_POST['values']['bf_options'] = json_encode($_POST['values']['bf_options']);
            $_POST['values']['bf_value'] = null;
        } else {
            $_POST['values']['bf_options'] = null;
        }
        $wpdb->replace(
            SSV_Forms::SHARED_BASE_FIELDS_TABLE,
            $_POST['values']
        );
        // TODO update user meta if name is changed.
        if ($die) {
            wp_die();
        }
    }

    add_action('wp_ajax_mp_ssv_general_forms_save_shared_base_field', 'mp_ssv_general_forms_save_shared_base_field');
}

if (!function_exists('mp_ssv_general_forms_delete_shared_base_fields')) {
    function mp_ssv_general_forms_delete_shared_base_fields(bool $die = true)
    {
        /** @var wpdb $wpdb */
        global $wpdb;
        $table = SSV_Forms::SHARED_BASE_FIELDS_TABLE;
        $ids   = implode(', ', $_POST['fieldIds']);
        $wpdb->query("DELETE FROM $table WHERE bf_id IN ($ids)");
        if ($die) {
            wp_die();
        }
    }

    add_action('wp_ajax_mp_ssv_general_forms_delete_shared_base_fields', 'mp_ssv_general_forms_delete_shared_base_fields');
}

if (!function_exists('mp_ssv_general_forms_save_site_specific_base_field')) {
    function mp_ssv_general_forms_save_site_specific_base_field(bool $die = true)
    {
        /** @var wpdb $wpdb */
        global $wpdb;
        if (is_array($_POST['values']['bf_options'])) {
            $_POST['values']['bf_options'] = json_encode($_POST['values']['bf_options']);
        }
        BaseFunctions::var_export($_POST['values'], 1);
        $wpdb->replace(
            SSV_Forms::SITE_SPECIFIC_BASE_FIELDS_TABLE,
            $_POST['values']
        );
        // TODO update user meta if name is changed.
        if ($die) {
            wp_die();
        }
    }

    add_action('wp_ajax_mp_ssv_general_forms_save_site_specific_base_field', 'mp_ssv_general_forms_save_site_specific_base_field');
}

if (!function_exists('mp_ssv_general_forms_delete_site_specific_base_fields')) {
    function mp_ssv_general_forms_delete_site_specific_base_fields(bool $die = true)
    {
        /** @var wpdb $wpdb */
        global $wpdb;
        $table = SSV_Forms::SITE_SPECIFIC_BASE_FIELDS_TABLE;
        $ids   = implode(', ', $_POST['fieldIds']);
        $wpdb->query("DELETE FROM $table WHERE bf_id IN ($ids)");
        if ($die) {
            wp_die();
        }
    }

    add_action('wp_ajax_mp_ssv_general_forms_delete_site_specific_base_fields', 'mp_ssv_general_forms_delete_site_specific_base_fields');
}

if (!function_exists('mp_ssv_general_forms_delete_shared_forms')) {
    function mp_ssv_general_forms_delete_shared_forms(bool $die = true)
    {
        /** @var wpdb $wpdb */
        global $wpdb;
        $table = SSV_Forms::SITE_SPECIFIC_FORMS_TABLE;
        $ids   = implode(', ', $_POST['formIds']);
        $wpdb->query("DELETE FROM $table WHERE f_id IN ($ids)");
        if ($die) {
            wp_die();
        }
    }

    add_action('wp_ajax_mp_ssv_general_forms_delete_shared_forms', 'mp_ssv_general_forms_delete_shared_forms');
}
