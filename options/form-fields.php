<?php

use mp_ssv_general\custom_fields\Field;
use mp_ssv_general\custom_fields\InputField;
use mp_ssv_general\SSV_General;

if (!defined('ABSPATH')) {
    exit;
}

/** @var wpdb $wpdb */
global $wpdb;
$baseTable       = SSV_General::BASE_FIELDS_TABLE;
$customizedTable = SSV_General::CUSTOMIZED_FIELDS_TABLE;

if (SSV_General::isValidPOST(SSV_General::OPTIONS_ADMIN_REFERER)) {
    if (isset($_POST['reset'])) {
        SSV_General::resetOptions();
    } else {
        $fieldIDs = SSV_General::sanitize($_POST['field_ids'], 'int');
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
?>
<h1>Shared Form Fields</h1>
<table class="form-table">
    <?php
    foreach ($baseFields as $baseField) {
        ?>
        <tr id="3_tr">
            <td style="padding: 0;">
                <label style="white-space: nowrap;">Field Title</label><br>
                <input style="width: 100%;" value="<?=$baseField->bf_title?>" readonly>
            </td>
            <td style="padding: 0;">
                <label style="white-space: nowrap;">Name</label><br>
                <input style="width: 100%;" pattern="[a-z0-9_]+" value="<?=$baseField->bf_name?>" readonly>
            </td>
            <td style="padding: 0;">
                <label style="white-space: nowrap;">Input Type</label><br>
                <input style="width: 100%;" list="inputType" value="<?=$baseField->bf_inputType?>" readonly>
            </td>
            <?php
            switch ($baseField->bf_inputType) {
                case 'select':
                    ?>
                    <td class="options_td" style="padding: 0;">
                        <label style="white-space: nowrap;">Options</label><br>
                        <input style="width: 100%;" placeholder="Separate with \',\'" value="<?= $baseField->bf_options ?>" readonly>
                    </td>
                    <?php
                    break;
                case 'hidden':
                    ?>
                    <td style="padding: 0;">
                        <label style="white-space: nowrap;">Value</label><br>
                        <input style="width: 100%;" value="<?= $baseField->bf_value ?>" readonly>
                    </td>
                    <?php
                    break;
                default:
                    ?>
                    <td style="padding: 0;" id="3_empty_td"></td>
                    <?php
            }
            ?>
            <td style="padding: 0;" id="3_empty_td"></td>
        </tr>
        <?php
    }
    ?>
</table>
<?php

?>
<h1>Site Specific Form Fields</h1>
<?php
echo SSV_General::getInputTypeDataList();
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
    <div style="overflow-x: auto;">
        <table id="site-specific-base-fields-placeholder"></table>
        <button type="button" onclick="mp_ssv_add_new_custom_field()" style="margin-top: 10px;">Add Field</button>
    </div>
    <script>
        var i = <?= count($baseFields) > 0 ? max(array_column($baseFields, 'bf_id')) + 1 : 1 ?>;

        function mp_ssv_add_new_base_input_field() {
            mp_ssv_add_site_specific_input_field('site-specific-base-fields-placeholder', i, '', '', '');
            document.getElementById(i + '_title').focus();
            i++;
        }
        <?php foreach($baseFields as $baseField): ?>
        mp_ssv_add_site_specific_input_field('site-specific-base-fields-placeholder', <?= $baseField->bf_id ?>, '<?= $baseField->bf_title ?>', '<?= $baseField->bf_name ?>', '<?= $baseField->bf_inputType ?>', '<?= $baseField->bf_options ?>', '<?= $baseField->bf_value ?>');
        <?php endforeach; ?>
    </script>
    <?= SSV_General::getFormSecurityFields(SSV_General::OPTIONS_ADMIN_REFERER, true, false); ?>
</form>
