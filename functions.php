<?php
function mp_ssv_get_local_time_string($time_string) {
	global $wpdb;
	$time = DateTime::createFromFormat('H:i', $time_string);
	$table_name = $wpdb->prefix."mp_ssv_event_timezone";
	$mp_ssv_event_time_zone = get_option('mp_ssv_event_time_zone');
	$result = $wpdb->get_row("SELECT * FROM $table_name WHERE `id` = $mp_ssv_event_time_zone");
	$gmt_adjustment = $result->gmt_adjustment;
	if ($gmt_adjustment[0] == '+') {
		$time->sub(new DateInterval('PT'.$gmt_adjustment[1].$gmt_adjustment[2].'H'.$gmt_adjustment[4].$gmt_adjustment[5].'M'));
	} else {
		$time->add(new DateInterval('PT'.$gmt_adjustment[1].$gmt_adjustment[2].'H'.$gmt_adjustment[4].$gmt_adjustment[5].'M'));
	}
	return $time->format('H:i');
}

function mp_ssv_redirect($location) {
	$redirect_script = '<script type="text/javascript">';
	$redirect_script .= 'window.location = "' . $location . '"';
	$redirect_script .= '</script>';
	echo $redirect_script;
}

function mp_ssv_get_user_name($user_ID) {
	$user = get_user_by( 'ID', $user_ID );
	return $user->display_name;
}
?>