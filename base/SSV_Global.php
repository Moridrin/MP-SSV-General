<?php

namespace mp_ssv_general\base;

if (!defined('ABSPATH')) {
    exit;
}

abstract class SSV_Global
{
    const PATH = SSV_BASE_PATH;
    const URL = SSV_BASE_URL;

    const HOOK_USER_PROFILE_URL = 'ssv_general__hook_profile_url';
    const HOOK_GENERAL_OPTIONS_PAGE_CONTENT = 'ssv_general__hook_general_options_page_content';
    const HOOK_RESET_OPTIONS = 'ssv_general__hook_reset_options';

    const HOOK_USERS_SAVE_MEMBER = 'ssv_users__hook_save_member';
    const HOOK_USERS_NEW_EVENT = 'ssv_events__hook_new_event';
    const HOOK_EVENTS_NEW_REGISTRATION = 'ssv_events__hook_new_registration';

    const USER_OPTION_CUSTOM_FIELD_FIELDS = 'ssv_general__custom_field_fields';
    const OPTIONS_ADMIN_REFERER = 'ssv_general__options_admin_referer';
    const BASE_FORM_FIELDS_BULK_ACTIONS = 'ssv_general__base_form_fields_bulk_actions';

    private static $database = null;

    public static function getLoginURL()
    {
        include_once(ABSPATH . 'wp-admin/includes/plugin.php');
        if (is_plugin_active('ssv-users/ssv-users.php')) {
            $loginPages = SSV_Users::getPagesWithTag(SSV_Users::TAG_LOGIN_FIELDS);
            if (count($loginPages) > 0) {
                return add_query_arg('redirect_to', get_permalink(), get_permalink($loginPages[0]));
            }
        }
        return site_url() . '/wp-login.php?redirect_to=' . site_url();
    }

    public static function getChangePasswordURL()
    {
        include_once(ABSPATH . 'wp-admin/includes/plugin.php');
        if (is_plugin_active('ssv-users/ssv-users.php')) {
            $changePasswordPages = SSV_Users::getPagesWithTag(SSV_Users::TAG_CHANGE_PASSWORD);
            if (count($changePasswordPages) > 0) {
                return add_query_arg('redirect_to', get_site_url(), get_permalink($changePasswordPages[0]));
            }
        }
        return '';
    }

    public static function enqueueAdminScripts()
    {
        wp_enqueue_script('ssv_global_datetimepicker', SSV_Global::URL . '/lib/js/jquery.datetimepicker.full.js', 'jquery-ui-datepicker');
        wp_enqueue_script('ssv_global_datetimepicker_init', SSV_Global::URL . '/js/datetimepicker-init.js', 'ssv_global_datetimepicker');
        wp_enqueue_style('ssv_global_datetimepicker_css', SSV_Global::URL . '/lib/css/jquery.datetimepicker.css');
    }

    public static function enqueueScripts()
    {
        wp_enqueue_script('ssv_global_datetimepicker', SSV_Global::URL . '/lib/js/jquery.datetimepicker.full.js', ['jquery', 'jquery-ui-datepicker']);
        wp_enqueue_script('ssv_global_datetimepicker_init', SSV_Global::URL . '/js/datetimepicker-init.js', ['ssv_global_datetimepicker', 'jquery']);
        wp_enqueue_style('ssv_global_datetimepicker_css', SSV_Global::URL . '/lib/css/jquery.datetimepicker.css');
    }

    public static function runFunctionOnAllSites(callable $callable, ...$args)
    {
        if (is_array($args) && array_key_exists('callables', $args)) {
            foreach ($args['callables'] as $callable) {
                $args[] = call_user_func($callable);
            }
            unset($args['callables']);
        }
        $database = SSV_Global::getDatabase();
        if (is_multisite()) {
            $blogIds = $database->get_col("SELECT blog_id FROM " . $database->getBlogsTable());
        } else {
            $blogIds = [get_current_blog_id()];
        }
        foreach ($blogIds as $blogId) {
            switch_to_blog($blogId);
            call_user_func($callable, ...$args);
            restore_current_blog();
        }
    }

    public static function addSettingsPage(string $mainTitle, string $subTitle, string $capability = 'edit_posts', callable $function)
    {
        if (empty($GLOBALS['admin_page_hooks']['ssv_settings'])) {
            add_menu_page($mainTitle, $mainTitle, $capability, 'ssv_settings');
            add_submenu_page('ssv_settings', $subTitle, $subTitle, $capability, 'ssv_settings', $function);
        } else {
            global $menu;
            foreach ($menu as &$menuItem) {
                if ($menuItem[2] === 'ssv_settings' && $menuItem[0] !== 'SSV Settings') {
                    $menuItem[0] = 'SSV Settings';
                }
            }
            add_submenu_page('ssv_settings', $subTitle, $subTitle, $capability, 'ssv_' . BaseFunctions::toSnakeCase($subTitle), $function);
        }
    }

    public static function getErrors($clear = true)
    {
        ob_start();
        foreach ($_SESSION['SSV']['errors'] as $error) {
            ?>
            <div class="notice notice-error">
                <p><?= $error ?></p>
            </div>
            <?php
        }
        if ($clear) {
            $_SESSION['SSV']['errors'] = [];
        }
        return ob_get_clean();
    }

    public static function showErrors()
    {
        if (!defined('DOING_AJAX') || !DOING_AJAX) {
            ?>
            <div id="messagesContainer" class="notice">
                <?= self::getErrors() ?>
            </div>
            <script>
                document.addEventListener("DOMContentLoaded", function (event) {
                    document.getElementById('messagesContainer').setAttribute('class', '');
                });
            </script>
            <?php
        }
    }

    public static function getDatabase(): Database
    {
        self::$database = new Database();
        return self::$database;
    }
}

add_action('admin_enqueue_scripts', [SSV_Global::class, 'enqueueAdminScripts']);
add_action('wp_enqueue_scripts', [SSV_Global::class, 'enqueueScripts']);
add_action('shutdown', [SSV_Global::class, 'showErrors']);
