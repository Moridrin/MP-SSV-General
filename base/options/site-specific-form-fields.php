<?php

use mp_ssv_general\custom_fields\Field;
use mp_ssv_general\custom_fields\InputField;
use mp_ssv_general\SSV_Base;

if (!defined('ABSPATH')) {
    exit;
}

/** @var wpdb $wpdb */
global $wpdb;
$sharedBaseTable = SSV_Base::SHARED_BASE_FIELDS_TABLE;
$siteSpecificBaseTable = SSV_Base::SITE_SPECIFIC_BASE_FIELDS_TABLE;
$customizedTable = SSV_Base::CUSTOMIZED_FIELDS_TABLE;
if (SSV_Base::isValidPOST(SSV_Base::OPTIONS_ADMIN_REFERER)) {
    if (isset($_POST['reset'])) {
        SSV_Base::resetOptions();
    } else {
        $fieldIds = SSV_Base::sanitize($_POST['field_ids'], 'int');
        $fieldIds = is_array($fieldIds) ? $fieldIds : [];

        if (current_user_can('remove_custom_fields')) {
            if (!empty($fieldIds)) {
                $databaseFieldIds = implode(", ", $fieldIds);
                $wpdb->query("DELETE FROM $siteSpecificBaseTable WHERE bf_id NOT IN ($databaseFieldIds)");
            } else {
                $wpdb->query("TRUNCATE $siteSpecificBaseTable");
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
                    if (is_array($property)) {
                        $property = json_encode($property);
                    }
                    $properties[str_replace('custom_field_' . $fieldId . '_', 'bf_', $key)] = $property;
                    unset($properties[$key]);
                }
            }
            if (current_user_can('edit_custom_fields')) {
                $currentField = $wpdb->get_row("SELECT bf_name FROM $siteSpecificBaseTable WHERE bf_id = $fieldId");
                $wpdb->replace($siteSpecificBaseTable, $properties);
                if ($currentField !== null) {
                    if ($properties['bf_name'] != $currentField->bf_name && $currentField->bf_name !== null) {
                        $wpdb->update($wpdb->usermeta, ['meta_key' => $properties['bf_name']], ['meta_key' => $currentField->bf_name]);
                        $wpdb->update($siteSpecificBaseTable, ['name' => $properties['bf_name']], ['name' => $currentField->bf_name]);
                    }
                }
            } elseif (current_user_can('add_custom_fields')) {
                $wpdb->insert($siteSpecificBaseTable, $properties);
            }
        }
    }
}
$sharedBaseFields = $wpdb->get_results("SELECT * FROM $sharedBaseTable");
?>
<h1>Shared Form Fields</h1>
<table class="form-table">
    <?php
    foreach ($sharedBaseFields as $baseField) {
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
$siteSpecificBaseFields = $wpdb->get_results("SELECT * FROM $siteSpecificBaseTable");
?>
<h1>Site Specific Form Fields</h1>
<?php
echo SSV_Base::getInputTypeDataList();
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
        <table id="site-specific-base-fields-placeholder" class="form-table"></table>
        <button type="button" onclick="mp_ssv_add_new_base_input_field()" style="margin-top: 10px;">Add Field</button>
    </div>
    <script>
        var i = <?= count($siteSpecificBaseFields) > 0 ? max(array_column($siteSpecificBaseFields, 'bf_id')) + 1 : 1 ?>;

        function mp_ssv_add_new_base_input_field() {
            mp_ssv_add_site_specific_input_field('site-specific-base-fields-placeholder', i, '', '', '');
            document.getElementById(i + '_title').focus();
            i++;
        }
        <?php foreach($siteSpecificBaseFields as $baseField): ?>
        mp_ssv_add_site_specific_input_field('site-specific-base-fields-placeholder', <?= $baseField->bf_id ?>, '<?= $baseField->bf_title ?>', '<?= $baseField->bf_name ?>', '<?= $baseField->bf_inputType ?>', '<?= $baseField->bf_options ?>', '<?= $baseField->bf_value ?>');
        <?php endforeach; ?>
    </script>
    <?= SSV_Base::getFormSecurityFields(SSV_Base::OPTIONS_ADMIN_REFERER, true, false); ?>
</form>
