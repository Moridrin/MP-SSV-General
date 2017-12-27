<?php

use mp_ssv_general\base\BaseFunctions;
use mp_ssv_general\base\Message;
use mp_ssv_general\base\User;

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists(BaseFunctions::class)) {
    define('SSV_BASE_FUNCTIONS_PATH', plugin_dir_path(__FILE__));
    define('SSV_BASE_FUNCTIONS_URL', plugins_url() . '/' . plugin_basename(__DIR__));
    require_once 'models/BaseFunctions.php';
}

if (!class_exists(User::class)) {
    require_once 'models/User.php';
}

if (!class_exists(Message::class)) {
    require_once 'models/Message.php';
}

function mp_ssv_base_admin_enquire_scripts()
{
    wp_enqueue_script('mp-ssv-general-functions', BaseFunctions::URL . '/js/mp-ssv-general-functions.js', ['jquery']);
}

add_action('admin_enqueue_scripts', 'mp_ssv_base_admin_enquire_scripts');
