<?php

namespace mp_ssv_forms\options;

use mp_ssv_forms\models\SSV_Forms;
use mp_ssv_general\base\BaseFunctions;

if (!defined('ABSPATH')) {
    exit;
}

abstract class Forms
{

    public static function setupNetworkMenu()
    {
        add_menu_page('SSV Forms', 'SSV Forms', 'edit_posts', 'ssv_forms', [self::class, 'showBaseFieldsManager']);
    }

    public static function showBaseFieldsManager()
    {
        ?>
        <div class="wrap">
            <?php
            if (BaseFunctions::isValidPOST(SSV_Forms::OPTIONS_ADMIN_REFERER)) {
                if ($_POST['action'] === 'delete-selected' && !isset($_POST['_inline_edit'])) {
                    mp_ssv_general_forms_delete_shared_base_fields(false);
                } elseif ($_POST['action'] === '-1' && isset($_POST['_inline_edit'])) {
                    $_POST['values'] = [
                        'bf_id'        => $_POST['_inline_edit'],
                        'bf_name'      => $_POST['name'],
                        'bf_title'     => $_POST['title'],
                        'bf_inputType' => $_POST['inputType'],
                        'bf_value'     => isset($_POST['value']) ? $_POST['value'] : null,
                    ];
                    mp_ssv_general_forms_save_shared_base_field(false);
                } else {
                    echo '<div class="notification error">Something unexpected happened. Please try again.</div>';
                }
            }
            require_once 'templates/base-form-fields-table.php';
            ?>
        </div>
        <?php
    }

}

add_action('network_admin_menu', [Forms::class, 'setupNetworkMenu'], 9);
