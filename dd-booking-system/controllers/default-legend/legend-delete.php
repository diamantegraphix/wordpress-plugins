<?php 
global $wpdb;

$calendarId = $_GET['id'];
$legendId = $_GET['legendID'];

if($legendId == 'default') die();

$calendarLegend = json_decode(get_option('wpbs-default-legend'),true);
    
unset($calendarLegend[$legendId]);

update_option('wpbs-default-legend',json_encode($calendarLegend));     

wp_redirect(admin_url('admin.php?page=wp-booking-system-default-legend&do=edit-legend'));
die();
?>     