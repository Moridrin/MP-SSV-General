<?php
if (!defined('ABSPATH')) {
	exit;
}
function ssv_add_ssv_menu()
{
	add_menu_page('MP SSV Options', 'SSV Options', 'manage_options', 'ssv_settings', 'ssv_settings_page');
	add_submenu_page('ssv_settings', 'General', 'General', 'manage_options', 'ssv_settings');
}

function ssv_settings_page()
{
	?>
	<div class="wrap">
		<h1>SSV General Options</h1>
	</div>
	<?php
}

add_action('admin_menu', 'ssv_add_ssv_menu', 9);
?>