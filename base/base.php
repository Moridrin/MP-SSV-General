<?php

use mp_ssv_general\base\BaseFunctions;
use mp_ssv_general\base\Database;
use mp_ssv_general\base\Message;
use mp_ssv_general\base\SSV_Global;
use mp_ssv_general\base\User;

if (!defined('ABSPATH')) {
    exit;
}

if (!session_id()) {
    session_start();
    if (!isset($_SESSION['SSV'])) {
        $_SESSION['SSV'] = [
            'errors' => [],
        ];
    }
}

if (!class_exists(BaseFunctions::class)) {
    require_once 'BaseFunctions.php';
}

if (!class_exists(SSV_Global::class)) {
    define('SSV_BASE_PATH', plugin_dir_path(__FILE__));
    define('SSV_BASE_URL', plugins_url() . '/' . plugin_basename(__DIR__));
    require_once 'SSV_Global.php';
}

if (!class_exists(User::class)) {
    require_once 'models/User.php';
}

if (!class_exists(Message::class)) {
    require_once 'models/Message.php';
}

if (!class_exists(Database::class)) {
    require_once 'models/Database.php';
}

if (!function_exists('mp_ssv_base_admin_enqueue_scripts')) {
    function mp_ssv_base_admin_enqueue_scripts()
    {
        wp_enqueue_script('select2', SSV_Global::URL . '/lib/js/select2.js', ['jquery']);
        wp_enqueue_script('select2-init', SSV_Global::URL . '/js/select2-init.js', ['jquery']);
        wp_enqueue_style('select2', SSV_Global::URL . '/lib/css/select2.css');

        wp_enqueue_script('mp-ssv-general-functions', SSV_Global::URL . '/js/general-functions.js', ['jquery']);
    }

    add_action('admin_enqueue_scripts', 'mp_ssv_base_admin_enqueue_scripts');
}
