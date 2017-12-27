<?php

use mp_ssv_general\custom_fields\Field;
use mp_ssv_general\custom_fields\InputField;
use mp_ssv_general\BaseFunctions;

if (!defined('ABSPATH')) {
    exit;
}

/** @var wpdb $wpdb */
global $wpdb;
$baseTable       = BaseFunctions::SHARED_BASE_FIELDS_TABLE;
$customizedTable = BaseFunctions::CUSTOMIZED_FIELDS_TABLE;

if (BaseFunctions::isValidPOST(BaseFunctions::OPTIONS_ADMIN_REFERER)) {
    if (isset($_POST['reset'])) {
        BaseFunctions::resetOptions();
    } else {
        $fieldIDs = BaseFunctions::sanitize($_POST['field_ids'], 'int');
        $fieldIDs = is_array($fieldIDs) ? $fieldIDs : array();

        if (current_user_can('remove_custom_fields')) {
            $oldNames = $wpdb->get_results("SELECT ID, `name` FROM $baseTable");
            $oldNames = array_combine(array_column($oldNames, 'ID'), array_column($oldNames, 'name'));
            if (!empty($fieldIDs)) {
                $databaseFieldIDs  = implode(", ", $fieldIDs);
                $fieldsToBeRemoved = array_diff_key($oldNames, array_fill_keys($fieldIDs, ''));
                $wpdb->query("DELETE FROM $baseTable WHERE ID NOT IN ($databaseFieldIDs)");
                $wpdb->query("DELETE FROM $customizedTable WHERE `name` IN ($fieldsToBeRemoved)");
            } else {
                $wpdb->query("DELETE FROM $baseTable WHERE 1");
                $wpdb->query("DELETE FROM $customizedTable WHERE `name` IN ($oldNames)");
                $fieldIDs = array();
            }
        }
        foreach ($fieldIDs as $fieldID) {
            $properties = array_filter(
                $_POST,
                function ($key) use ($fieldID) {
                    return mp_ssv_starts_with($key, 'custom_field_' . $fieldID . '_');
                },
                ARRAY_FILTER_USE_KEY
            );
            foreach ($properties as $key => $property) {
                if (mp_ssv_starts_with($key, 'custom_field_' . $fieldID . '_')) {
                    $properties[str_replace('custom_field_' . $fieldID . '_', '', $key)] = $property;
                    unset($properties[$key]);
                }
            }
            $properties['field_type'] = InputField::FIELD_TYPE;
            /** @var InputField $field */
            $field   = Field::fromJSON(json_encode($properties));
            $oldName = $wpdb->get_row("SELECT `name` FROM $baseTable WHERE ID = $fieldID")->name;
            $name    = $field->name;
            if ($oldName !== null && $name != $oldName) {
                $wpdb->update($wpdb->usermeta, array('meta_key' => $name), array('meta_key' => $oldName));
                $wpdb->update($customizedTable, array('name' => $name), array('name' => $oldName));
            }
            if (current_user_can('edit_custom_fields')) {
                $wpdb->replace(
                    $baseTable,
                    array(
                        'ID'    => $fieldID,
                        'name'  => $name,
                        'title' => $field->title,
                        'json'  => $field->toJSON(),
                    )
                );
            } elseif (current_user_can('add_custom_fields')) {
                $wpdb->insert(
                    $baseTable,
                    array(
                        'ID'    => $fieldID,
                        'name'  => $name,
                        'title' => $field->title,
                        'json'  => $field->toJSON(),
                    )
                );
            }
        }
    }
}
$baseFields = $wpdb->get_results("SELECT * FROM $baseTable");
$baseFields = array_combine(array_column($baseFields, 'ID'), $baseFields);
echo BaseFunctions::getInputTypeDataList();
?>
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
    <?= BaseFunctions::getFormSecurityFields(BaseFunctions::OPTIONS_ADMIN_REFERER, true, false); ?>
</form>
