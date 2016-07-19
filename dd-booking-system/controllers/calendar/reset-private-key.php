<?php
global $wpdb;
if(!empty($_GET['id'])){
    
    $random_string = wpbs_generateRandomString(32);
    
    $wpdb->update( $wpdb->prefix.'bs_calendars', array('calendarHash' => $random_string), array('calendarID' => $_GET['id']) );

    wp_redirect(admin_url('admin.php?page=wp-booking-system-ical&save=ok'));     

    die();
}

?>