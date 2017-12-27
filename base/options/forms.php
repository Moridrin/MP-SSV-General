<?php

use mp_ssv_general\SSV_Base;

if (!defined('ABSPATH')) {
    exit;
}

require_once 'base-form-fields-functions.php';

function ssv_network_forms_menu()
{
    add_menu_page('SSV Forms', 'SSV Forms', 'edit_posts', 'ssv_forms', 'ssv_shared_base_form_fields_manager');
}

function ssv_shared_base_form_fields_manager()
{
    ?>
    <div class="wrap">
        <?php
        if (SSV_Base::isValidPOST(SSV_Base::BASE_FORM_FIELDS_BULK_ACTIONS)) {
            mp_ssv_general_delete_selected_base_fields();
        }
        require_once 'base-form-fields-table.php';
        ?>
    </div>
    <?php
}

add_action('network_admin_menu', 'ssv_network_forms_menu', 9);

function ssv_site_specific_forms_menu()
{
    add_menu_page('SSV Forms', 'SSV Forms', 'edit_posts', 'ssv_forms', 'ssv_site_specific_forms_manager');
    add_submenu_page('ssv_forms', 'Form Builder', 'Form Builder', 'edit_posts', 'ssv_settings');
}

function ssv_site_specific_forms_manager()
{

    $active_tab = "site-specific-fields";
    if (isset($_GET['tab'])) {
        $active_tab = $_GET['tab'];
    }
    ?>
    <div class="wrap">
        <h2 class="nav-tab-wrapper">
            <a href="?page=<?= $_GET['page'] ?>&tab=site-specific-fields" class="nav-tab <?= $active_tab == 'site-specific-fields' ? 'nav-tab-active' : '' ?>">Form Fields</a>
            <a href="?page=<?= $_GET['page'] ?>&tab=general" class="nav-tab <?= $active_tab == 'general' ? 'nav-tab-active' : '' ?>">General</a>
            <a href="http://bosso.nl/plugins/ssv-general/" target="_blank" class="nav-tab">
                Help <img src="<?= SSV_Base::URL ?>/images/link-new-tab-small.png" width="14" style="vertical-align:middle" height="14">
            </a>
        </h2>
        <?php
        if (is_multisite()) {
            switch ($active_tab) {
                default:
                case "general":
                    require_once "general.php";
                    break;
                case "site-specific-fields":
                    require_once "site-specific-form-fields.php";
                    break;
            }
        }
        ?>
    </div>
    <?php
}

add_action('admin_menu', 'ssv_site_specific_forms_menu', 9);

function mp_ssv_ajax_general_save_base_field()
{
    if (!isset($_POST['title']) || !isset($_POST['name']) || !isset($_POST['inputType']) || !isset($_POST['value'])) {
        throw new HttpInvalidParamException('The "title", "name", "inputType" and/or "value" parameter(s) isn\'t provided.');
    }
    /** @var wpdb $wpdb */
    global $wpdb;
    $baseTable = SSV_Base::SHARED_BASE_FIELDS_TABLE;
    $wpdb->replace(
        $baseTable,
        [
            $_POST['title'],
            $_POST['name'],
            $_POST['inputType'],
            $_POST['value'],
        ]
    );
    wp_die();
}

add_action('wp_ajax_mp_ssv_general_save_base_field', 'mp_ssv_ajax_general_save_base_field');
