<?php

use mp_ssv_general\SSV_Base;

if (!defined('ABSPATH')) {
    exit;
}

function mp_ssv_general_delete_selected_base_fields()
{
    /** @var wpdb $wpdb */
    global $wpdb;
    $baseTable = SSV_Base::SHARED_BASE_FIELDS_TABLE;
    $selectedFieldIds = $_POST['selected_field_ids'];
    if (current_user_can('manage_base_fields')) {
        $wpdb->query("DELETE FROM $baseTable WHERE bf_id IN ($selectedFieldIds)");
    }
    wp_die();
}
add_action('wp_ajax_mp_ssv_general_delete_selected_base_fields', 'mp_ssv_general_delete_selected_base_fields');

function mp_ssv_general_save_base_field()
{
    /** @var wpdb $wpdb */
    global $wpdb;
    $baseTable = SSV_Base::SHARED_BASE_FIELDS_TABLE;
    $selectedFieldIds = $_POST['selected_field_ids'];
    if (current_user_can('manage_base_fields')) {
        $wpdb->query("DELETE FROM $baseTable WHERE bf_id IN ($selectedFieldIds)");
    }
    wp_die();
}
add_action('wp_ajax_mp_ssv_general_save_base_field', 'mp_ssv_general_save_base_field');

//if (isset($_POST['reset'])) {
//    SSV_General::resetOptions();
//} else {
//    $fieldIds = SSV_General::sanitize($_POST['field_ids'], 'int');
//    $fieldIds = is_array($fieldIds) ? $fieldIds : [];
//    if (current_user_can('remove_custom_fields')) {
//        if (!empty($fieldIds)) {
//            $databaseFieldIds = implode(", ", $fieldIds);
//        } else {
//            $wpdb->query("TRUNCATE $baseTable");
//            $fieldIds = [];
//        }
//    }
//    foreach ($fieldIds as $fieldId) {
//        $properties = array_filter(
//            $_POST,
//            function ($key) use ($fieldId) {
//                return mp_ssv_starts_with($key, 'custom_field_' . $fieldId . '_');
//            },
//            ARRAY_FILTER_USE_KEY
//        );
//
//        foreach ($properties as $key => $property) {
//            if (mp_ssv_starts_with($key, 'custom_field_' . $fieldId . '_')) {
//                $properties[str_replace('custom_field_' . $fieldId . '_', 'bf_', $key)] = $property;
//                unset($properties[$key]);
//            }
//        }
//        if (current_user_can('edit_custom_fields')) {
//            $currentField = $wpdb->get_row("SELECT bf_name FROM $baseTable WHERE bf_id = $fieldId");
//            $wpdb->replace($baseTable, $properties);
//            if ($currentField !== null) {
//                SSV_General::var_export($properties);
//                if ($properties['bf_name'] != $currentField->bf_name && $currentField->bf_name !== null) {
//                    $wpdb->update($wpdb->usermeta, ['meta_key' => $properties['bf_name']], ['meta_key' => $currentField->bf_name]);
//                    $wpdb->update($baseTable, ['name' => $properties['bf_name']], ['name' => $currentField->bf_name]);
//                }
//            }
//        } elseif (current_user_can('add_custom_fields')) {
//            $wpdb->insert($baseTable, $properties);
//        }
//    }
//}
