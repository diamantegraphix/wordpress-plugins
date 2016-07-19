<?php
global $wpdb;


$legendId = $_GET['legendID'];

$calendarLegend = json_decode(get_option('wpbs-default-legend'),true);



if($calendarLegend[$legendId]['sync'] == 'yes'){
    $calendarLegend[$legendId]['sync'] = 'no';
} else {
    $calendarLegend[$legendId]['sync'] = 'yes';
}

    
update_option('wpbs-default-legend',json_encode($calendarLegend));    



wp_redirect(admin_url('admin.php?page=wp-booking-system-default-legend&do=edit-legend'));
die();
?>     