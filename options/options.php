<?php
use mp_ssv_general\custom_fields\Field;
use mp_ssv_general\custom_fields\InputField;
use mp_ssv_general\SSV_General;
use mp_ssv_general\User;

if (!defined('ABSPATH')) {
    exit;
}

#region Menu Items
function ssv_add_ssv_menu()
{
    add_menu_page('SSV Options', 'SSV Options', 'edit_posts', 'ssv_settings', 'ssv_settings_page');
    add_submenu_page('ssv_settings', 'General', 'General', 'edit_posts', 'ssv_settings');
}

add_action('admin_menu', 'ssv_add_ssv_menu', 9);
add_action('network_admin_menu', 'ssv_add_ssv_menu', 9);
#endregion

#region Page Content
function ssv_settings_page()
{
    $columns = array(
        'default',
        'placeholder',
        'class',
        'style',
        'override_right',
    );

    if (SSV_General::isValidPOST(SSV_General::OPTIONS_ADMIN_REFERER)) {
        if (isset($_POST['reset'])) {
            SSV_General::resetOptions();
        } else {
            $fieldIDs = SSV_General::sanitize($_POST['field_ids'], 'array');

            /** @var wpdb $wpdb */
            global $wpdb;
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
                $field = Field::fromJSON(json_encode($properties));
                $name = null;
                if ($field instanceof InputField) {
                    $oldName = $wpdb->get_row("SELECT `name` FROM $table WHERE ID = $fieldID")->name;
                    $name = $field->name;
                    if ($name != $oldName) {
                        $wpdb->update($wpdb->usermeta, array('meta_key' => $name), array('meta_key' => $oldName));
                    }
                }
                if (current_user_can('edit_custom_fields')) {
                    $wpdb->replace(
                        $table,
                        array(
                            'ID'    => $field->id,
                            'name'  => $name,
                            'title' => $field->title,
                            'json'  => $field->toJSON(true),
                        )
                    );
                } elseif (current_user_can('add_custom_fields')) {
                    $wpdb->insert(
                        $table,
                        array(
                            'ID'    => $field->id,
                            'name'  => $name,
                            'title' => $field->title,
                            'json'  => $field->toJSON(true),
                        )
                    );
                }
            }
            $customFieldFields = isset($_POST['columns']) ? SSV_General::sanitize($_POST['columns'], $columns) : array();
            User::getCurrent()->updateMeta(SSV_General::USER_OPTION_CUSTOM_FIELD_FIELDS, json_encode($customFieldFields), false);
        }
    }
    global $wpdb;
    $table          = SSV_General::CUSTOM_FIELDS_TABLE;
    $databaseFields = $wpdb->get_results("SELECT * FROM $table ORDER BY ID ASC");
    $fields         = array();
    foreach ($databaseFields as $field) {
        $values        = json_decode($field->json);
        $values->id    = $field->ID;
        $values->name  = $field->name;
        $values->title = $field->title;
        $fields[]      = Field::fromJSON(json_encode($values));
    }
    ?>
    <div class="wrap">
        <h1>General Options</h1>
    </div>
    <form method="post" action="#">
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="columns">Columns</label>
                </th>
                <td>
                    <?php
                    $selected = json_decode(User::getCurrent()->getMeta(SSV_General::USER_OPTION_CUSTOM_FIELD_FIELDS, json_encode(array('display', 'default', 'placeholder'))));
                    $selected = $selected ?: array();
                    ?>
                    <select id="columns" size="<?= count($columns) ?>" name="columns[]" multiple onchange="columnsChanged()">
                        <?php
                        foreach ($columns as $field) {
                            ?>
                            <option value="<?= $field ?>" <?= in_array($field, $selected) ? 'selected' : '' ?>>
                                <?= $field ?>
                            </option>
                            <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row" style="padding: 0;">
                    <label>Custom Fields</label>
                </th>
            </tr>
            <tr>
                <td colspan="2" style="padding: 0;">
                    <div style="overflow-x: auto;">
                        <table id="custom-fields-placeholder"></table>
                        <button type="button" onclick="mp_ssv_add_new_custom_field()" style="margin-top: 10px;">Add Field</button>
                    </div>
                    <script>
                        var i = <?= esc_html(Field::getMaxID($fields) + 1) ?>;
                        function mp_ssv_add_new_custom_field() {
                            mp_ssv_add_custom_input_field('custom-fields-placeholder', i, 'text', {"override_right": ""}, false);
                            i++;
                        }
                        <?php foreach($fields as $field): ?>
                        mp_ssv_add_custom_input_field('custom-fields-placeholder', <?= esc_html($field->id) ?>, '<?= isset($field->inputType) ? esc_html($field->inputType) : '' ?>', <?= $field->toJSON() ?>, false);
                        <?php endforeach; ?>
                    </script>
                </td>
            </tr>
        </table>
        <?= SSV_General::getFormSecurityFields(SSV_General::OPTIONS_ADMIN_REFERER, true, 'Reset Preference'); ?>
    </form>
    <?php
}
#endregion
