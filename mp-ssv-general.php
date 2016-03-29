<?php
/**
* Plugin Name: SSV General
* Plugin URI: http://studentensurvival.com/plugins/mp-ssv-google-apps
* Description: SSV General is the parent plugin for all SSV plugins (they all require this plugin).
* Version: 1.0
* Author: Jeroen Berkvens
* Author URI: http://nl.linkedin.com/in/jberkvens/
* License: WTFPL
* License URI: http://www.wtfpl.net/txt/copying/
*/

include_once "options/options.php";

function mp_ssv_unregister_mp_ssv_general(){
	if (!is_plugin_active('MP-SSV-Frontend-Members/mp-ssv-frontend-members.php')) {
		wp_die('Sorry, but this plugin is required by all SSV plugins. Deactivate all SSV plugins before deactivating this plugin. <br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>');
	}
}
register_deactivation_hook( __FILE__, 'mp_ssv_unregister_mp_ssv_general' );
?>