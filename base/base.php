<?php

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

define('SSV_BASE_PATH', plugin_dir_path(__FILE__));
define('SSV_BASE_URL', plugins_url() . '/' . plugin_basename(__DIR__));

require_once 'BaseFunctions.php';
require_once 'SSV_Global.php';
require_once 'models/Model.php';
require_once 'models/User.php';
require_once 'models/Database.php';
require_once 'shortcodes/PostContent.php';
