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


$legendId = $_GET['legendID'];
$direction = $_GET['direction'];

if($direction != 'up' && $direction != 'down') die();


$calendarLegend = json_decode(get_option('wpbs-default-legend'),true);
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

update_option('wpbs-default-legend',json_encode($calendarLegend));   


wp_redirect(admin_url('admin.php?page=wp-booking-system-default-legend&do=edit-legend'));
die();
?>