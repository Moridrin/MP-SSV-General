<?php
if (!defined('ABSPATH')) {
    exit;
}
if (!function_exists('ssv_initialize_general')) {
    function ssv_initialize_general()
    {
        require_once "functions.php";
        require_once "options/options.php";
        require_once "models/FrontendMember.php";
    }

    ssv_initialize_general();
}
