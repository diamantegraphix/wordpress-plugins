<?php 
/* Plugin Name: Diamante Default Setup
 * Author: Diamante Design
 * Author URL: http://diamantedesign.solutions
 * Description: Diamante default setup for WordPress
 *     Add default users
 * Text Domain: dd-wp-default-setup
 */

function dd_set_defaults () {
	dd_add_users ($users);
}
register_activation_hook (__FILE__, 'dd_set_defaults');


// Add users from external file 
function dd_add_users () {
	date_default_timezone_set("America/New_York");
	
	// Create log file
	$logfile = fopen(dirname(__FILE__) . '/setupreport.txt', 'a');
	$log = "[" . date('Y-m-d H:i:s') . "]\r\n";
	
        // Check if users file exists
	if (file_exists (dirname(__FILE__) . '/dd-wp-users.php')) {
		require_once ('dd_wp_users.php');
		foreach ($dd_wp_users as $user) {
			$log .= "Calling addUser function for " . $user['user_login'] . "\r\n";
			// Call function to add user
			$log .= addUser ($user, $logfile);
		}
	} else { 
		// Log message if users file does not exist
		$log .= "Users file does not exist\r\n"; 
	}
	
	// Write log message to log file
	$log .= "\r\n";
	$log .= "\r\n";
	fwrite($logfile, $log);
	fclose($logfile);
}

// Add a user
function addUser ($userdata, $log) {

	// Check if user login name already exists, capture 
	$userid = username_exists ($userdata['user_login']);
	if ($userid) {
	
		// Add user ID to userdata array
		$userdata['ID'] = $userid;
		
		// Update user, log results
		$userid = wp_update_user ($userdata);
		
		// Log results of user update
		if (!is_wp_error($userid)) {
    			$log .= "User " . $userid . ": " . $userdata['user_login'] . " updated\r\n";
		} else {
			$error = $userid->get_error_message();
			$log .= "Error updating user " . $userdata['user_login'] . ": " . $error . "\r\n";
		}		
	} else {
	
		// Check if email exists for another user
		$emailexists = email_exists($userdata['user_email']);
		if ($emailexists) {
		
			// Log message that user cannot be added because email is already in use
			$log .= $userdata['user_login'] . " not added, " . $userdata['user_email'] . " is assigned to " . $exists->login . "\r\n";
		} else { 
		
			// Add user
			$userid = wp_insert_user ($userdata);
			
			// Log results of user add
			if (!is_wp_error($userid)) {
    				$log .= "User " . $userid . ": " . $userdata['user_login'] . " added\r\n";
			} else {
				$error = $userid->get_error_message();
				$log .= "Error adding user " . $userdata['user_login'] . ": " . $error . "\r\n";		
			}
		}
	}
	return $log;
	
}

/*
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
*/


