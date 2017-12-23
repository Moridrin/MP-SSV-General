<?php

use mp_ssv_general\SSV_General;

if (!defined('ABSPATH')) {
    exit;
}

/** @var wpdb $wpdb */
global $wpdb;

if (isset($_POST['reset'])) {
    SSV_General::resetOptions();
} else {
    $fieldIds = SSV_General::sanitize($_POST['field_ids'], 'int');
    $fieldIds = is_array($fieldIds) ? $fieldIds : [];
    if (current_user_can('remove_custom_fields')) {
        if (!empty($fieldIds)) {
            $databaseFieldIds = implode(", ", $fieldIds);
            $wpdb->query("DELETE FROM $baseTable WHERE bf_id NOT IN ($databaseFieldIds)");
        } else {
            $wpdb->query("TRUNCATE $baseTable");
            $fieldIds = [];
        }
    }
    foreach ($fieldIds as $fieldId) {
        $properties = array_filter(
            $_POST,
            function ($key) use ($fieldId) {
                return mp_ssv_starts_with($key, 'custom_field_' . $fieldId . '_');
            },
            ARRAY_FILTER_USE_KEY
        );

        foreach ($properties as $key => $property) {
            if (mp_ssv_starts_with($key, 'custom_field_' . $fieldId . '_')) {
                $properties[str_replace('custom_field_' . $fieldId . '_', 'bf_', $key)] = $property;
                unset($properties[$key]);
            }
        }
        if (current_user_can('edit_custom_fields')) {
            $currentField = $wpdb->get_row("SELECT bf_name FROM $baseTable WHERE bf_id = $fieldId");
            $wpdb->replace($baseTable, $properties);
            if ($currentField !== null) {
                if ($properties['bf_name'] != $currentField->bf_name && $currentField->bf_name !== null) {
                    $wpdb->update($wpdb->usermeta, ['meta_key' => $properties['bf_name']], ['meta_key' => $currentField->bf_name]);
                    $wpdb->update($baseTable, ['name' => $properties['bf_name']], ['name' => $currentField->bf_name]);
                }
            }
        } elseif (current_user_can('add_custom_fields')) {
            $wpdb->insert($baseTable, $properties);
        }
    }
}
