<?php

namespace mp_ssv_global;

use mp_ssv_users\SSV_Users;
use wpdb;

if (!defined('ABSPATH')) {
    exit;
}

class SSV_Global
{
    const PATH = SSV_GENERAL_PATH;
    const URL = SSV_GENERAL_URL;

    const SHARED_BASE_FIELDS_TABLE = SSV_GENERAL_SHARED_BASE_FIELDS_TABLE;
    const SITE_SPECIFIC_BASE_FIELDS_TABLE = SSV_GENERAL_SITE_SPECIFIC_BASE_FIELDS_TABLE;
    const CUSTOMIZED_FIELDS_TABLE = SSV_GENERAL_CUSTOMIZED_FIELDS;

    const HOOK_USER_PROFILE_URL = 'ssv_general__hook_profile_url';
    const HOOK_GENERAL_OPTIONS_PAGE_CONTENT = 'ssv_general__hook_general_options_page_content';
    const HOOK_RESET_OPTIONS = 'ssv_general__hook_reset_options';

    const HOOK_USERS_SAVE_MEMBER = 'ssv_users__hook_save_member';
    const HOOK_USERS_NEW_EVENT = 'ssv_events__hook_new_event';
    const HOOK_EVENTS_NEW_REGISTRATION = 'ssv_events__hook_new_registration';

    const USER_OPTION_CUSTOM_FIELD_FIELDS = 'ssv_general__custom_field_fields';
    const OPTIONS_ADMIN_REFERER = 'ssv_general__options_admin_referer';
    const BASE_FORM_FIELDS_BULK_ACTIONS = 'ssv_general__base_form_fields_bulk_actions';

    public static function getLoginURL()
    {
        include_once(ABSPATH . 'wp-admin/includes/plugin.php');
        if (is_plugin_active('ssv-users/ssv-users.php')) {
            $loginPages = SSV_Users::getPagesWithTag(SSV_Users::TAG_LOGIN_FIELDS);
            if (count($loginPages) > 0) {
                return add_query_arg('redirect_to', get_permalink(), get_permalink($loginPages[0]));
            }
        }
        return site_url() . '/wp-login.php?redirect_to=' . site_url();
    }

    public static function getChangePasswordURL()
    {
        include_once(ABSPATH . 'wp-admin/includes/plugin.php');
        if (is_plugin_active('ssv-users/ssv-users.php')) {
            $changePasswordPages = SSV_Users::getPagesWithTag(SSV_Users::TAG_CHANGE_PASSWORD);
            if (count($changePasswordPages) > 0) {
                return add_query_arg('redirect_to', get_site_url(), get_permalink($changePasswordPages[0]));
            }
        }
        return '';
    }

    public static function CLEAN_INSTALL()
    {
        /** @var wpdb $wpdb */
        global $wpdb;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $tableName = SSV_Global::SHARED_BASE_FIELDS_TABLE;
        $wpdb->query("DROP TABLE $tableName;");
        $tableName = SSV_Global::CUSTOMIZED_FIELDS_TABLE;
        $wpdb->query("DROP TABLE $tableName;");
        mp_ssv_general_register_plugin();
    }
}

function mp_ssv_general_admin_scripts()
{
    if (isset($_GET["page"]) && $_GET["page"] == "ssv_forms") {
        wp_enqueue_script('mp-ssv-base-fields-manager', SSV_Global::URL . '/js/mp-ssv-base-fields-manager.js', ['jquery']);
        wp_enqueue_script('mp-ssv-fields-management', SSV_Global::URL . '/js/mp-ssv-fields-management.js', ['jquery']);
        wp_localize_script(
            'mp-ssv-fields-management',
            'urls',
            [
                'plugins'  => plugins_url(),
                'ajax'    => admin_url('admin-ajax.php'),
                'base'     => get_home_url(),
                'basePath' => ABSPATH,
            ]
        );
    } else {
        wp_enqueue_script('mp-ssv-base-field-customizer', SSV_Global::URL . '/js/mp-ssv-base-field-customizer.js', ['jquery']);
        wp_enqueue_script('mp-ssv-fields-management', SSV_Global::URL . '/js/mp-ssv-fields-management.js', ['jquery']);
    }
    wp_localize_script(
        'mp-ssv-fields-management',
        'settings',
        ['roles'   => json_encode(array_keys(get_editable_roles()))]
    );
}

add_action('admin_enqueue_scripts', 'mp_ssv_general_admin_scripts');

function mp_ssv_register_forms_plugin()
{
    /** @var wpdb $wpdb */
    global $wpdb;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    $charset_collate = $wpdb->get_charset_collate();

    $tableName = SSV_Global::SHARED_BASE_FIELDS_TABLE;
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

    $tableName = SSV_Global::SITE_SPECIFIC_BASE_FIELDS_TABLE;
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

    $tableName = SSV_Global::CUSTOMIZED_FIELDS_TABLE;
    $sql
               = "
		CREATE TABLE IF NOT EXISTS $tableName (
			`cf_id` bigint(20) NOT NULL PRIMARY KEY AUTO_INCREMENT,
			`cf_bf_id` bigint(20),
			`cf_json` TEXT NOT NULL
		) $charset_collate;";
    $wpdb->query($sql);
}
