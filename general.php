<?php

use mp_ssv_general\SSV_General;
use mp_ssv_general\User;

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('mp_ssv_general\SSV_General')) {
    global $wpdb;
    define('SSV_GENERAL_PATH', plugin_dir_path(__FILE__));
    define('SSV_GENERAL_URL', plugins_url() . '/' . plugin_basename(__DIR__));
    define('SSV_GENERAL_BASE_URL', (is_ssl() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST']);
    define('SSV_GENERAL_BASE_FIELDS_TABLE', $wpdb->base_prefix . "ssv_general_base_fields");
    define('SSV_GENERAL_CUSTOMIZED_FIELDS', $wpdb->prefix . "ssv_general_customized_fields");
    require_once 'SSV_General.php';
    require_once 'models/custom-fields/Field.php';
    SSV_General::_init();

    function mp_ssv_general_admin_scripts()
    {
        wp_enqueue_script('mp-ssv-general-functions', SSV_General::URL . '/js/mp-ssv-general-functions.js', ['jquery']);
        wp_enqueue_script('mp-ssv-sortable-tables', SSV_General::URL . '/js/mp-ssv-sortable-tables.js', ['jquery', 'jquery-ui-sortable']);
        if (isset($_GET["page"]) && $_GET["page"] == "ssv_settings") {
            wp_enqueue_script('mp-ssv-custom-field-creator', SSV_General::URL . '/js/mp-ssv-custom-field-creator.js', ['jquery']);
            wp_localize_script('mp-ssv-custom-field-creator', 'settings', ['roles' => json_encode(array_keys(get_editable_roles())),]);
        } else {
            wp_enqueue_script('mp-ssv-custom-field-customizer', SSV_General::URL . '/js/mp-ssv-custom-field-customizer.js', ['jquery']);
            wp_localize_script(
                'mp-ssv-custom-field-customizer',
                'settings',
                [
                    'roles'   => json_encode(array_keys(get_editable_roles())),
                    'columns' => User::getCurrent()->getMeta(SSV_General::USER_OPTION_CUSTOM_FIELD_FIELDS, []),
                ]
            );
        }
    }

    add_action('admin_enqueue_scripts', 'mp_ssv_general_admin_scripts');

    function mp_ssv_general_register_plugin()
    {
        /** @var wpdb $wpdb */
        global $wpdb;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $charset_collate = $wpdb->get_charset_collate();

        $tableName = SSV_General::BASE_FIELDS_TABLE;
        $sql
                    = "
		CREATE TABLE IF NOT EXISTS $tableName (
			`bf_id` bigint(20) NOT NULL PRIMARY KEY,
			`bf_name` VARCHAR(50) UNIQUE,
			`bf_title` VARCHAR(50) NOT NULL,
			`bf_type` VARCHAR(50),
			`bf_inputType` VARCHAR(50)
		) $charset_collate;";
        $wpdb->query($sql);

        $tableName = SSV_General::CUSTOMIZED_FIELDS_TABLE;
        $sql
                    = "
		CREATE TABLE IF NOT EXISTS $tableName (
			`cf_id` bigint(20) NOT NULL PRIMARY KEY AUTO_INCREMENT,
			`cf_bf_id` bigint(20),
			`cf_json` TEXT NOT NULL
		) $charset_collate;";
        $wpdb->query($sql);
    }
}
