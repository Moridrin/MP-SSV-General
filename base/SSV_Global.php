<?php

namespace mp_ssv_general\base;

use mp_ssv_users\SSV_Users;

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
}
