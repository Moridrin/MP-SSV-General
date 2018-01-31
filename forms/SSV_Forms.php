<?php

namespace mp_ssv_general\forms;

use mp_ssv_general\base\BaseFunctions;
use mp_ssv_general\base\SSV_Global;
use wpdb;

if (!defined('ABSPATH')) {
    exit;
}

abstract class SSV_Forms
{
    const PATH = SSV_FORMS_PATH;
    const URL = SSV_FORMS_URL;

    const ALL_FORMS_ADMIN_REFERER = 'ssv_forms__all_forms_admin_referer';
    const EDIT_FORM_ADMIN_REFERER = 'ssv_forms__edit_form_admin_referer';

    const SHARED_BASE_FIELDS_TABLE = SSV_FORMS_SHARED_BASE_FIELDS_TABLE;
    const SITE_SPECIFIC_BASE_FIELDS_TABLE = SSV_FORMS_SITE_SPECIFIC_BASE_FIELDS_TABLE;
    const CUSTOMIZED_FIELDS_TABLE = SSV_FORMS_CUSTOMIZED_FIELDS;
    const SITE_SPECIFIC_FORMS_TABLE = SSV_FORMS_SITE_SPECIFIC_FORMS_TABLE;

    public static function setupForBlog() {
        $wpdb = SSV_Global::getDatabase();
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $charset_collate = $wpdb->get_charset_collate();

        $tableName = self::SHARED_BASE_FIELDS_TABLE;
        $sql
                   = "
            CREATE TABLE IF NOT EXISTS $tableName (
            `bf_name` VARCHAR(50) PRIMARY KEY,
            `bf_properties` TEXT NOT NULL
            ) $charset_collate;";
        $wpdb->query($sql);
        if (!empty($wpdb->last_error)) {
            $_SESSION['SSV']['errors'][] = $wpdb->last_error;
        }

        $tableName = $wpdb->prefix . 'ssv_base_fields';
        $sql
                   = "
        CREATE TABLE IF NOT EXISTS $tableName (
            `bf_name` VARCHAR(50) PRIMARY KEY,
            `bf_properties` TEXT NOT NULL
        ) $charset_collate;";
        $wpdb->query($sql);
        if (!empty($wpdb->last_error)) {
            $_SESSION['SSV']['errors'][] = $wpdb->last_error;
        }

        $tableName = $wpdb->prefix . 'ssv_forms';
        $sql
                   = "
        CREATE TABLE IF NOT EXISTS $tableName (
            `f_id` bigint(20) NOT NULL PRIMARY KEY AUTO_INCREMENT,
            `f_tag` VARCHAR(50) UNIQUE,
            `f_title` VARCHAR(50) NOT NULL,
            `f_fields` TEXT
        ) $charset_collate;";
        $wpdb->query($sql);
        if (!empty($wpdb->last_error)) {
            $_SESSION['SSV']['errors'][] = $wpdb->last_error;
        }

        $tableName = $wpdb->prefix . 'ssv_customized_fields';
        $sql
                   = "
        CREATE TABLE IF NOT EXISTS $tableName (
            `f_id` BIGINT(20) NOT NULL,
            `bf_name` VARCHAR(50) NOT NULL,
            `cf_properties` TEXT NOT NULL,
            PRIMARY KEY (`f_id`, `bf_name`)
        ) $charset_collate;";
        $wpdb->query($sql);
        if (!empty($wpdb->last_error)) {
            $_SESSION['SSV']['errors'][] = $wpdb->last_error;
        }
    }

    public static function setup($networkEnable)
    {
        if ($networkEnable) {
            SSV_Global::runFunctionOnAllSites([self::class, 'setupForBlog']);
        } else {
            self::setupForBlog();
        }
    }

    public static function enqueueAdminScripts()
    {
        $page      = isset($_GET['page']) ? $_GET['page'] : null;
        $activeTab = isset($_GET['tab']) ? $_GET['tab'] : 'shared';
        if ($page === 'ssv_forms_fields_manager') {

            $wpdb = SSV_Global::getDatabase();
            $sharedBaseFieldsTable = SSV_Forms::SHARED_BASE_FIELDS_TABLE;
            $usedFieldNames = $wpdb->get_col("SELECT bf_name FROM $sharedBaseFieldsTable");
            if ($activeTab !== 'shared') {
                $siteSpecificBaseFieldsTable = SSV_Forms::SITE_SPECIFIC_BASE_FIELDS_TABLE;
                $usedFieldNames = array_merge($usedFieldNames, $wpdb->get_col("SELECT bf_name FROM $siteSpecificBaseFieldsTable"));
            }
            $duplicateNames = array_diff_assoc($usedFieldNames, array_unique($usedFieldNames));
            foreach ($duplicateNames as $duplicateName) {
                $_SESSION['SSV']['errors'][] = 'The field with the name \'' . $duplicateName . '\' is not unique. Fields with the same name can cause loss of data!';
            }

            wp_enqueue_script('mp-ssv-fields-manager', SSV_Forms::URL . '/js/fields-manager.js', ['jquery']);
            wp_localize_script(
                'mp-ssv-fields-manager',
                'mp_ssv_fields_manager_params',
                [
                    'urls' => [
                        'plugins'  => plugins_url(),
                        'ajax'     => admin_url('admin-ajax.php'),
                        'base'     => get_home_url(),
                        'basePath' => ABSPATH,
                    ],
                    'actions' => [
                        'save'   => 'mp_ssv_general_forms_save_field',
                        'delete' => 'mp_ssv_general_forms_delete_fields',
                    ],
                    'isShared' => $activeTab === 'shared',
                    'roles' => array_keys(get_editable_roles()),
                    'usedFieldNames' => $usedFieldNames,
                    'inputTypes' => BaseFunctions::getInputTypes($activeTab === 'shared' ? ['role_checkbox', 'role_select'] : []),
                    'formId' => isset($_GET['id']) ? $_GET['id'] : null,
                ]
            );
        }
    }

    public static function cleanInstallForBlog()
    {
        $wpdb = SSV_Global::getDatabase();
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $tableName = $wpdb->prefix . 'ssv_base_fields';
        $wpdb->query("DROP TABLE $tableName;");
        $tableName = $wpdb->prefix . 'ssv_customized_fields';
        $wpdb->query("DROP TABLE $tableName;");
        $tableName = $wpdb->prefix . 'ssv_forms';
        $wpdb->query("DROP TABLE $tableName;");
        self::setupForBlog();
    }

    public static function CLEAN_INSTALL($networkEnable)
    {
        $wpdb = SSV_Global::getDatabase();
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $tableName = self::SHARED_BASE_FIELDS_TABLE;
        $wpdb->query("DROP TABLE $tableName;");
        if ($networkEnable) {
            SSV_Global::runFunctionOnAllSites([self::class, 'cleanInstallForBlog']);
        } else {
            self::cleanInstallForBlog();
        }
    }
}

register_activation_hook(SSV_FORMS_ACTIVATOR_PLUGIN, [SSV_Forms::class, 'setup']);
add_action('admin_enqueue_scripts', [SSV_Forms::class, 'enqueueAdminScripts']);
