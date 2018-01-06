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
    require_once 'BaseFunctions.php';
}

if (!class_exists(User::class)) {
    require_once 'models/User.php';
}

if (!class_exists(Message::class)) {
    require_once 'models/Message.php';
}

function mp_ssv_base_admin_enquire_scripts()
{
    wp_enqueue_script('select2', BaseFunctions::URL . '/lib/js/select2.js', ['jquery']);
    wp_enqueue_script('select2-init', BaseFunctions::URL . '/js/select2-init.js', ['jquery']);
    wp_enqueue_style('select2', BaseFunctions::URL . '/lib/css/select2.css');

    wp_enqueue_script('mp-ssv-general-functions', BaseFunctions::URL . '/js/mp-ssv-general-functions.js', ['jquery']);
}

add_action('admin_enqueue_scripts', 'mp_ssv_base_admin_enquire_scripts');
