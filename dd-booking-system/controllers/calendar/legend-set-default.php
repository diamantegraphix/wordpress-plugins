<?php
global $wpdb;

$calendarId = $_GET['id'];
$legendId = $_GET['legendID'];


$sql = $wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bs_calendars WHERE calendarID=%d',$calendarId);
$calendar = $wpdb->get_row( $sql, ARRAY_A );
if($wpdb->num_rows > 0):
    $calendarLegend = json_decode($calendar['calendarLegend'],true);
    $calendarData = json_decode($calendar['calendarData'],true);
    
    foreach($calendarLegend as $ID => $value){
        if($ID == 'default'){
            $newCalendarLegend[$legendId] = $value;
        } elseif ($ID == $legendId) {
            $newCalendarLegend['default'] = $value;
        } else {
            $newCalendarLegend[$ID] = $value;
        }    
    }    
    $calendarLegend = $newCalendarLegend;
    
    foreach($calendarData as $yearKey => $calendarYear){
        foreach($calendarYear as $monthKey =>  $calendarMonth){
            foreach($calendarMonth as $dayKey => $calendarDay){
                if($calendarDay == $legendId)
                    $calendarData[$yearKey][$monthKey][$dayKey] = 'default';
                elseif($calendarDay == 'default')
                    $calendarData[$yearKey][$monthKey][$dayKey] = $legendId;
            }
        }
    }
    
    $wpdb->update( $wpdb->prefix.'bs_calendars', array('calendarData' => json_encode($calendarData), 'calendarLegend' => json_encode($calendarLegend)), array('calendarID' => $calendarId));     
    
endif;

wp_redirect(admin_url('admin.php?page=wp-booking-system&do=edit-legend&id='.$_GET['id']));
die();
?>     