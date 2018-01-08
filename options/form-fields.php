<?php

use mp_ssv_general\custom_fields\Field;
use mp_ssv_general\custom_fields\InputField;
use mp_ssv_general\SSV_General;

if (!defined('ABSPATH')) {
    exit;
}

#region Enquire Scripts
function mp_ssv_form_fields_creator_scripts($hook)
{
    wp_enqueue_script('ssv_form_fields_creator', SSV_General::URL . '/js/mp-ssv-custom-field-creator.js');
}
add_action('admin_enqueue_scripts', 'mp_ssv_form_fields_creator_scripts');
#endregion

#region Setup Menu
function ssv_add_ssv_network_admin_form_fields_creator_menu()
{
    add_submenu_page('ssv_settings', 'Form Fields', 'Form Fields', 'edit_posts', 'ssv-users-settings', 'ssv_form_fields_creator_menu_page_network_admin');
}

add_action('network_admin_menu', 'ssv_add_ssv_network_admin_form_fields_creator_menu', 9);
#endregion

#region Content
function ssv_form_fields_creator_menu_page_network_admin()
{
    /** @var wpdb $wpdb */
    global $wpdb;
    $baseTable       = SSV_General::CUSTOM_FIELDS_TABLE;
    $customizedTable = SSV_General::CUSTOM_FORM_FIELDS_TABLE;

    if (SSV_General::isValidPOST(SSV_General::OPTIONS_ADMIN_REFERER)) {
        if (isset($_POST['reset'])) {
            SSV_General::resetOptions();
        } else {
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
                        <?php foreach($baseFields as $fieldID => $baseField): ?>
                        <?php $field = Field::fromJSON($baseField->json); ?>
                        mp_ssv_add_custom_input_field('shared-custom-fields-placeholder', <?= $fieldID ?>, '<?= isset($field->inputType) ? esc_html($field->inputType) : '' ?>', <?= $field->toJSON() ?>, false);
                        <?php endforeach; ?>
                    </script>
                </td>
            </tr>
        </table>
        <?= SSV_General::getFormSecurityFields(SSV_General::OPTIONS_ADMIN_REFERER, true, false); ?>
    </form>
    <?php
}
#endregion
