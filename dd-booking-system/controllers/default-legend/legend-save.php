<?php 
global $wpdb;

$calendarId = $_POST['calendarID'];

$calendarLegend = json_decode(get_option('wpbs-default-legend'),true);
    
if(empty($_POST['legendID'])){
    $legendId = max(array_keys($calendarLegend));
    $i = 1; while(!empty($calendarLegend[$i])){
        $i++;
    }
    $legendId = $i;    
} else {
    $legendId = $_POST['legendID'];
} 

$availableLanguages = json_decode(get_option('wpbs-languages'),true);
foreach($availableLanguages as $languageCode => $languageName):
    $calendarLegend[$legendId]['name'][$languageCode] = $_POST[$languageCode];
endforeach;

$calendarLegend[$legendId]['name']['default'] = $_POST['legendTitle'];
$calendarLegend[$legendId]['color'] = $_POST['color'];

//Split Color
$splitColor = false;
if(!empty($_POST['activeSplitColor']) && $_POST['activeSplitColor'] == 'on'){
    $splitColor = $_POST['splitColor'];
}
$calendarLegend[$legendId]['splitColor'] = $splitColor;


if(!empty($_POST['bookable']) && $_POST['bookable'] == 'on'){
    $calendarLegend[$legendId]['bookable'] = 'yes';
} else {
    $calendarLegend[$legendId]['bookable'] = false;
}

    
update_option('wpbs-default-legend',json_encode($calendarLegend));       


wp_redirect(admin_url('admin.php?page=wp-booking-system-default-legend&do=edit-legend'));
die();
?>     