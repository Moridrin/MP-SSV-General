<?php

namespace mp_ssv_general\forms;

use mp_ssv_general\base\BaseFunctions;
use mp_ssv_general\base\SSV_Global;
use mp_ssv_general\forms\models\Field;
use mp_ssv_general\forms\models\Form;
use mp_ssv_general\forms\models\FormField;
use mp_ssv_general\forms\models\SharedField;
use mp_ssv_general\forms\models\SiteSpecificField;

if (!defined('ABSPATH')) {
    exit;
}

abstract class Options
{
    public static function setupNetworkMenu()
    {
        add_menu_page('SSV Forms', 'SSV Forms', 'edit_posts', 'ssv_forms', [self::class, 'showSharedFieldsPage'], 'dashicons-feedback');
    }

    public static function setupSiteSpecificMenu()
    {
        add_menu_page('SSV Forms', 'SSV Forms', 'edit_posts', 'ssv_forms', '', 'dashicons-feedback');
        add_submenu_page('ssv_forms', 'All Forms', 'All Forms', 'edit_posts', 'ssv_forms', [self::class, 'showFormsPage']);
        add_submenu_page('ssv_forms', 'Add New', 'Add New', 'edit_posts', 'ssv_forms_add_new_form', [self::class, 'showFormPageEdit']);
        add_submenu_page('ssv_forms', 'Manage Fields', 'Manage Fields', 'edit_posts', 'ssv_forms_fields_manager', [self::class, 'showCombinedFieldsPage']);
    }

    public static function showSharedFieldsPage()
    {
        $activeTab = $_GET['tab'] ?? 'shared';
        ?>
        <div class="wrap">
            <h2 class="nav-tab-wrapper">
                <a href="?page=<?= esc_html($_GET['page']) ?>&tab=shared" class="nav-tab <?= $activeTab === 'shared' ? 'nav-tab-active' : '' ?>">Shared</a>
                <a href="http://bosso.nl/plugins/ssv-file-manager/" target="_blank" class="nav-tab">
                    Help <!--suppress HtmlUnknownTarget -->
                    <img src="<?= esc_url(SSV_Global::URL) ?>/images/link-new-tab-small.png" width="14" style="vertical-align:middle">
                </a>
            </h2>
            <?php self::showSharedFieldsTab(); ?>
        </div>
        <?php
    }

    public static function showFormsPage()
    {
        if (isset($_GET['action']) && $_GET['action'] === 'edit') {
            self::showFormPageEdit();
            return;
        }
        ?>
        <div class="wrap">
            <?php
            if (BaseFunctions::isValidPOST(SSV_Forms::ADMIN_REFERER)) {
                $formId = BaseFunctions::sanitize($_POST['formId'], 'int');
                $formTitle = BaseFunctions::sanitize($_POST['formTitle'], 'text');
                if ($formId === -1) {
                    $formId = Form::create($formTitle);
                }
                $fields = [];
                if (isset($_POST['fields'])) {
                    foreach ($_POST['fields'] as $field) {
                        $field = BaseFunctions::sanitize(json_decode(stripslashes($field), true), 'string');
                        $found = Field::findByName($field['name'], $formId);
                        if ($found === null) {
                            FormField::create($field['name'], $field, $formId);
                        } elseif ($found instanceof FormField) {
                            $found
                                ->setProperties($field)
                                ->save()
                            ;
                        } elseif (!$found->equals($field)) {
                            FormField::create($field['name'], $field, $formId);
                        }
                        $fields[] = $field['name'];
                    }
                }
                Form::findById($formId)
                    ->setTitle($formTitle)
                    ->setFields($fields)
                    ->save()
                ;
            }
            $order    = BaseFunctions::sanitize(isset($_GET['order']) ? $_GET['order'] : 'asc', 'text');
            $orderBy  = BaseFunctions::sanitize(isset($_GET['orderby']) ? $_GET['orderby'] : 'f_title', 'text');
            $addNew   = '<a href="?page=ssv_forms_add_new_form" class="page-title-action">Add New</a>';
            ?>
            <h1 class="wp-heading-inline"><span>Site Specific Forms</span><?= current_user_can('manage_site_specific_forms') ? $addNew : '' ?></h1>
            <p>These forms will only be available for <?= get_bloginfo() ?>.</p>
            <?php
            mp_ssv_show_table(Form::class, $orderBy, $order, current_user_can('manage_site_specific_forms'))
            ?>
        </div>
        <?php
    }

    public static function showFormPageEdit()
    {
        if (BaseFunctions::isValidPOST(SSV_Forms::ADMIN_REFERER)) {
            BaseFunctions::var_export($_POST, 1);
        }
        if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
            $form = Form::findById($_GET['id']);
            if ($form === null) {
                throw new \Exception('Form not found');
            }
        } else {
            $form = Form::getDummy();
//            $formId = Form::create('');
//            if ($formId === null) {
//                throw new \Exception('Form could not be created');
//            }
//            $form = Form::findById($formId);
        }
        mp_ssv_show_form_editor($form);
    }

    public static function showCombinedFieldsPage()
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
            $function = 'show' . ucfirst($activeTab) . 'FieldsTab';
            if (method_exists(Options::class, $function)) {
                self::$function();
            } else {
                ?><div class="notice error"><p>Unknown Tab</p></div><?php
            }
            ?>
        </div>
        <?php
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */
    private static function showSharedFieldsTab()
    {
        if (BaseFunctions::isValidPOST(SSV_Forms::ADMIN_REFERER)) {
            if ($_POST['action'] === 'delete-selected' && !isset($_POST['_inline_edit'])) {
                SharedField::deleteByIds(BaseFunctions::sanitize($_POST['ids'], 'int'));
            } else {
                SSV_Global::addError('Unknown action.');
            }
        }
        $orderBy    = BaseFunctions::sanitize(isset($_GET['orderby']) ? $_GET['orderby'] : 'f_name', 'text');
        $order      = BaseFunctions::sanitize(isset($_GET['order']) ? $_GET['order'] : 'asc', 'text');
        $addNew     = '<a href="javascript:void(0)" class="page-title-action" onclick="fieldsManager.addNew(\'the-list\', \'\')">Add New</a>';
        ?>
        <h1 class="wp-heading-inline"><span>Shared Form Fields</span><?= current_user_can('manage_shared_base_fields') ? $addNew : '' ?></h1>
        <p>These fields will be available for all sites.</p>
        <?php
        mp_ssv_show_table(SharedField::class, $orderBy, $order, current_user_can('manage_shared_base_fields'));
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */
    private static function showSiteSpecificFieldsTab()
    {
        if (BaseFunctions::isValidPOST(SSV_Forms::ADMIN_REFERER)) {
            if ($_POST['action'] === 'delete-selected' && !isset($_POST['_inline_edit'])) {
                SiteSpecificField::deleteByIds(BaseFunctions::sanitize($_POST['ids'], 'int'));
            } else {
                SSV_Global::addError('Unknown action.');
            }
        }
        $orderBy    = BaseFunctions::sanitize(isset($_GET['orderby']) ? $_GET['orderby'] : 'f_name', 'text');
        $order      = BaseFunctions::sanitize(isset($_GET['order']) ? $_GET['order'] : 'asc', 'text');
        ?>
        <h1 class="wp-heading-inline"><span>Site Specific Form Fields</span>
            <?php
            if (current_user_can('manage_site_specific_base_fields')) {
                ?><a href="javascript:void(0)" class="page-title-action" onclick="fieldsManager.addNew('the-list', '')">Add New</a><?php
            }
            ?>
        </h1>
        <p>These fields will only be available for <?= get_bloginfo() ?>.</p>
        <?php
        mp_ssv_show_table(SiteSpecificField::class, $orderBy, $order, current_user_can('manage_site_specific_base_fields'));
    }
}

add_action('network_admin_menu', [Options::class, 'setupNetworkMenu']);
add_action('admin_menu', [Options::class, 'setupSiteSpecificMenu']);
