<?php

if (!defined('ABSPATH')) {
    exit;
}

$currentDir = getcwd();
chdir(__DIR__ . '/../..');
define('SSV_BASE_ACTIVATOR_PLUGIN', getcwd() . DIRECTORY_SEPARATOR . basename(getcwd()) . '.php');
chdir($currentDir);
define('SSV_BASE_PATH', plugin_dir_path(__FILE__));
define('SSV_BASE_URL', plugins_url() . '/' . plugin_basename(__DIR__));

require_once 'BaseFunctions.php';
require_once 'SSV_Global.php';
require_once 'SSV_Themes.php';
require_once 'models/Model.php';
require_once 'models/User.php';
require_once 'models/Database.php';
require_once 'shortcodes/Post.php';
require_once 'shortcodes/ViewRight.php';
require_once 'shortcodes/NotFoundShortcode.php';
require_once 'Revisions/InstallRevision.php';

function ssv_wpautop($content)
{
    if (has_shortcode($content, 'timeline')) {
        return $content;
    } else {
        return wpautop($content);
    }
}

remove_filter('the_content', 'wpautop');
add_filter('the_content', 'ssv_wpautop');

function ssv_start_session()
{
    do_action('before_session_start');
    if (!session_id()) {
        session_start();
    }
    if (!isset($_SESSION['SSV'])) {
        $_SESSION['SSV'] = [
            'errors' => [],
        ];
    }
}
add_action('init', 'ssv_start_session', 1);