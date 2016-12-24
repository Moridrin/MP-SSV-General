<?php
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
        update_option(SSV_General::OPTION_BOARD_ROLE, $_POST['board_role']);
    }
    ?>
    <div class="wrap">
        <h1>SSV General Options</h1>
    </div>
    <?php do_action(SSV_General::HOOK_GENERAL_OPTIONS_PAGE_CONTENT); ?>

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
        </table>
        <?php wp_nonce_field(SSV_General::OPTIONS_ADMIN_REFERER); ?>
        <?php submit_button(); ?>
    </form>
    <?php
}
#endregion
