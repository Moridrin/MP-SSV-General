<?php

use mp_ssv_general\custom_fields\Field;
use mp_ssv_general\custom_fields\InputField;
use mp_ssv_general\SSV_General;

if (!defined('ABSPATH')) {
    exit;
}

function mp_ssv_form_fields_creator_scripts($hook)
{
    wp_enqueue_script('ssv_form_fields_creator', SSV_General::URL . '/js/mp-ssv-custom-field-creator.js');
}

add_action('admin_enqueue_scripts', 'mp_ssv_form_fields_creator_scripts');

function ssv_add_ssv_network_admin_form_fields_creator_menu()
{
    add_submenu_page('ssv_settings', 'Form Fields', 'Form Fields', 'edit_posts', 'ssv-users-settings', 'ssv_form_fields_creator_menu_page_network_admin');
}

add_action('network_admin_menu', 'ssv_add_ssv_network_admin_form_fields_creator_menu', 9);

function ssv_form_fields_creator_menu_page_network_admin()
{
    /** @var wpdb $wpdb */
    global $wpdb;
    $baseTable       = SSV_General::BASE_FIELDS_TABLE;
    $customizedTable = SSV_General::CUSTOMIZED_FIELDS_TABLE;

    if (SSV_General::isValidPOST(SSV_General::OPTIONS_ADMIN_REFERER)) {
        if (isset($_POST['reset'])) {
            SSV_General::resetOptions();
        } else {
            $fieldIds = SSV_General::sanitize($_POST['field_ids'], 'int');
            $fieldIds = is_array($fieldIds) ? $fieldIds : [];

            if (current_user_can('remove_custom_fields')) {
                if (!empty($fieldIds)) {
                    $databaseFieldIds = implode(", ", $fieldIds);
                    $databaseOldFieldIds           = implode(', ', $wpdb->get_results("SELECT bf_id FROM $baseTable WHERE bf_id NOT IN ($databaseFieldIds)"));
                    $wpdb->query("DELETE FROM $baseTable WHERE bf_id NOT IN ($databaseFieldIds)");
                    $wpdb->query("DELETE FROM $customizedTable WHERE cf_bf_id IN ($databaseOldFieldIds)");
                } else {
                    $wpdb->query("TRUNCATE $baseTable");
                    $wpdb->query("TRUNCATE $customizedTable");
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
                $properties['bf_type'] = InputField::FIELD_TYPE;
                $wpdb->replace(SSV_General::BASE_FIELDS_TABLE, $properties);
//                /** @var InputField $field */
//                $field   = Field::fromJSON(json_encode($properties));
//                SSV_General::var_export($field, 1);
//                $oldName = $wpdb->get_row("SELECT `name` FROM $baseTable WHERE ID = $fieldId")->name;
//                $name    = $field->name;
//                if ($oldName !== null && $name != $oldName) {
//                    $wpdb->update($wpdb->usermeta, array('meta_key' => $name), array('meta_key' => $oldName));
//                    $wpdb->update($customizedTable, array('name' => $name), array('name' => $oldName));
//                }
//                if (current_user_can('edit_custom_fields')) {
//                    $wpdb->replace(
//                        $baseTable,
//                        array(
//                            'ID'    => $fieldId,
//                            'name'  => $name,
//                            'title' => $field->title,
//                            'json'  => $field->toJSON(),
//                        )
//                    );
//                } elseif (current_user_can('add_custom_fields')) {
//                    $wpdb->insert(
//                        $baseTable,
//                        array(
//                            'ID'    => $fieldId,
//                            'name'  => $name,
//                            'title' => $field->title,
//                            'json'  => $field->toJSON(),
//                        )
//                    );
//                }
            }
        }
    }
    $baseFields = $wpdb->get_results("SELECT * FROM $baseTable");
    $baseFields = array_combine(array_column($baseFields, 'ID'), $baseFields);
    echo SSV_General::getInputTypeDataList();
    ?>
    <h1>Form Fields</h1>
    <p>These fields will be available for all sites.</p>
    <?php if (!current_user_can('add_custom_fields')): ?>
    <div class="notice">
        <p>You are not allowed to add custom fields.</p>
    </div>
<?php endif; ?>
    <?php if (!current_user_can('edit_custom_fields')): ?>
    <div class="notice">
        <p>You are not allowed to edit existing custom fields.</p>
    </div>
<?php endif; ?>
    <?php if (!current_user_can('remove_custom_fields')): ?>
    <div class="notice">
        <p>You are not allowed to remove custom fields.</p>
    </div>
<?php endif; ?>
    <form method="post" action="#">
        <table class="form-table">
            <tr>
                <td colspan="2" style="padding: 0;">
                    <div style="overflow-x: auto;">
                        <table id="shared-custom-fields-placeholder"></table>
                        <button type="button" onclick="mp_ssv_add_new_custom_field()" style="margin-top: 10px;">Add Field</button>
                    </div>
                    <script>
                        var i = <?= count($baseFields) > 0 ? max(array_keys($baseFields)) + 1 : 1 ?>;

                        function mp_ssv_add_new_custom_field() {
                            mp_ssv_add_custom_input_field('shared-custom-fields-placeholder', i, 'text', {"override_right": ""}, false);
                            i++;
                        }
                        <?php foreach($baseFields as $fieldId => $baseField): ?>
                        <?php $field = Field::fromJSON($baseField->json); ?>
                        mp_ssv_add_custom_input_field('shared-custom-fields-placeholder', <?= $fieldId ?>, '<?= isset($field->inputType) ? esc_html($field->inputType) : '' ?>', <?= $field->toJSON() ?>, false);
                        <?php endforeach; ?>
                    </script>
                </td>
            </tr>
        </table>
        <?= SSV_General::getFormSecurityFields(SSV_General::OPTIONS_ADMIN_REFERER, true, false); ?>
    </form>
    <?php
}
