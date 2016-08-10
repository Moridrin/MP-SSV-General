<?php
if (!function_exists('mp_ssv_initialize_general')) {
    function mp_ssv_initialize_general()
    {
        require_once "functions.php";
        require_once "options/options.php";
        require_once "models/FrontendMember.php";
    }
}
mp_ssv_initialize_general();
