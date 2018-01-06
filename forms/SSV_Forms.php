<?php

namespace mp_ssv_general\forms;

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

    public static function setup($networkEnable)
    {
        /** @var wpdb $wpdb */
        global $wpdb;
        require_once(ABSPATH.'wp-admin/includes/upgrade.php');
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
                `bf_options` TEXT,
                `bf_value` VARCHAR(255)
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
                `bf_options` TEXT,
                `bf_value` VARCHAR(255)
            ) $charset_collate;";
            $wpdb->query($sql);

            $tableName = $wpdb->prefix . 'ssv_customized_fields';
            $sql
                       = "
            CREATE TABLE IF NOT EXISTS $tableName (
                `cf_id` bigint(20) NOT NULL PRIMARY KEY AUTO_INCREMENT,
                `cf_f_id` bigint(20),
                `cf_bf_id` bigint(20),
                `cf_json` TEXT NOT NULL
            ) $charset_collate;";
            $wpdb->query($sql);

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
            restore_current_blog();
        }
    }

    public static function enquireAdminScripts()
    {
        $page      = isset($_GET['page']) ? $_GET['page'] : null;
        $action    = isset($_GET['action']) ? $_GET['action'] : null;
        $activeTab = isset($_GET['tab']) ? $_GET['tab'] : 'shared';
        if ($page === 'ssv_forms_base_fields_manager') {
            wp_enqueue_script('mp-ssv-base-fields-manager', SSV_Forms::URL . '/js/base-fields-manager.js', ['jquery']);
            wp_enqueue_script('mp-ssv-fields-management', SSV_Forms::URL . '/js/fields-management.js', ['jquery']);
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
                'actions',
                [
                    'save'   => $activeTab === 'shared' ? 'mp_ssv_general_forms_save_shared_base_field' : 'mp_ssv_general_forms_save_site_specific_base_field',
                    'delete' => $activeTab === 'shared' ? 'mp_ssv_general_forms_delete_shared_base_fields' : 'mp_ssv_general_forms_delete_site_specific_base_fields',
                ]
            );
            wp_localize_script(
                'mp-ssv-fields-management',
                'roles',
                array_keys(get_editable_roles())
            );
        }

        if ($page === 'ssv_forms' && $action !== 'edit') {
            wp_enqueue_script('mp-ssv-forms-manager', SSV_Forms::URL . '/js/forms-manager.js', ['jquery']);
            wp_localize_script(
                'mp-ssv-forms-manager',
                'urls',
                [
                    'plugins'  => plugins_url(),
                    'ajax'     => admin_url('admin-ajax.php'),
                    'base'     => get_home_url(),
                    'basePath' => ABSPATH,
                ]
            );
            wp_localize_script(
                'mp-ssv-forms-manager',
                'actions',
                ['delete' => 'mp_ssv_general_forms_delete_shared_forms']
            );
        }

        if ($page === 'ssv_forms_add_new_form' || ($page === 'ssv_forms' && $action === 'edit')) {
            wp_enqueue_style('mp-ssv-forms-manager-css', SSV_Forms::URL . '/css/forms-editor.css');
            wp_enqueue_script('mp-ssv-form-editor', SSV_Forms::URL . '/js/form-editor.js', ['jquery']);
            wp_enqueue_script('mp-ssv-fields-customizer', SSV_Forms::URL . '/js/fields-customizer.js', ['jquery']);
            wp_localize_script(
                'mp-ssv-fields-customizer',
                'urls',
                [
                    'plugins'  => plugins_url(),
                    'ajax'     => admin_url('admin-ajax.php'),
                    'base'     => get_home_url(),
                    'basePath' => ABSPATH,
                ]
            );
            wp_localize_script(
                'mp-ssv-fields-customizer',
                'actions',
                ['save'   => 'mp_ssv_general_forms_save_customized_field']
            );
        }
    }

    public static function CLEAN_INSTALL($networkWide)
    {
        /** @var wpdb $wpdb */
        global $wpdb;
        require_once(ABSPATH.'wp-admin/includes/upgrade.php');
        $tableName = self::SHARED_BASE_FIELDS_TABLE;
        $wpdb->query("DROP TABLE $tableName;");
        $tableName = self::SITE_SPECIFIC_BASE_FIELDS_TABLE;
        $wpdb->query("DROP TABLE $tableName;");
        $tableName = self::CUSTOMIZED_FIELDS_TABLE;
        $wpdb->query("DROP TABLE $tableName;");
        $tableName = self::SITE_SPECIFIC_FORMS_TABLE;
        $wpdb->query("DROP TABLE $tableName;");
        self::setup($networkWide);
    }
}

register_activation_hook(SSV_FORMS_ACTIVATOR_PLUGIN, [SSV_Forms::class, 'setup']);
add_action('admin_enqueue_scripts', [SSV_Forms::class, 'enquireAdminScripts']);
