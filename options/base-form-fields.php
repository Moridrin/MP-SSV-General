<?php

use mp_ssv_general\SSV_General;

if (!defined('ABSPATH')) {
    exit;
}

/** @var wpdb $wpdb */
global $wpdb;
$baseTable = SSV_General::BASE_FIELDS_TABLE;
if (SSV_General::isValidPOST(SSV_General::OPTIONS_ADMIN_REFERER)) {
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
                $wpdb->replace(SSV_General::BASE_FIELDS_TABLE, $properties);
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
}
$baseFields = $wpdb->get_results("SELECT * FROM $baseTable");
echo SSV_General::getInputTypeDataList(['Role Checkbox', 'Role Select']);
?>
<h1>Shared Form Fields</h1>
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
    <div style="overflow-x: auto;">
        <table id="shared-base-fields-placeholder"></table>
        <button type="button" onclick="mp_ssv_add_new_base_input_field()" style="margin-top: 10px;">Add Field</button>
    </div>
    <script>
        var i = <?= count($baseFields) > 0 ? max(array_column($baseFields, 'bf_id')) + 1 : 1 ?>;

        function mp_ssv_add_new_base_input_field() {
            mp_ssv_add_base_input_field('shared-base-fields-placeholder', i, '', '', '');
            document.getElementById(i + '_title').focus();
            i++;
        }
        <?php foreach($baseFields as $baseField): ?>
        mp_ssv_add_base_input_field('shared-base-fields-placeholder', <?= $baseField->bf_id ?>, '<?= $baseField->bf_title ?>', '<?= $baseField->bf_name ?>', '<?= $baseField->bf_inputType ?>', '<?= $baseField->bf_options ?>', '<?= $baseField->bf_value ?>');
        <?php endforeach; ?>
    </script>
    <?= SSV_General::getFormSecurityFields(SSV_General::OPTIONS_ADMIN_REFERER, true, false); ?>
</form>
