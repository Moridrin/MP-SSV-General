<?php
use mp_ssv_general\SSV_General;
use mp_ssv_general\User;

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('mp_ssv_general\SSV_General')) {
    require_once 'models/custom-fields/Field.php';

    #region Register Scripts
    function mp_ssv_general_admin_scripts()
    {
        wp_enqueue_script('mp-ssv-general-functions', SSV_General::URL . '/js/mp-ssv-general-functions.js', array('jquery'));
        wp_enqueue_script('mp-ssv-sortable-tables', SSV_General::URL . '/js/mp-ssv-sortable-tables.js', array('jquery', 'jquery-ui-sortable'));
        if ($_GET["page"] == "ssv_settings") {
            wp_enqueue_script('mp-ssv-custom-field-creator', SSV_General::URL . '/js/mp-ssv-custom-field-creator.js', array('jquery'));
            wp_localize_script('mp-ssv-custom-field-creator', 'settings', array('roles' => json_encode(array_keys(get_editable_roles())),));
        } else {
            wp_enqueue_script('mp-ssv-custom-field-customizer', SSV_General::URL . '/js/mp-ssv-custom-field-customizer.js', array('jquery'));
            wp_localize_script(
                'mp-ssv-custom-field-customizer',
                'settings',
                array(
                    'roles'   => json_encode(array_keys(get_editable_roles())),
                    'columns' => User::getCurrent()->getMeta(SSV_General::USER_OPTION_CUSTOM_FIELD_FIELDS, array()),
                )
            );
        }
    }

    add_action('admin_enqueue_scripts', 'mp_ssv_general_admin_scripts');
    #endregion

    global $wpdb;
    define('SSV_GENERAL_PATH', plugin_dir_path(__FILE__));
    define('SSV_GENERAL_URL', plugins_url() . '/' . plugin_basename(__DIR__));
    define('SSV_GENERAL_BASE_URL', (is_ssl() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST']);
    define('SSV_GENERAL_CUSTOM_FORM_FIELDS_TABLE', $wpdb->prefix . "ssv_general_customized_form_fields");
    require_once 'SSV_General.php';

    SSV_General::_init();

    #region Register
    function mp_ssv_general_register_plugin()
    {
        /** @var wpdb $wpdb */
        global $wpdb;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $charset_collate = $wpdb->get_charset_collate();

        $table_name = SSV_General::CUSTOM_FIELDS_TABLE;
        $sql
                    = "
		CREATE TABLE IF NOT EXISTS $table_name (
			`ID` bigint(20) NOT NULL PRIMARY KEY,
			`name` VARCHAR(50) UNIQUE,
			`title` VARCHAR(50) NOT NULL,
			`json` TEXT NOT NULL,
			`shared` TINYINT NOT NULL DEFAULT 1
		) $charset_collate;";
        $wpdb->query($sql);

        $table_name = SSV_General::CUSTOM_FORM_FIELDS_TABLE;
        $sql
                    = "
		CREATE TABLE IF NOT EXISTS $table_name (
			`ID` bigint(20) NOT NULL PRIMARY KEY AUTO_INCREMENT,
			`postID` bigint(20) NOT NULL,
			`containerID` bigint(20) NOT NULL DEFAULT 0,
			`order` bigint(20) NOT NULL,
			`name` VARCHAR(50),
			`json` TEXT NOT NULL
		) $charset_collate;";
        $wpdb->query($sql);
        if (!empty($wpdb->last_error)) {
            SSV_General::var_export($wpdb->last_error, 1);
        }
    }
    #endregion
}
