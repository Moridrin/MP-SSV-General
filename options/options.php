<?php
use mp_ssv_general\SSV_General;

if (!defined('ABSPATH')) {
    exit;
}

#region Menu Items
function ssv_add_ssv_menu()
{
    add_menu_page('SSV Options', 'SSV Options', 'manage_options', 'ssv_settings', 'ssv_settings_page');
    add_submenu_page('ssv_settings', 'General', 'General', 'manage_options', 'ssv_settings');
}

add_action('admin_menu', 'ssv_add_ssv_menu', 9);
#endregion

#region Page Content
function ssv_settings_page()
{
    if (SSV_General::isValidPOST(SSV_General::OPTIONS_ADMIN_REFERER)) {
        if (isset($_POST['reset'])) {
            SSV_General::resetOptions();
        } else {
            update_option(SSV_General::OPTION_BOARD_ROLE, SSV_General::sanitize($_POST['board_role']));
            $customFieldFields = isset($_POST['custom_field_fields']) ? SSV_General::sanitize($_POST['custom_field_fields']) : null;
            update_option(SSV_General::OPTION_CUSTOM_FIELD_FIELDS, json_encode($customFieldFields));
        }
    }
    ?>
    <div class="wrap">
        <h1>SSV Plugins</h1>
    </div>
    <?php do_action(SSV_General::HOOK_GENERAL_OPTIONS_PAGE_CONTENT); ?>
    <div class="wrap">
        <h1>General Options</h1>
    </div>
    <form method="post" action="#">
        <table class="form-table">

            <tr valign="top">
                <th scope="row">
                    <label for="board_role">Board Role</label>
                </th>
                <td>
                    <select id="board_role" name="board_role">
                        <?php wp_dropdown_roles(get_option(SSV_General::OPTION_BOARD_ROLE)); ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="custom_field_fields">Custom Field Fields</label>
                </th>
                <td>
                    <?php
                    $selected = json_decode(get_option(SSV_General::OPTION_CUSTOM_FIELD_FIELDS));
                    $selected = $selected ?: array();
                    $fields   = array(
                        'display',
                        'default',
                        'placeholder',
                        'class',
                        'style',
                    );
                    ?>
                    <select id="custom_field_fields" size="<?= count($fields) ?>" name="custom_field_fields[]" multiple>
                        <?php
                        foreach ($fields as $field) {
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
        </table>
        <?= SSV_General::getFormSecurityFields(SSV_General::OPTIONS_ADMIN_REFERER); ?>
    </form>
    <?php
}
#endregion
