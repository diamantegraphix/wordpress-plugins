<?php 
function array_swap_assoc($key1, $key2, $array) {
    $newArray = array ();
    foreach ($array as $key => $value) {
        if ($key == $key1) {
            $newArray[$key2] = $array[$key2];
        } elseif ($key == $key2) {
            $newArray[$key1] = $array[$key1];
        } else {
            $newArray[$key] = $value;
        }
    }
    return $newArray;
}

global $wpdb;

$calendarId = $_GET['id'];
$legendId = $_GET['legendID'];
$direction = $_GET['direction'];

if($direction != 'up' && $direction != 'down') die();

$sql = $wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bs_calendars WHERE calendarID=%d',$calendarId);
$calendar = $wpdb->get_row( $sql, ARRAY_A );
if($wpdb->num_rows > 0):
    $calendarLegend = json_decode($calendar['calendarLegend'],true);
    //reset array just to be sure
    
    reset($calendarLegend);
    
    //set index to current key
    while (key($calendarLegend) != $legendId) next($calendarLegend);
    
    //get prev or next position
    if($direction == 'up'){
        prev($calendarLegend);
        $swapKey = key($calendarLegend);
    } elseif($direction == 'down') {
        next($calendarLegend);
        $swapKey = key($calendarLegend);
    }    
    //check if prev or next exists
    if(empty($swapKey)) die();
  
    //if all good, swap them
    $calendarLegend = array_swap_assoc($legendId,$swapKey,$calendarLegend);
    
    $wpdb->update( $wpdb->prefix.'bs_calendars', array('calendarLegend' => json_encode($calendarLegend)), array('calendarID' => $calendarId));  
endif;

wp_redirect(admin_url('admin.php?page=wp-booking-system&do=edit-legend&id='.$_GET['id']));
die();
?>