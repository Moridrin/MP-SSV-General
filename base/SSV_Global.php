<?php

namespace mp_general\base;

if (!defined('ABSPATH')) {
    exit;
}

/** @noinspection PhpIncludeInspection */
require_once SSV_Global::PATH . 'templates/table.php';

abstract class SSV_Global
{
    const PATH          = SSV_BASE_PATH;
    const URL           = SSV_BASE_URL;
    const ADMIN_REFERER = 'ssv_global__admin_referer';

    public static function setup()
    {
        global $wpdb;
        $tableName = $wpdb->base_prefix . 'ssv_revisions';
        $wpdb->query("CREATE TABLE $tableName (plugin_file VARCHAR(255), revision int, CONSTRAINT table_name_plugin_file_revision_pk PRIMARY KEY (plugin_file, revision));");
    }

    public static function revisionInstalled($pluginFile, $revision)
    {
        global $wpdb;
        $tableName = $wpdb->base_prefix . 'ssv_revisions';
        $wpdb->insert($tableName, ['plugin_file' => $pluginFile, 'revision' => $revision]);
    }

    public static function enqueueAdminScripts()
    {
        // General Functions
        wp_enqueue_script('mp-ssv-general-functions', self::URL . '/js/general-functions.js', ['jquery']);

        // DateTime Picker
        wp_enqueue_script('ssv_global_datetimepicker', self::URL . '/lib/js/jquery.datetimepicker.full.js', 'jquery-ui-datepicker');
        wp_enqueue_script('ssv_global_datetimepicker_init', self::URL . '/js/datetimepicker-init.js', 'ssv_global_datetimepicker');
        wp_enqueue_style('ssv_global_datetimepicker_css', self::URL . '/lib/css/jquery.datetimepicker.css');

        // Select2
        wp_enqueue_script('select2', self::URL . '/lib/js/select2.js', ['jquery']);
        wp_enqueue_script('select2-init', self::URL . '/js/select2-init.js', ['jquery']);
        wp_enqueue_style('select2', self::URL . '/lib/css/select2.css');
    }

    public static function enqueueScripts()
    {
        wp_enqueue_script('mp-ssv-general-functions', self::URL . '/js/general-functions.js', ['jquery']);
        wp_enqueue_script('ssv_global_datetimepicker', self::URL . '/lib/js/jquery.datetimepicker.full.js', ['jquery', 'jquery-ui-datepicker']);
        wp_enqueue_script('ssv_global_datetimepicker_init', self::URL . '/js/datetimepicker-init.js', ['ssv_global_datetimepicker', 'jquery']);
        wp_enqueue_style('ssv_global_datetimepicker_css', self::URL . '/lib/css/jquery.datetimepicker.css');
    }

    public static function runFunctionOnAllSites(callable $callable, ...$args)
    {
        if (is_array($args) && array_key_exists('callables', $args)) {
            foreach ($args['callables'] as $callable) {
                $args[] = call_user_func($callable);
            }
            unset($args['callables']);
        }
        if (is_multisite()) {
            global $wpdb;
            $blogsTable = $wpdb->blogs;
            $blogIds    = $wpdb->get_col("SELECT blog_id FROM $blogsTable WHERE archived = '0' AND spam = '0' AND deleted = '0'");
        } else {
            $blogIds = [get_current_blog_id()];
        }
        foreach ($blogIds as $blogId) {
            switch_to_blog($blogId);
            call_user_func($callable, ...$args);
            restore_current_blog();
        }
    }

    public static function addMenuItem(string $pageTitle, string $menuTitle, string $menuSlug, $function = '', string $capability = 'manage_options')
    {
        if (!array_key_exists('ssv_settings', $GLOBALS['admin_page_hooks'])) {
            add_menu_page('SSV Settings', 'SSV Settings', 'edit_posts', 'ssv_settings', '', 'dashicons-feedback');
            add_submenu_page('ssv_settings', $pageTitle, $menuTitle, $capability, 'ssv_settings', $function);
        } else {
            add_submenu_page('ssv_settings', $pageTitle, $menuTitle, $capability, $menuSlug, $function);
        }
    }

    public static function addError(string $message): void
    {
        $_SESSION['SSV']['errors'][] = $message;
    }

    public static function hasError(): bool
    {
        return !empty($_SESSION['SSV']['errors']);
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
        if (is_admin() && (!defined('DOING_AJAX') || !DOING_AJAX)) {
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
//        } else {
//            wp_die(json_encode(['errors' => SSV_Global::getErrors() ?: false]));
        }
    }

    public static function showAjaxErrors()
    {
        return function ($outputString) {
            if (!self::hasError()) {
                echo $outputString;
                die();
            }
            $output = json_decode($outputString, true);
            if (!is_array($output)) {
                SSV_Global::addError('Task returned with the following unexpected output: ' . var_export($outputString, true));
                $output = [];
            }
            $output['errors']  = SSV_Global::getErrors() ?: false;
            $output['success'] = false;
            echo json_encode($output);
            die();
        };
    }
}

register_activation_hook(SSV_BASE_ACTIVATOR_PLUGIN, [SSV_Global::class, 'setup']);
add_action('admin_enqueue_scripts', [SSV_Global::class, 'enqueueAdminScripts']);
add_action('wp_enqueue_scripts', [SSV_Global::class, 'enqueueScripts']);
add_action('shutdown', [SSV_Global::class, 'showErrors']);
add_filter('wp_die_ajax_handler', [SSV_Global::class, 'showAjaxErrors']);
