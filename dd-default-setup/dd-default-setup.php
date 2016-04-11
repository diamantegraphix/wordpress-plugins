<?php 
/* Plugin Name: Diamante Default Setup
 * Author: Diamante Design
 * Author URL: http://diamantedesign.solutions
 * Description: Diamante default setup for WordPress
 *     Add default users
 * Text Domain: dd-diamante-default-setup
 */

function dd_user_setup () {
	date_default_timezone_set("America/New_York");
	$log = "[" . date('Y-m-d H:i:s') . "]\r\n";
	if (file_exists (dirname(__FILE__) . '/dd_wp_users.php')) {
		require_once ('dd_wp_users.php');
		foreach ($dd_wp_users as $user) {
			$add = addUser ($user);
			switch ($add) {
				case 'added':
					$log .= $user['user_login'] . " added\r\n";
					break;
				case 'updated':
					$log .= $user['user_login'] . " updated\r\n";
					break;
				default:
					$exists = get_user_by('id', $add);
					$log .= $user['user_login'] . " not added, " . $user['user_email'] . " is assigned to " . $exists->user_login . "\r\n";
					break;



			}
		}
	} else {
		$log .= "Users file does not exist\r\n"; 
	}
	$log .= "\r\n";
	//$log .= "\r\n";
	$logfile = fopen(dirname(__FILE__) . '/setupreport.txt', 'a');
	fwrite($logfile, $log);
	fclose($logfile);
}

function addUser ($newuser) {
	$userid = username_exists ($newuser['user_login']);
	if (!$userid) {
		$emailexists = email_exists($newuser['user_email']);
		if (!$emailexists) {
			$userid = wp_insert_user ($newuser);
			wp_new_user_notification($userid, NULL, 'both');
			return 'added';	
		} else {
			return $emailexists; // email exists, cannot add new user  
		}
	} 

/* update when user already exists
else {
		$user['ID'] = $userid;
		$user['pass'] = wp_hash_password ($newuser['pass']);
		$userid = wp_update_user ($newuser);
		return 'updated';
	} */
}

function dd_set_defaults () {
	dd_add_users ($dd_wp_users);

}
register_activation_hook (__FILE__, 'dd_set_defaults');

function dd_show_users_admin_notice() { 
	global $pagenow;
	if ( $pagenow == 'plugins.php' ) { 
		global $current_user ;
		    $user_id = $current_user->ID;
		if (!get_user_meta($user_id, 'dd_users_notice')) {
			echo '<div id="message" class="error notice is-dismissible">';
			echo '<p><strong>Diamante Design Custom Settings</strong></p>';
			echo '<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>';
			echo '</div>';
			add_user_meta($user_id, 'dd_users_notice', 'true', true);
		}
	}
}
add_action('admin_notices', 'dd_show_users_admin_notice', 99);

function dd_remove_ignore_notices () {
	$users = get_users();
	foreach ($users as $user) {
		$user_id = $user->ID;	
		delete_user_meta($user_id, 'dd_users_notice');
	}
}

register_deactivation_hook (__FILE__, 'dd_remove_ignore_notices');









