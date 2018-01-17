<?php

namespace mp_ssv_general\forms\options;

use mp_ssv_general\base\BaseFunctions;
use mp_ssv_general\base\SSV_Global;
use mp_ssv_general\forms\SSV_Forms;
use stdClass;
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

abstract class Forms
{

    public static function filterContent($content)
    {
        /** @var wpdb $wpdb */
        global $wpdb;
        $table = SSV_Forms::SITE_SPECIFIC_FORMS_TABLE;
        $forms = $wpdb->get_results("SELECT * FROM $table");
        foreach ($forms as $form) {
            if (strpos($content, $form->f_tag) !== false) {
                $content = str_replace($form->f_tag, self::getFormFieldsHTML($form), $content);
            }
        }
        return $content;
    }

    public static function setupNetworkMenu()
    {
        add_menu_page('SSV Forms', 'SSV Forms', 'edit_posts', 'ssv_forms', [self::class, 'showSharedBaseFieldsPage']);
    }

    public static function setupSiteSpecificMenu()
    {
        add_menu_page('SSV Forms', 'SSV Forms', 'ssv_not_allowed', 'ssv_forms');
        add_submenu_page('ssv_forms', 'All Forms', 'All Forms', 'edit_posts', 'ssv_forms', [self::class, 'showFormsPage']);
        add_submenu_page('ssv_forms', 'Add New', 'Add New', 'edit_posts', 'ssv_forms_add_new_form', [self::class, 'showEditFormPage']);
        add_submenu_page('ssv_forms', 'Manage Fields', 'Manage Fields', 'edit_posts', 'ssv_forms_base_fields_manager', [self::class, 'showSiteBaseFieldsPage']);
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
            /** @var wpdb $wpdb */
            global $wpdb;
            $order      = isset($_GET['order']) ? BaseFunctions::sanitize($_GET['order'], 'text') : 'asc';
            $orderBy    = isset($_GET['orderby']) ? BaseFunctions::sanitize($_GET['orderby'], 'text') : 'bf_title';
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

    public static function getWordPressBaseFields(): array
    {
        return json_decode(
            json_encode(
                [
                    [
                        'bf_id'        => 'b_0',
                        'bf_name'      => 'username',
                        'bf_title'     => 'Username',
                        'bf_inputType' => 'text',
                        'bf_value'     => null,
                        'bf_options'   => null,
                    ],
                    [
                        'bf_id'        => 'b_1',
                        'bf_name'      => 'first_name',
                        'bf_title'     => 'First Name',
                        'bf_inputType' => 'text',
                        'bf_value'     => null,
                        'bf_options'   => null,
                    ],
                    [
                        'bf_id'        => 'b_2',
                        'bf_name'      => 'last_name',
                        'bf_title'     => 'Last Name',
                        'bf_inputType' => 'text',
                        'bf_value'     => null,
                        'bf_options'   => null,
                    ],
                    [
                        'bf_id'        => 'b_3',
                        'bf_name'      => 'email',
                        'bf_title'     => 'Email',
                        'bf_inputType' => 'email',
                        'bf_value'     => null,
                        'bf_options'   => null,
                    ],
                    [
                        'bf_id'        => 'b_4',
                        'bf_name'      => 'password',
                        'bf_title'     => 'Password',
                        'bf_inputType' => 'password',
                        'bf_value'     => null,
                        'bf_options'   => null,
                    ],
                    [
                        'bf_id'        => 'b_5',
                        'bf_name'      => 'password_confirm',
                        'bf_title'     => 'Confirm Password',
                        'bf_inputType' => 'password',
                        'bf_value'     => null,
                        'bf_options'   => null,
                    ],
                ]
            )
        );
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
                    /** @var wpdb $wpdb */
                    global $wpdb;
                    $wpdb->replace(
                        SSV_Forms::SITE_SPECIFIC_FORMS_TABLE,
                        [
                            'f_id'     => $_POST['form_id'],
                            'f_tag'    => $_POST['form_tag'],
                            'f_title'  => $_POST['form_title'],
                            'f_fields' => json_encode($_POST['form_fields']),
                        ]
                    );
                } elseif (BaseFunctions::isValidPOST(SSV_Forms::ALL_FORMS_ADMIN_REFERER)) {
                    if ($_POST['action'] === 'delete-selected') {
                        mp_ssv_general_forms_delete_shared_forms(false);
                    } else {
                        echo '<div class="notice error"><p>Something unexpected happened. Please try again.</p></div>';
                    }
                }
                /** @var wpdb $wpdb */
                global $wpdb;
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
        /** @var wpdb $wpdb */
        global $wpdb;
        $sharedBaseFieldsTable       = SSV_Forms::SHARED_BASE_FIELDS_TABLE;
        $siteSpecificBaseFieldsTable = SSV_Forms::SITE_SPECIFIC_BASE_FIELDS_TABLE;
        $formsTable                  = SSV_Forms::SITE_SPECIFIC_FORMS_TABLE;
        $baseSharedFields            = $wpdb->get_results("SELECT * FROM $sharedBaseFieldsTable ORDER BY bf_title");
        $baseSiteSpecificFields      = $wpdb->get_results("SELECT * FROM $siteSpecificBaseFieldsTable ORDER BY bf_title");
        if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
            $id         = $_GET['id'];
            $formName   = $wpdb->get_var("SELECT f_title FROM $formsTable WHERE f_id = $id");
            $fieldNames = json_decode($wpdb->get_var("SELECT f_fields FROM $formsTable WHERE f_id = $id"));
            $formFields = self::getFormFields($fieldNames);
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
        $function = 'showSiteBaseFields' . ucfirst($activeTab) . 'Tab';
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
                <?= BaseFunctions::getAdminFormSecurityFields(SSV_Forms::ALL_FORMS_ADMIN_REFERER, false, false) ?>
                <?php
            }
            ?>
        </form>
        <?php
    }

    public static function getFormFieldsHTML(stdClass $form): string
    {
        $formFields = self::getFormFields(json_decode($form->f_fields));
        ob_start();
        foreach ($formFields as $field) {
            $field    = json_decode(json_encode($field), true);
            $newField = [];
            foreach ($field as $key => $value) {
                $newField[str_replace('bf_', '', $key)] = $value;
            }
            switch ($newField['inputType']) {
                case 'hidden':
                    /** @noinspection PhpIncludeInspection */
                    require_once SSV_Forms::PATH . 'templates/fields/hidden.php';
                    show_hidden_input_field($newField);
                    break;
                case 'select':
                    /** @noinspection PhpIncludeInspection */
                    require_once SSV_Forms::PATH . 'templates/fields/select.php';
                    show_select_input_field($form->f_id, $newField);
                    break;
                case 'checkbox':
                    /** @noinspection PhpIncludeInspection */
                    require_once SSV_Forms::PATH . 'templates/fields/checkbox.php';
                    show_checkbox_input_field($form->f_id, $newField);
                    break;
                case 'datetime':
                    /** @noinspection PhpIncludeInspection */
                    require_once SSV_Forms::PATH . 'templates/fields/datetime.php';
                    show_datetime_input_field($form->f_id, $newField);
                    break;
                default:
                    /** @noinspection PhpIncludeInspection */
                    require_once SSV_Forms::PATH . 'templates/fields/input.php';
                    show_default_input_field($form->f_id, $newField);
                    break;
            }
        }
        return ob_get_clean();
    }

    private static function getFormFields(array $fieldNames): array
    {
        $wordPressBaseFields = self::getWordPressBaseFields();
        $formFields          = array_filter(
            $wordPressBaseFields,
            function ($field) use ($fieldNames) {
                return in_array($field->bf_name, $fieldNames);
            }
        );
        /** @var wpdb $wpdb */
        global $wpdb;
        $fieldNames                  = '"' . implode('", "', $fieldNames) . '"';
        $sharedBaseFieldsTable       = SSV_Forms::SHARED_BASE_FIELDS_TABLE;
        $siteSpecificBaseFieldsTable = SSV_Forms::SITE_SPECIFIC_BASE_FIELDS_TABLE;
        $databaseFields = $wpdb->get_results("SELECT * FROM (SELECT * FROM $sharedBaseFieldsTable UNION SELECT * FROM $siteSpecificBaseFieldsTable) combined WHERE bf_name IN ($fieldNames) ORDER BY FIELD(`bf_name`,$fieldNames)");
        return array_merge($formFields, $databaseFields);
    }
}

add_action('network_admin_menu', [Forms::class, 'setupNetworkMenu']);
add_action('admin_menu', [Forms::class, 'setupSiteSpecificMenu']);
add_filter('the_content', [Forms::class, 'filterContent']);
