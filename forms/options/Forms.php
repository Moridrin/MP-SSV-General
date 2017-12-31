<?php

namespace mp_ssv_forms\options;

use mp_ssv_forms\models\SSV_Forms;
use mp_ssv_general\base\BaseFunctions;
use wpdb;

if (!defined('ABSPATH')) {
    exit;
}

require_once 'templates/base-form-fields-table.php';
require_once 'templates/forms-table.php';
require_once 'templates/form-editor.php';

abstract class Forms
{

    public static function setupNetworkMenu()
    {
        add_menu_page('SSV Forms', 'SSV Forms', 'edit_posts', 'ssv_forms', [self::class, 'showSharedBaseFieldsPage']);
    }

    public static function showSharedBaseFieldsPage()
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
                    echo '<div class="notice error">Something unexpected happened. Please try again.</div>';
                }
            }
            /** @var wpdb $wpdb */
            global $wpdb;
            $order      = BaseFunctions::sanitize(isset($_GET['order']) ? $_GET['order'] : 'asc', 'text');
            $orderBy    = BaseFunctions::sanitize(isset($_GET['orderby']) ? $_GET['orderby'] : 'bf_title', 'text');
            $baseTable  = SSV_Forms::SHARED_BASE_FIELDS_TABLE;
            $baseFields = $wpdb->get_results("SELECT * FROM $baseTable ORDER BY $orderBy $order");
            $addNew     = '<a href="javascript:void(0)" class="page-title-action" onclick="mp_ssv_add_new_base_input_field()">Add New</a>';
            ?>
            <h1 class="wp-heading-inline"><span>Shared Form Fields</span><?= current_user_can('manage_shared_base_fields') ? $addNew : '' ?></h1>
            <p>These fields will be available for all sites.</p>
            <?php
            self::showFieldsManager($baseFields, $order, $orderBy, current_user_can('manage_shared_base_fields'), ['Role Checkbox', 'Role Select']);
            ?>
        </div>
        <?php
    }

    public static function setupSiteSpecificMenu()
    {
        add_menu_page('SSV Forms', 'SSV Forms', 'ssv_not_allowed', 'ssv_forms');
        add_submenu_page('ssv_forms', 'All Forms', 'All Forms', 'edit_posts', 'ssv_forms', [self::class, 'showFormsPage']);
        add_submenu_page('ssv_forms', 'Add New', 'Add New', 'edit_posts', 'ssv_forms_add_new_form', [self::class, 'showNewFormPage']);
        add_submenu_page('ssv_forms', 'Manage Fields', 'Manage Fields', 'edit_posts', 'ssv_forms_base_fields_manager', [self::class, 'showSiteBaseFieldsPage']);
    }

    public static function showFormsPage()
    {
        ?>
        <div class="wrap">
            <?php
            if (BaseFunctions::isValidPOST(SSV_Forms::OPTIONS_ADMIN_REFERER)) {
                if ($_POST['action'] === 'delete-selected' && !isset($_POST['_inline_edit'])) {
                    mp_ssv_general_forms_delete_site_specific_base_fields(false);
                } elseif ($_POST['action'] === '-1' && isset($_POST['_inline_edit'])) {
                    $value = isset($_POST['value']) ? $_POST['value'] : null;
                    if (is_array($value)) {
                        $value = implode(';', $value);
                    }
                    $_POST['values'] = [
                        'bf_id'        => $_POST['_inline_edit'],
                        'bf_name'      => $_POST['name'],
                        'bf_title'     => $_POST['title'],
                        'bf_inputType' => $_POST['inputType'],
                        'bf_value'     => $value,
                    ];
                    mp_ssv_general_forms_save_site_specific_base_field(false);
                } else {
                    echo '<div class="notice error"><p>Something unexpected happened. Please try again.</p></div>';
                }
            }
            /** @var wpdb $wpdb */
            global $wpdb;
            $order   = BaseFunctions::sanitize(isset($_GET['order']) ? $_GET['order'] : 'asc', 'text');
            $orderBy = BaseFunctions::sanitize(isset($_GET['orderby']) ? $_GET['orderby'] : 'bf_title', 'text');
            $table   = SSV_Forms::SITE_SPECIFIC_FORMS_TABLE;
            $forms   = $wpdb->get_results("SELECT * FROM $table ORDER BY $orderBy $order");
            $addNew  = '<a href="?page=ssv_forms_add_new_form" class="page-title-action">Add New</a>';
            ?>
            <h1 class="wp-heading-inline"><span>Site Specific Forms</span><?= current_user_can('manage_site_specific_forms') ? $addNew : '' ?></h1>
            <p>These forms will only be available for <?= get_bloginfo() ?>.</p>
            <?php
            self::showFormsManager($forms, $order, $orderBy, current_user_can('manage_site_specific_forms'));
            ?>
        </div>
        <?php
    }

    public static function showNewFormPage()
    {
        /** @var wpdb $wpdb */
        global $wpdb;
        $sharedBaseFieldsTable = SSV_Forms::SHARED_BASE_FIELDS_TABLE;
        $siteSpecificBaseFieldsTable = SSV_Forms::SITE_SPECIFIC_BASE_FIELDS_TABLE;
        $sharedBaseFields = $wpdb->get_results("SELECT * FROM (SELECT * FROM $sharedBaseFieldsTable UNION SELECT * FROM $siteSpecificBaseFieldsTable) combined ORDER BY bf_title");
        show_form_editor($sharedBaseFields);
    }

    public static function showSiteBaseFieldsPage()
    {
        $activeTab = "shared";
        if (isset($_GET['tab'])) {
            $activeTab = $_GET['tab'];
        }
        $function = 'showSiteBaseFields' . ucfirst($activeTab) . 'Tab';
        ?>
        <div class="wrap">
            <h2 class="nav-tab-wrapper">
                <a href="?page=<?= esc_html($_GET['page']) ?>&tab=shared" class="nav-tab <?= $activeTab === 'shared' ? 'nav-tab-active' : '' ?>">Shared</a>
                <a href="?page=<?= esc_html($_GET['page']) ?>&tab=siteSpecific" class="nav-tab <?= $activeTab === 'siteSpecific' ? 'nav-tab-active' : '' ?>">Site Specific</a>
                <a href="http://bosso.nl/plugins/ssv-file-manager/" target="_blank" class="nav-tab">
                    Help <!--suppress HtmlUnknownTarget -->
                    <img src="<?= esc_url(BaseFunctions::URL) ?>/images/link-new-tab-small.png" width="14" style="vertical-align:middle">
                </a>
            </h2>
            <?php
            if (method_exists(Forms::class, $function)) {
                self::$function();
            } else {
                ?>
                <div class="notice error"><p>Unknown Tab</p></div><?php
            }
            ?>
        </div>
        <?php
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */
    private static function showSiteBaseFieldsSharedTab()
    {
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
                echo '<div class="notice error">Something unexpected happened. Please try again.</div>';
            }
        }
        /** @var wpdb $wpdb */
        global $wpdb;
        $order      = BaseFunctions::sanitize(isset($_GET['order']) ? $_GET['order'] : 'asc', 'text');
        $orderBy    = BaseFunctions::sanitize(isset($_GET['orderby']) ? $_GET['orderby'] : 'bf_title', 'text');
        $baseTable  = SSV_Forms::SHARED_BASE_FIELDS_TABLE;
        $baseFields = $wpdb->get_results("SELECT * FROM $baseTable ORDER BY $orderBy $order");
        $addNew     = '<a href="javascript:void(0)" class="page-title-action" onclick="mp_ssv_add_new_base_input_field()">Add New</a>';
        ?>
        <h1 class="wp-heading-inline"><span>Shared Form Fields</span><?= current_user_can('manage_shared_base_fields') ? $addNew : '' ?></h1>
        <p>These fields will be available for all sites.</p>
        <?php
        self::showFieldsManager($baseFields, $order, $orderBy, current_user_can('manage_shared_base_fields'), ['Role Checkbox', 'Role Select']);
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */
    private static function showSiteBaseFieldsSiteSpecificTab()
    {
        if (BaseFunctions::isValidPOST(SSV_Forms::OPTIONS_ADMIN_REFERER)) {
            if ($_POST['action'] === 'delete-selected' && !isset($_POST['_inline_edit'])) {
                mp_ssv_general_forms_delete_site_specific_base_fields(false);
            } elseif ($_POST['action'] === '-1' && isset($_POST['_inline_edit'])) {
                $value = isset($_POST['value']) ? $_POST['value'] : null;
                if (is_array($value)) {
                    $value = implode(';', $value);
                }
                $_POST['values'] = [
                    'bf_id'        => $_POST['_inline_edit'],
                    'bf_name'      => $_POST['name'],
                    'bf_title'     => $_POST['title'],
                    'bf_inputType' => $_POST['inputType'],
                    'bf_value'     => $value,
                ];
                mp_ssv_general_forms_save_site_specific_base_field(false);
            } else {
                echo '<div class="notice error"><p>Something unexpected happened. Please try again.</p></div>';
            }
        }
        /** @var wpdb $wpdb */
        global $wpdb;
        $order      = BaseFunctions::sanitize(isset($_GET['order']) ? $_GET['order'] : 'asc', 'text');
        $orderBy    = BaseFunctions::sanitize(isset($_GET['orderby']) ? $_GET['orderby'] : 'bf_title', 'text');
        $baseTable  = SSV_Forms::SITE_SPECIFIC_BASE_FIELDS_TABLE;
        $baseFields = $wpdb->get_results("SELECT * FROM $baseTable ORDER BY $orderBy $order");
        $addNew     = '<a href="javascript:void(0)" class="page-title-action" onclick="mp_ssv_add_new_base_input_field()">Add New</a>';
        ?>
        <h1 class="wp-heading-inline"><span>Site Specific Form Fields</span><?= current_user_can('manage_site_specific_base_fields') ? $addNew : '' ?></h1>
        <p>These fields will only be available for <?= get_bloginfo() ?>.</p>
        <?php
        self::showFieldsManager($baseFields, $order, $orderBy, current_user_can('manage_site_specific_base_fields'));
    }

    private static function showFieldsManager($baseFields, $order, $orderBy, $hasManageRight, $excludedRoles = [])
    {
        ?>
        <form method="post" action="#">
            <?php
            echo BaseFunctions::getInputTypeDataList($excludedRoles);
            show_base_form_fields_table($baseFields, $order, $orderBy, $hasManageRight);
            if ($hasManageRight) {
                ?>
                <script>
                    let i = <?= count($baseFields) > 0 ? max(array_column($baseFields, 'bf_id')) + 1 : 1 ?>;

                    function mp_ssv_add_new_base_input_field() {
                        event.preventDefault();
                        mp_ssv_add_base_input_field('the-list', i, '', '', '');
                        document.getElementById(i + '_title').focus();
                        i++;
                    }
                </script>
                <?= BaseFunctions::getFormSecurityFields(SSV_Forms::OPTIONS_ADMIN_REFERER, false, false) ?>
                <?php
            }
            ?>
        </form>
        <?php
    }

    private static function showFormsManager($forms, $order, $orderBy, $hasManageRight)
    {
        ?>
        <form method="post" action="#">
            <?php
            show_forms_table($forms, $order, $orderBy, $hasManageRight);
            if ($hasManageRight) {
                ?>
                <script>
                    let i = <?= count($forms) > 0 ? max(array_column($forms, 'bf_id')) + 1 : 1 ?>;

                    function mp_ssv_add_new_form() {
                        event.preventDefault();
                        mp_ssv_add_form('the-list', i, '', '', '');
                        document.getElementById(i + '_title').focus();
                        i++;
                    }
                </script>
                <?= BaseFunctions::getFormSecurityFields(SSV_Forms::OPTIONS_ADMIN_REFERER, false, false) ?>
                <?php
            }
            ?>
        </form>
        <?php
    }

}

add_action('network_admin_menu', [Forms::class, 'setupNetworkMenu'], 9);
add_action('admin_menu', [Forms::class, 'setupSiteSpecificMenu'], 9);
