<?php

use mp_general\base\BaseFunctions;

if (!defined('ABSPATH')) {
    exit;
}

if (WP_DEBUG) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
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
    require_once 'base/base.php';
    require_once 'exceptions/exceptions.php';
}
