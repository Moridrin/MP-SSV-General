<?php
use mp_ssv_general\custom_fields\Field;
use mp_ssv_general\custom_fields\InputField;
use mp_ssv_general\SSV_General;

if (!defined('ABSPATH')) {
    exit;
}

/** @var wpdb $wpdb */
global $wpdb;

if (SSV_General::isValidPOST(SSV_General::OPTIONS_ADMIN_REFERER)) {
    if (isset($_POST['reset'])) {
        SSV_General::resetOptions();
    } else {
        $fieldIDs = SSV_General::sanitize($_POST['field_ids'], 'int');
        $fieldIDs = is_array($fieldIDs) ? $fieldIDs : array();

        $table = SSV_General::CUSTOM_FIELDS_TABLE;
        if (current_user_can('remove_custom_fields')) {
            if (!empty($fieldIDs)) {
                $databaseFieldIDs = implode(", ", $fieldIDs);
                $wpdb->query("DELETE FROM $table WHERE ID NOT IN ($databaseFieldIDs)");
            } else {
                $wpdb->query("DELETE FROM $table WHERE 1");
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
            $oldName = $wpdb->get_row("SELECT `name` FROM $table WHERE ID = $fieldID")->name;
            $name    = $field->name;
            if ($oldName !== null && $name != $oldName) {
                $wpdb->update($wpdb->usermeta, array('meta_key' => $name), array('meta_key' => $oldName));
            }
            if (current_user_can('edit_custom_fields')) {
                $wpdb->replace(
                    $table,
                    array(
                        'ID'    => $fieldID,
                        'name'  => $name,
                        'title' => $field->title,
                        'json'  => $field->toJSON(),
                    )
                );
            } elseif (current_user_can('add_custom_fields')) {
                $wpdb->insert(
                    $table,
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
$table      = SSV_General::CUSTOM_FIELDS_TABLE;
$baseFields = $wpdb->get_results("SELECT * FROM $table WHERE `shared` = 0");
$baseFields = array_combine(array_column($baseFields, 'ID'), $baseFields);
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
    <table class="form-table">
        <tr>
            <td colspan="2" style="padding: 0;">
                <div style="overflow-x: auto;">
                    <table id="site-specific-custom-fields-placeholder"></table>
                    <button type="button" onclick="mp_ssv_add_new_custom_field()" style="margin-top: 10px;">Add Field</button>
                </div>
                <!--suppress JSUnusedLocalSymbols -->
                <script>
                    //                        var i = <?//= esc_html(Field::getMaxID($baseFields) + 1) ?>//;
                    var i = <?= max(array_keys($baseFields)) + 1 ?>;
                    function mp_ssv_add_new_custom_field() {
                        mp_ssv_add_custom_input_field('site-specific-custom-fields-placeholder', i, 'text', {"override_right": ""}, false);
                        i++;
                    }
                    <?php foreach($baseFields as $fieldID => $baseField): ?>
                    <?php $field = Field::fromJSON($baseField->json); ?>
                    mp_ssv_add_custom_input_field('site-specific-custom-fields-placeholder', <?= $fieldID ?>, '<?= isset($field->inputType) ? esc_html($field->inputType) : '' ?>', <?= $field->toJSON() ?>, false);
                    <?php endforeach; ?>
                </script>
            </td>
        </tr>
    </table>
    <?= SSV_General::getFormSecurityFields(SSV_General::OPTIONS_ADMIN_REFERER, true, false); ?>
</form>
