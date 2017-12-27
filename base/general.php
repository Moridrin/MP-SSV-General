<?php

use mp_ssv_general\SSV_Base;
use mp_ssv_general\User;
use mp_ssv_global\SSV_Global;

if (!defined('ABSPATH')) {
    exit;
}

require_once 'functions.php';

if (!class_exists('mp_ssv_general\SSV_Base')) {
    define('SSV_GENERAL_PATH', plugin_dir_path(__FILE__));
    define('SSV_GENERAL_URL', plugins_url() . '/' . plugin_basename(__DIR__));
    global $wpdb;
    define('SSV_GENERAL_SHARED_BASE_FIELDS_TABLE', $wpdb->base_prefix . "ssv_general_base_fields");
    define('SSV_GENERAL_SITE_SPECIFIC_BASE_FIELDS_TABLE', $wpdb->prefix . "ssv_general_base_fields");
    define('SSV_GENERAL_CUSTOMIZED_FIELDS', $wpdb->prefix . "ssv_general_customized_fields");

    require_once 'SSV_Base.php';
    require_once 'SSV_Global.php';
    require_once 'models/User.php';
    require_once 'models/Message.php';
}
