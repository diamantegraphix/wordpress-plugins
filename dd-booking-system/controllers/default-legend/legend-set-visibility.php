<?php
global $wpdb;


$legendId = $_GET['legendID'];

$calendarLegend = json_decode(get_option('wpbs-default-legend'),true);



if($calendarLegend[$legendId]['hide'] == 'hide'){
    $calendarLegend[$legendId]['hide'] = false;
} else {
    $calendarLegend[$legendId]['hide'] = 'hide';
}

    
update_option('wpbs-default-legend',json_encode($calendarLegend));    



wp_redirect(admin_url('admin.php?page=wp-booking-system-default-legend&do=edit-legend'));
die();
?>     