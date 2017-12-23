<?php

use mp_ssv_general\SSV_General;

if (!defined('ABSPATH')) {
    exit;
}

function ssv_network_forms_menu()
{
    add_menu_page('SSV Forms', 'SSV Forms', 'edit_posts', 'ssv_forms', 'ssv_shared_base_form_fields_manager');
}

function ssv_shared_base_form_fields_manager()
{
    ?>
    <div class="wrap">
        <?php
        if (SSV_General::isValidPOST(SSV_General::OPTIONS_ADMIN_REFERER)) {
            require_once 'base-form-fields-save.php';
        }
        require_once 'base-form-fields.php';
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
                Help <img src="<?= SSV_General::URL ?>/images/link-new-tab-small.png" width="14" style="vertical-align:middle" height="14">
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
