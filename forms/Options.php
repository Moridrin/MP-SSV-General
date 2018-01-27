<?php

namespace mp_ssv_general\forms;

use mp_ssv_general\base\BaseFunctions;
use mp_ssv_general\base\SSV_Global;
use mp_ssv_general\forms\models\Forms;
use wpdb;

if (!defined('ABSPATH')) {
    exit;
}

/** @noinspection PhpIncludeInspection */
require_once SSV_Forms::PATH . 'templates/base-form-fields-table.php';
/** @noinspection PhpIncludeInspection */
require_once SSV_Forms::PATH . 'templates/forms-table.php';
/** @noinspection PhpIncludeInspection */
require_once SSV_Forms::PATH . 'templates/form-editor.php';

abstract class Options
{
    public static function setupNetworkMenu()
    {
        add_menu_page('SSV Forms', 'SSV Forms', 'edit_posts', 'ssv_forms', [self::class, 'showSharedBaseFieldsPage'], 'dashicons-feedback');
    }

    public static function setupSiteSpecificMenu()
    {
        add_menu_page('SSV Forms', 'SSV Forms', 'ssv_not_allowed', 'ssv_forms', '', 'dashicons-feedback');
        add_submenu_page('ssv_forms', 'All Forms', 'All Forms', 'edit_posts', 'ssv_forms', [self::class, 'showFormsPage']);
        add_submenu_page('ssv_forms', 'Add New', 'Add New', 'edit_posts', 'ssv_forms_add_new_form', [self::class, 'showEditFormPage']);
        add_submenu_page('ssv_forms', 'Manage Fields', 'Manage Fields', 'edit_posts', 'ssv_forms_fields_manager', [self::class, 'showSiteBaseFieldsPage']);
    }

    public static function showSharedBaseFieldsPage()
    {
        ?>
        <div class="wrap">
            <?php
            if (BaseFunctions::isValidPOST(SSV_Forms::ALL_FORMS_ADMIN_REFERER)) {
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
            $wpdb = SSV_Global::getDatabase();
            $order      = isset($_GET['order']) ? BaseFunctions::sanitize($_GET['order'], 'text') : 'asc';
            $orderBy    = isset($_GET['orderby']) ? BaseFunctions::sanitize($_GET['orderby'], 'text') : 'bf_title';
            $baseTable  = SSV_Forms::SHARED_BASE_FIELDS_TABLE;
            $baseFields = $wpdb->get_results("SELECT * FROM $baseTable ORDER BY $orderBy $order");
            $addNew     = '<a href="javascript:void(0)" class="page-title-action" onclick="fieldsManager.addNew(\'the-list\', \'\')">Add New</a>';
            ?>
            <h1 class="wp-heading-inline"><span>Shared Form Fields</span><?= current_user_can('manage_shared_base_fields') ? $addNew : '' ?></h1>
            <p>These fields will be available for all sites.</p>
            <?php
            self::showFieldsManager($baseFields, $order, $orderBy, current_user_can('manage_shared_base_fields'));
            ?>
        </div>
        <?php
    }

    public static function showFormsPage()
    {
        if (isset($_GET['action']) && $_GET['action'] === 'edit') {
            self::showEditFormPage();
        } else {
            ?>
            <div class="wrap">
                <?php
                if (BaseFunctions::isValidPOST(SSV_Forms::EDIT_FORM_ADMIN_REFERER)) {
                    $wpdb = SSV_Global::getDatabase();
                    $wpdb->replace(
                        SSV_Forms::SITE_SPECIFIC_FORMS_TABLE,
                        [
                            'f_id'     => $_POST['form_id'],
                            'f_tag'    => $_POST['form_tag'],
                            'f_title'  => $_POST['form_title'],
                            'f_fields' => json_encode(isset($_POST['form_fields']) ? $_POST['form_fields'] : []),
                        ]
                    );
                    if ($wpdb->last_error) {
                        $_SESSION['SSV']['errors'][] = $wpdb->last_error;
                        SSV_Global::showErrors();
                    }
                } elseif (BaseFunctions::isValidPOST(SSV_Forms::ALL_FORMS_ADMIN_REFERER)) {
                    if ($_POST['action'] === 'delete-selected') {
                        mp_ssv_general_forms_delete_shared_forms(false);
                    } else {
                        echo '<div class="notice error"><p>Something unexpected happened. Please try again.</p></div>';
                    }
                }
                $wpdb = SSV_Global::getDatabase();
                $order   = BaseFunctions::sanitize(isset($_GET['order']) ? $_GET['order'] : 'asc', 'text');
                $orderBy = BaseFunctions::sanitize(isset($_GET['orderby']) ? $_GET['orderby'] : 'f_title', 'text');
                $table   = SSV_Forms::SITE_SPECIFIC_FORMS_TABLE;
                $forms   = $wpdb->get_results("SELECT * FROM $table ORDER BY $orderBy $order");
                $addNew  = '<a href="?page=ssv_forms_add_new_form" class="page-title-action">Add New</a>';
                ?>
                <h1 class="wp-heading-inline"><span>Site Specific Forms</span><?= current_user_can('manage_site_specific_forms') ? $addNew : '' ?></h1>
                <p>These forms will only be available for <?= get_bloginfo() ?>.</p>
                <form method="post" action="#">
                    <?php
                    show_forms_table($forms, $order, $orderBy, current_user_can('manage_site_specific_forms'));
                    if (current_user_can('manage_site_specific_forms')) {
                        echo BaseFunctions::getAdminFormSecurityFields(SSV_Forms::ALL_FORMS_ADMIN_REFERER, false, false);
                    }
                    ?>
                </form>
                <?php
                ?>
            </div>
            <?php
        }
    }

    public static function showEditFormPage()
    {
        $wpdb = SSV_Global::getDatabase();
        $sharedBaseFieldsTable       = SSV_Forms::SHARED_BASE_FIELDS_TABLE;
        $siteSpecificBaseFieldsTable = SSV_Forms::SITE_SPECIFIC_BASE_FIELDS_TABLE;
        $formsTable                  = SSV_Forms::SITE_SPECIFIC_FORMS_TABLE;
        $baseSharedFields            = $wpdb->get_results("SELECT * FROM $sharedBaseFieldsTable ORDER BY bf_title");
        $baseSiteSpecificFields      = $wpdb->get_results("SELECT * FROM $siteSpecificBaseFieldsTable ORDER BY bf_title");
        if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
            $id         = $_GET['id'];
            $formName   = $wpdb->get_var("SELECT f_title FROM $formsTable WHERE f_id = $id");
            $fieldNames = json_decode($wpdb->get_var("SELECT f_fields FROM $formsTable WHERE f_id = $id"));
            $formFields = Forms::getFormFields($fieldNames);
        } else {
            $id         = $wpdb->get_var("SELECT MAX(f_id) AS maxId FROM $formsTable") + 1;
            $formName   = '';
            $formFields = [];
        }
        show_form_editor($id, $formName, $baseSharedFields, $baseSiteSpecificFields, $formFields);
    }

    public static function showSiteBaseFieldsPage()
    {
        $activeTab = "shared";
        if (isset($_GET['tab'])) {
            $activeTab = $_GET['tab'];
        }
        ?>
        <div class="wrap">
            <h2 class="nav-tab-wrapper">
                <a href="?page=<?= esc_html($_GET['page']) ?>&tab=shared" class="nav-tab <?= $activeTab === 'shared' ? 'nav-tab-active' : '' ?>">Shared</a>
                <a href="?page=<?= esc_html($_GET['page']) ?>&tab=siteSpecific" class="nav-tab <?= $activeTab === 'siteSpecific' ? 'nav-tab-active' : '' ?>">Site Specific</a>
                <a href="http://bosso.nl/plugins/ssv-file-manager/" target="_blank" class="nav-tab">
                    Help <!--suppress HtmlUnknownTarget -->
                    <img src="<?= esc_url(SSV_Global::URL) ?>/images/link-new-tab-small.png" width="14" style="vertical-align:middle">
                </a>
            </h2>
            <?php
            $function = 'showSiteBaseFields' . ucfirst($activeTab) . 'Tab';
            if (method_exists(Options::class, $function)) {
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
        if (BaseFunctions::isValidPOST(SSV_Forms::ALL_FORMS_ADMIN_REFERER)) {
            if ($_POST['action'] === 'delete-selected' && !isset($_POST['_inline_edit'])) {
                mp_ssv_general_forms_delete_shared_base_fields(false);
            } elseif ($_POST['action'] === '-1' && isset($_POST['_inline_edit'])) {
                $_POST['values'] = [
                    'bf_id'        => $_POST['_inline_edit'],
                    'bf_name'      => $_POST['name'],
                    'bf_title'     => $_POST['title'],
                    'bf_inputType' => $_POST['inputType'],
                    'bf_value'     => isset($_POST['value']) ? $_POST['value'] : null,
                    'bf_options'   => isset($_POST['options']) ? $_POST['options'] : null,
                ];
                mp_ssv_general_forms_save_shared_base_field(false);
            } else {
                echo '<div class="notice error">Something unexpected happened. Please try again.</div>';
            }
        }
        $wpdb = SSV_Global::getDatabase();
        $order      = BaseFunctions::sanitize(isset($_GET['order']) ? $_GET['order'] : 'asc', 'text');
        $orderBy    = BaseFunctions::sanitize(isset($_GET['orderby']) ? $_GET['orderby'] : 'name', 'text');
        $baseTable  = SSV_Forms::SHARED_BASE_FIELDS_TABLE;
        $baseFields = $wpdb->get_results("SELECT *, JSON_EXTRACT(bf_properties, '$.$orderBy') AS $orderBy FROM $baseTable ORDER BY $orderBy $order");
        if ($wpdb->last_error) {
            $_SESSION['SSV']['errors'][] = $wpdb->last_error;
        }
        $addNew     = '<a href="javascript:void(0)" class="page-title-action" onclick="fieldsManager.addNew(\'the-list\', \'\')">Add New</a>';
        ?>
        <h1 class="wp-heading-inline"><span>Shared Form Fields</span><?= current_user_can('manage_shared_base_fields') ? $addNew : '' ?></h1>
        <p>These fields will be available for all sites.</p>
        <?php
        self::showFieldsManager($baseFields, $order, $orderBy, current_user_can('manage_shared_base_fields'));
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */
    private static function showSiteBaseFieldsSiteSpecificTab()
    {
        if (BaseFunctions::isValidPOST(SSV_Forms::ALL_FORMS_ADMIN_REFERER)) {
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
        $wpdb = SSV_Global::getDatabase();
        $order      = BaseFunctions::sanitize(isset($_GET['order']) ? $_GET['order'] : 'asc', 'text');
        $orderBy    = BaseFunctions::sanitize(isset($_GET['orderby']) ? $_GET['orderby'] : 'bf_title', 'text');
        $baseTable  = SSV_Forms::SITE_SPECIFIC_BASE_FIELDS_TABLE;
        $baseFields = $wpdb->get_results("SELECT * FROM $baseTable ORDER BY $orderBy $order");
        $addNew     = '<a href="javascript:void(0)" class="page-title-action" onclick="fieldsManager.addNew(\'the-list\', \'\')">Add New</a>';
        ?>
        <h1 class="wp-heading-inline"><span>Site Specific Form Fields</span><?= current_user_can('manage_site_specific_base_fields') ? $addNew : '' ?></h1>
        <p>These fields will only be available for <?= get_bloginfo() ?>.</p>
        <?php
        self::showFieldsManager($baseFields, $order, $orderBy, current_user_can('manage_site_specific_base_fields'));
    }

    private static function showFieldsManager($fields, $order, $orderBy, $hasManageRight)
    {
        ?>
        <form method="post" action="#">
            <?php
            show_base_form_fields_table($fields, $order, $orderBy, $hasManageRight);
            if ($hasManageRight) {
                echo BaseFunctions::getAdminFormSecurityFields(SSV_Forms::ALL_FORMS_ADMIN_REFERER, false, false);
            }
            ?>
        </form>
        <?php
    }
}

add_action('network_admin_menu', [Options::class, 'setupNetworkMenu']);
add_action('admin_menu', [Options::class, 'setupSiteSpecificMenu']);
