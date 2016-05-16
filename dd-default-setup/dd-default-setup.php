<?php 
/* Plugin Name: Diamante Default Setup
 * Author: Diamante Design
 * Author URL: http://diamantedesign.solutions
 * Description: Diamante default setup for WordPress
 *     Add default users
 * Text Domain: dd-wp-default-setup
 * Uses: $dd_settings file - contains settings data for setup
 */


function dd_set_defaults () {

	$dd_settings = dirname(__FILE__) . "/dd-wp-defaults.php";
	$logname = dirname(__FILE__) . "/setupreport.txt";

	// Start log record
	date_default_timezone_set("America/New_York");
	$log = "[" . date('Y-m-d H:i:s') . "]\r\n";

	// Check if settings file exists
	if (!file_exists ($dd_settings)) {
	
		// Log message if setup file does not exist
		$log .= "Users file does not exist\r\n"; 
	} else {
		// Get settings data from file
		require_once ($dd_settings);
		
		// Call setup functions
		$log .= dd_register_users ($dd_users);
		$log .= dd_delete_mojo_plugins ($dd_delete_plugins);
	}
	
	// Write log message to log file
	$logfile = fopen($logname, 'a');
	$log .= "\r\n";
	fwrite($logfile, $log);
	fclose($logfile);
}
register_activation_hook (__FILE__, 'dd_set_defaults');

// Add users from external file 
function dd_register_users ($users) {
	// Add each user in file
	foreach ($users as $user) {
		// Call function to add user
		$registeruserslog .= dd_add_user ($user);
	}
	return $registeruserslog;
}

// Add a user
function dd_add_user ($userdata) {

	// Check if user login name already exists, capture 
	$userid = username_exists ($userdata['user_login']);
	if ($userid) {
	
		// Add user ID to userdata array
		$userdata['ID'] = $userid;
		
		// Update user, log results
		$userid = wp_update_user ($userdata);
		
		// Log results of user update
		if (!is_wp_error($userid)) {
    			$userlog .= "User " . $userid . ": " . $userdata['user_login'] . " updated\r\n";
		} else {
			$error = $userid->get_error_message();
			$userlog .= "Error updating user " . $userdata['user_login'] . ": " . $error . "\r\n";
		}		
	} else {
	
		// Check if email exists for another user
		$emailexists = email_exists($userdata['user_email']);
		if ($emailexists) {
			$userexists = get_userdata($emailexists);

			// Log message that user cannot be added because email is already in use
			$userlog .= $userdata['user_login'] . " not added, " . $userdata['user_email'] . " is assigned to " . $userexists->user_login . "\r\n";
		} else { 
		
			// Add user
			$userid = wp_insert_user ($userdata);
			
			// Log results of user add
			if (!is_wp_error($userid)) {
    				$userlog .= "User " . $userid . ": " . $userdata['user_login'] . " added\r\n";
			} else {
				$error = $userid->get_error_message();
				$userlog .= "Error adding user " . $userdata['user_login'] . ": " . $error . "\r\n";		
			}
		}
	}
	return $userlog;
}


function delTree($dir) {
	$files = array_diff(scandir($dir), array('.','..'));
	foreach ($files as $file) {
		(is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
    	}
	return rmdir($dir);
} 



// Delete standard Mojo Marketplace plugins
function dd_delete_mojo_plugins ($plugins) {
	$plugins_dir = get_home_path() . "wp-content/plugins";
	foreach ($plugins as $plugin) {
		error_log ("Plugin: $plugin");
		$plugindata = get_plugin_data("$plugins_dir/$plugin");
		
		if (file_exists("$plugins_dir/$plugin") == true) {
			if (is_plugin_active($plugin)) {
				$error = deactivate_plugins($plugin);
				if (trim($error) == false) {
					$pluginlog .= $plugindata['Name'] . " deactivated\r\n";
				}
			}
			
			
			$plugin_pkg = strtok($plugin, '/');

			error_log ("Plugin pkg: $plugins_dir/$plugin_pkg");
  			
			if (delTree("$plugins_dir/$plugin_pkg")) {
				$pluginlog .= $plugindata['Name'] . " deleted\r\n";
			}
  		} else {
  			$pluginlog .= $plugin . " not installed\r\n";
  		}
	}
	$pluginlog .= "\r\n";
	return $pluginlog;
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


