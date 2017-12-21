<?php

use mp_ssv_general\SSV_General;

if (!defined('ABSPATH')) {
    exit;
}

function ssv_add_ssv_menu()
{
    add_menu_page('SSV Options', 'SSV Options', 'edit_posts', 'ssv_settings', 'ssv_settings_page');
    add_submenu_page('ssv_settings', 'General', 'General', 'edit_posts', 'ssv_settings');
}

function ssv_settings_page()
{

    $active_tab = "general";
    if (isset($_GET['tab'])) {
        $active_tab = $_GET['tab'];
    }
    ?>
    <div class="wrap">
        <h1>Users Options</h1>
        <h2 class="nav-tab-wrapper">
            <a href="?page=<?= $_GET['page'] ?>&tab=general" class="nav-tab <?= $active_tab == 'general' ? 'nav-tab-active' : '' ?>">General</a>
            <a href="?page=<?= $_GET['page'] ?>&tab=site-specific-fields" class="nav-tab <?= $active_tab == 'site-specific-fields' ? 'nav-tab-active' : '' ?>">Site Specific Fields</a>
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
                    require_once "site-specific-fields.php";
                    break;
            }
        }
        ?>
    </div>
    <?php
}

add_action('admin_menu', 'ssv_add_ssv_menu', 9);

function ssv_add_ssv_network_admin_menu()
{
    add_menu_page('SSV Options', 'SSV Options', 'edit_posts', 'ssv_settings', 'ssv_settings_page_network_admin');
    add_submenu_page('ssv_settings', 'General', 'General', 'edit_posts', 'ssv_settings');
}

function ssv_settings_page_network_admin()
{

}

add_action('network_admin_menu', 'ssv_add_ssv_network_admin_menu', 9);
