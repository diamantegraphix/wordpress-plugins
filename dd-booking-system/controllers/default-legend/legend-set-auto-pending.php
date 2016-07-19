<?php
global $wpdb;

$legendId = $_GET['legendID'];



$calendarLegend = json_decode(get_option('wpbs-default-legend'),true);


foreach($calendarLegend as $ID => $value){
    if($ID == $legendId){
        $calendarLegend[$ID]['auto-pending'] = 'yes';
    } else {
        $calendarLegend[$ID]['auto-pending'] = 'no';
    }
}    


update_option('wpbs-default-legend',json_encode($calendarLegend));   


wp_redirect(admin_url('admin.php?page=wp-booking-system-default-legend&do=edit-legend'));
die();
?>     