<?php
/**
 * Plugin Name: SSV Forms
 * Plugin URI: http://moridrin.com/ssv-forms
 * Description: This is a plugin to create forms with ease.
 * Version: 0.1.9
 * Author: Jeroen Berkvens
 * Author URI: http://nl.linkedin.com/in/jberkvens/
 * License: WTFPL
 * License URI: http://www.wtfpl.net/txt/copying/
 */

use mp_ssv_general\base\BaseFunctions;

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
    require_once 'forms/forms.php';
}
