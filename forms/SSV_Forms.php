<?php

namespace mp_ssv_forms;

use wpdb;

if (!defined('ABSPATH')) {
    exit;
}

$currentDir = getcwd();
chdir(__DIR__ . DIRECTORY_SEPARATOR . '../..');
define('SSV_FORMS_ACTIVATOR_PLUGIN', getcwd() . DIRECTORY_SEPARATOR . glob('ss?-*.php')[0]);
define('SSV_FORMS_ACTIVATOR_PLUGIN_RELATIVE', str_replace(ABSPATH . 'wp-content/plugins/', '', SSV_FORMS_ACTIVATOR_PLUGIN));
chdir($currentDir);
define('SSV_FORMS_PATH', plugin_dir_path(__FILE__));
define('SSV_FORMS_URL', plugins_url() . '/' . plugin_basename(__DIR__));
global $wpdb;
define('SSV_FORMS_SHARED_BASE_FIELDS_TABLE', $wpdb->base_prefix . "ssv_shared_base_fields");
define('SSV_FORMS_SITE_SPECIFIC_BASE_FIELDS_TABLE', $wpdb->prefix . "ssv_base_fields");
define('SSV_FORMS_CUSTOMIZED_FIELDS', $wpdb->prefix . "ssv_customized_fields");

abstract class SSV_Forms
{
    const PATH = SSV_FORMS_PATH;
    const URL = SSV_FORMS_URL;

    const SHARED_BASE_FIELDS_TABLE = SSV_FORMS_SHARED_BASE_FIELDS_TABLE;
    const SITE_SPECIFIC_BASE_FIELDS_TABLE = SSV_FORMS_SITE_SPECIFIC_BASE_FIELDS_TABLE;
    const CUSTOMIZED_FIELDS_TABLE = SSV_FORMS_CUSTOMIZED_FIELDS;

    public static function setup($networkEnable)
    {
        /** @var wpdb $wpdb */
        global $wpdb;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $charset_collate = $wpdb->get_charset_collate();
        if (is_multisite() && $networkEnable) {
            $blogIds = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
        } else {
            $blogIds = [get_current_blog_id()];
        }
        $tableName = self::SHARED_BASE_FIELDS_TABLE;
        $sql
                   = "
            CREATE TABLE IF NOT EXISTS $tableName (
                `bf_id` bigint(20) NOT NULL PRIMARY KEY,
                `bf_name` VARCHAR(50) UNIQUE,
                `bf_title` VARCHAR(50) NOT NULL,
                `bf_inputType` VARCHAR(50),
                `bf_value` VARCHAR(50)
            ) $charset_collate;";
        $wpdb->query($sql);

        foreach ($blogIds as $blog_id) {
            switch_to_blog($blog_id);
            $tableName = $wpdb->prefix . 'ssv_base_fields';
            $sql
                       = "
            CREATE TABLE IF NOT EXISTS $tableName (
                `bf_id` bigint(20) NOT NULL PRIMARY KEY,
                `bf_name` VARCHAR(50) UNIQUE,
                `bf_title` VARCHAR(50) NOT NULL,
                `bf_inputType` VARCHAR(50),
                `bf_value` VARCHAR(50)
            ) $charset_collate;";
            $wpdb->query($sql);

            $tableName = $wpdb->prefix . 'ssv_customized_fields';
            $sql
                       = "
            CREATE TABLE IF NOT EXISTS $tableName (
                `cf_id` bigint(20) NOT NULL PRIMARY KEY AUTO_INCREMENT,
                `cf_bf_id` bigint(20),
                `cf_json` TEXT NOT NULL
            ) $charset_collate;";
            $wpdb->query($sql);
            restore_current_blog();
        }
    }

    public static function enquireAdminScripts()
    {
        if (isset($_GET["page"]) && $_GET["page"] == "ssv_forms") {
            wp_enqueue_script('mp-ssv-base-fields-manager', SSV_Forms::URL . '/js/mp-ssv-base-fields-manager.js', ['jquery']);
            wp_enqueue_script('mp-ssv-fields-management', SSV_Forms::URL . '/js/mp-ssv-fields-management.js', ['jquery']);
            wp_localize_script(
                'mp-ssv-fields-management',
                'urls',
                [
                    'plugins'  => plugins_url(),
                    'ajax'     => admin_url('admin-ajax.php'),
                    'base'     => get_home_url(),
                    'basePath' => ABSPATH,
                ]
            );
            wp_localize_script(
                'mp-ssv-fields-management',
                'roles',
                array_keys(get_editable_roles())
            );
        }
    }

    public static function CLEAN_INSTALL($networkWide)
    {
        /** @var wpdb $wpdb */
        global $wpdb;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $tableName = self::SHARED_BASE_FIELDS_TABLE;
        $wpdb->query("DROP TABLE $tableName;");
        $tableName = self::CUSTOMIZED_FIELDS_TABLE;
        $wpdb->query("DROP TABLE $tableName;");
        self::setup($networkWide);
    }
}

register_activation_hook(SSV_FORMS_ACTIVATOR_PLUGIN, [SSV_Forms::class, 'setup']);
add_action('admin_enqueue_scripts', [SSV_Forms::class, 'enquireAdminScripts']);
