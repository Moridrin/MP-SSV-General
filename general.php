<?php
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('SSV_General')) {
    require_once 'models/custom-fields/Field.php';

    #region Register Scripts
    function mp_ssv_general_admin_scripts()
    {
        wp_enqueue_script('mp-ssv-input-field-selector', SSV_General::URL . '/js/mp-ssv-input-field-selector.js', array('jquery'));
        wp_localize_script(
            'mp-ssv-input-field-selector',
            'settings',
            array('custom_field_fields' => get_option(SSV_General::OPTION_CUSTOM_FIELD_FIELDS))
        );
        wp_enqueue_script('mp-ssv-sortable-tables', SSV_General::URL . '/js/mp-ssv-sortable-tables.js', array('jquery', 'jquery-ui-sortable'));
    }

    add_action('admin_enqueue_scripts', 'mp_ssv_general_admin_scripts');
    #endregion

    global $wpdb;
    define('SSV_GENERAL_PATH', plugin_dir_path(__FILE__));
    define('SSV_GENERAL_URL', plugins_url() . '/' . plugin_basename(__DIR__));
    define('SSV_GENERAL_BASE_URL', (is_ssl() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    require_once 'SSV_General.php';

    SSV_General::_init();
}
