<?php

namespace mp_ssv_general\forms;

use mp_ssv_general\base\BaseFunctions;
use mp_ssv_general\base\models\Model;
use mp_ssv_general\base\SSV_Global;
use mp_ssv_general\forms\models\Field;
use mp_ssv_general\forms\models\Form;
use mp_ssv_general\forms\models\FormField;
use mp_ssv_general\forms\models\Forms;
use mp_ssv_general\forms\models\SharedField;
use mp_ssv_general\forms\models\SiteSpecificField;

if (!defined('ABSPATH')) {
    exit;
}

abstract class Options
{
    public static function setupNetworkMenu()
    {
        add_menu_page('SSV Forms', 'SSV Forms', 'edit_posts', 'ssv_forms', [self::class, 'showSharedBaseFieldsPage'], 'dashicons-feedback');
    }

    public static function setupSiteSpecificMenu()
    {
        add_menu_page('SSV Forms', 'SSV Forms', 'edit_posts', 'ssv_forms', '', 'dashicons-feedback');
        add_submenu_page('ssv_forms', 'All Forms', 'All Forms', 'edit_posts', 'ssv_forms', [self::class, 'showFormsPage']);
        add_submenu_page('ssv_forms', 'Add New', 'Add New', 'edit_posts', 'ssv_forms_add_new_form', [self::class, 'showEditFormPage']);
        add_submenu_page('ssv_forms', 'Manage Fields', 'Manage Fields', 'edit_posts', 'ssv_forms_fields_manager', [self::class, 'showSiteBaseFieldsPage']);
    }

    public static function showSharedBaseFieldsPage()
    {
        $activeTab = $_GET['tab'] ?? 'shared';
        $blogs     = get_blogs_of_user(get_current_user_id());
        ?>
        <div class="wrap">
            <h2 class="nav-tab-wrapper">
                <a href="?page=<?= esc_html($_GET['page']) ?>&tab=shared" class="nav-tab <?= $activeTab === 'shared' ? 'nav-tab-active' : '' ?>">Shared</a>
                <?php foreach ($blogs as $blog): ?>
                    <?php $blogName = $blog->blogname ?: $blog->domain ?>
                    <a href="?page=<?= esc_html($_GET['page']) ?>&tab=blog_<?= $blog->userblog_id ?>" class="nav-tab <?= $activeTab === 'blog_' . $blog->userblog_id ? 'nav-tab-active' : '' ?>"><?= $blogName ?></a>
                <?php endforeach; ?>
                <a href="http://bosso.nl/plugins/ssv-file-manager/" target="_blank" class="nav-tab">
                    Help <!--suppress HtmlUnknownTarget -->
                    <img src="<?= esc_url(SSV_Global::URL) ?>/images/link-new-tab-small.png" width="14" style="vertical-align:middle">
                </a>
            </h2>
            <?php self::showSiteBaseFieldsSharedTab(); ?>
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
                    $database = SSV_Global::getDatabase();
                    $database->replace(
                        SSV_Forms::SITE_SPECIFIC_FORMS_TABLE,
                        [
                            'f_id'     => $_POST['form_id'],
                            'f_tag'    => $_POST['form_tag'],
                            'f_title'  => $_POST['form_title'],
                            'f_fields' => json_encode(isset($_POST['form_fields']) ? $_POST['form_fields'] : []),
                        ]
                    );
                } elseif (BaseFunctions::isValidPOST(SSV_Forms::ALL_FORMS_ADMIN_REFERER)) {
                    if ($_POST['action'] === 'delete-selected') {
                        mp_ssv_general_forms_delete_form();
                    } else {
                        echo '<div class="notice error"><p>Something unexpected happened. Please try again.</p></div>';
                    }
                }
                $database = SSV_Global::getDatabase();
                $order    = BaseFunctions::sanitize(isset($_GET['order']) ? $_GET['order'] : 'asc', 'text');
                $orderBy  = BaseFunctions::sanitize(isset($_GET['orderby']) ? $_GET['orderby'] : 'f_title', 'text');
                $table    = SSV_Forms::SITE_SPECIFIC_FORMS_TABLE;
                $forms    = $database->get_results("SELECT * FROM $table ORDER BY $orderBy $order");
                $addNew   = '<a href="?page=ssv_forms_add_new_form" class="page-title-action">Add New</a>';
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
        $database                    = SSV_Global::getDatabase();
        $sharedBaseFieldsTable       = SSV_Forms::SHARED_BASE_FIELDS_TABLE;
        $siteSpecificBaseFieldsTable = SSV_Forms::SITE_SPECIFIC_BASE_FIELDS_TABLE;
        $formsTable                  = SSV_Forms::SITE_SPECIFIC_FORMS_TABLE;
        $baseSharedFields            = $database->get_results("SELECT * FROM $sharedBaseFieldsTable ORDER BY bf_name");
        $baseSiteSpecificFields      = $database->get_results("SELECT * FROM $siteSpecificBaseFieldsTable ORDER BY bf_name");
        if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
            $id         = $_GET['id'];
            $formName   = $database->get_var("SELECT f_title FROM $formsTable WHERE f_id = $id");
            $fieldNames = json_decode($database->get_var("SELECT f_fields FROM $formsTable WHERE f_id = $id"));
            $formFields = Forms::getFormFields($fieldNames);
        } else {
            $id         = $database->get_var("SELECT MAX(f_id) AS maxId FROM $formsTable") + 1;
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
                SharedField::deleteByIds(BaseFunctions::sanitize($_POST['ids'], 'int'));
            } else {
                $_SESSION['SSV']['errors'][] = 'Unknown action.';
            }
        }
        $orderBy    = BaseFunctions::sanitize(isset($_GET['orderby']) ? $_GET['orderby'] : 'f_name', 'text');
        $order      = BaseFunctions::sanitize(isset($_GET['order']) ? $_GET['order'] : 'asc', 'text');
        $fields     = Field::getAll($orderBy, $order);
        $addNew     = '<a href="javascript:void(0)" class="page-title-action" onclick="fieldsManager.addNew(\'the-list\', \'\')">Add New</a>';
        ?>
        <h1 class="wp-heading-inline"><span>Shared Form Fields</span><?= current_user_can('manage_shared_base_fields') ? $addNew : '' ?></h1>
        <p>These fields will be available for all sites.</p>
        <?php
        self::showFieldsManager($fields, $order, $orderBy, current_user_can('manage_shared_base_fields'));
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */
    private static function showSiteBaseFieldsSiteSpecificTab()
    {
        if (BaseFunctions::isValidPOST(SSV_Forms::ALL_FORMS_ADMIN_REFERER)) {
            if ($_POST['action'] === 'delete-selected' && !isset($_POST['_inline_edit'])) {
                mp_ssv_general_forms_delete_field(false);
            } else {
                $_SESSION['SSV']['errors'][] = 'Unknown action.';
            }
        }
        $database   = SSV_Global::getDatabase();
        $order      = BaseFunctions::sanitize(isset($_GET['order']) ? $_GET['order'] : 'asc', 'text');
        $orderBy    = BaseFunctions::sanitize(isset($_GET['orderby']) ? $_GET['orderby'] : 'bf_name', 'text');
        $baseTable  = SSV_Forms::SITE_SPECIFIC_BASE_FIELDS_TABLE;
        $baseFields = $database->get_results("SELECT * FROM $baseTable ORDER BY $orderBy $order");
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
            mp_ssv_show_fields_table($fields, $order, $orderBy, $hasManageRight);
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
