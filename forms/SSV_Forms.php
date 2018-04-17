<?php

namespace mp_ssv_general\forms;

use mp_ssv_general\base\BaseFunctions;
use mp_ssv_general\base\SSV_Global;

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

    public static function setupForBlog(int $blogId = null)
    {
        $database = SSV_Global::getDatabase();
        if ($blogId === null) {
            global $wpdb;
            $prefix = $wpdb->prefix;
        } else {
            $prefix = $database->get_blog_prefix($blogId);
        }

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $charset_collate = $database->get_charset_collate();

        $tableName = self::SHARED_BASE_FIELDS_TABLE;
        $sql
                   = "
            CREATE TABLE IF NOT EXISTS $tableName (
            `bf_name` VARCHAR(50) PRIMARY KEY,
            `bf_properties` TEXT NOT NULL
            ) $charset_collate;";
        $database->query($sql);

        $tableName = $prefix . 'ssv_base_fields';
        $sql
                   = "
        CREATE TABLE IF NOT EXISTS $tableName (
            `bf_name` VARCHAR(50) PRIMARY KEY,
            `bf_properties` TEXT NOT NULL
        ) $charset_collate;";
        $database->query($sql);

        $tableName = $database->get_blog_prefix($blogId) . 'ssv_forms';
        $sql
                   = "
        CREATE TABLE IF NOT EXISTS $tableName (
            `f_id` bigint(20) NOT NULL PRIMARY KEY AUTO_INCREMENT,
            `f_tag` VARCHAR(50) UNIQUE,
            `f_title` VARCHAR(50) NOT NULL,
            `f_fields` TEXT
        ) $charset_collate;";
        $database->query($sql);

        $tableName = $database->get_blog_prefix($blogId) . 'ssv_customized_fields';
        $sql
                   = "
        CREATE TABLE IF NOT EXISTS $tableName (
            `f_id` BIGINT(20) NOT NULL,
            `bf_name` VARCHAR(50) NOT NULL,
            `cf_properties` TEXT NOT NULL,
            PRIMARY KEY (`f_id`, `bf_name`)
        ) $charset_collate;";
        $database->query($sql);
    }

    public static function addSite(int $blogId)
    {
        foreach (wp_get_active_network_plugins() as $plugin) {
            if (preg_match_all('/.*(ssv[a-z-]+).php/', $plugin)) {
                self::setupForBlog($blogId);
                return;
            }
        }
    }

    public static function deleteSite(int $blogId)
    {
        self::cleanupBlog($blogId);
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
        $page = $_GET['page'] ?? null;
        if (!in_array($page, ['ssv_forms_fields_manager', 'ssv_forms'])) {
            return;
        }
        switch ($page) {
            case 'ssv_forms':
                if (is_network_admin()) {
                    self::enquireFieldsManagerScripts();
                }
                break;
            case 'ssv_forms_fields_manager':
                self::enquireFieldsManagerScripts();
                break;
        }
    }

    private static function enquireFieldsManagerScripts()
    {
        $activeTab = $_GET['tab'] ?? 'shared';
        wp_enqueue_script('mp-ssv-fields-manager', SSV_Forms::URL . '/js/fields-manager.js', ['jquery']);
        wp_localize_script('mp-ssv-fields-manager', 'mp_ssv_fields_manager_params', [
            'urls'       => [
                'plugins'  => plugins_url(),
                'ajax'     => admin_url('admin-ajax.php'),
                'base'     => get_home_url(),
                'basePath' => ABSPATH,
            ],
            'actions'    => [
                'save'   => 'mp_ssv_general_forms_save_field',
                'delete' => 'mp_ssv_general_forms_delete_field',
            ],
            'isShared'   => $activeTab === 'shared',
            'roles'      => array_keys(get_editable_roles()),
            'inputTypes' => BaseFunctions::getInputTypes($activeTab === 'shared' ? ['role_checkbox', 'role_select'] : []),
            'formId'     => $_GET['id'] ?? null,
        ]);
    }

    public static function cleanInstallBlog(int $blogId = null)
    {
        if ($blogId === null) {
            $blogId = get_current_blog_id();
        }
        self::cleanupBlog($blogId);
        self::setupForBlog($blogId);
    }

    public static function cleanupBlog(int $blogId = null)
    {
        $database = SSV_Global::getDatabase();
        if ($blogId === null) {
            global $wpdb;
            $prefix = $wpdb->prefix;
        } else {
            $prefix = $database->get_blog_prefix($blogId);
        }
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $tableName = $prefix . 'ssv_base_fields';
        $database->query("DROP TABLE $tableName;");
        $tableName = $prefix . 'ssv_customized_fields';
        $database->query("DROP TABLE $tableName;");
        $tableName = $prefix . 'ssv_forms';
        $database->query("DROP TABLE $tableName;");
    }

    public static function CLEAN_INSTALL($networkEnable)
    {
        $database = SSV_Global::getDatabase();
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $tableName = self::SHARED_BASE_FIELDS_TABLE;
        $database->query("DROP TABLE $tableName;");
        if ($networkEnable) {
            SSV_Global::runFunctionOnAllSites([self::class, 'cleanInstallBlog']);
        } else {
            self::cleanInstallBlog();
        }
    }
}

register_activation_hook(SSV_FORMS_ACTIVATOR_PLUGIN, [SSV_Forms::class, 'setup']);
register_deactivation_hook(SSV_FORMS_ACTIVATOR_PLUGIN, [SSV_Forms::class, 'deactivate']);
add_action('wpmu_new_blog', [SSV_Forms::class, 'addSite']);
add_action('delete_blog', [SSV_Forms::class, 'deleteSite']);
add_action('admin_enqueue_scripts', [SSV_Forms::class, 'enqueueAdminScripts']);
