<?php 
global $wpdb;

$calendarId = $_POST['calendarID'];


$sql = $wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bs_calendars WHERE calendarID=%d',$calendarId);
$calendar = $wpdb->get_row( $sql, ARRAY_A );
if($wpdb->num_rows > 0):
    $calendarLegend = json_decode($calendar['calendarLegend'],true);
    

    if(empty($_POST['legendID'])){
        $legendId = max(array_keys($calendarLegend));
        $i = 1; while(!empty($calendarLegend[$i])){
            $i++;
        }
        $legendId = $i;    
    } else {
        $legendId = $_POST['legendID'];
    } 

        
    /*
    if(empty($_POST['default'])) {$error[] = array('default' => "Please enter a default title"); $_POST['default'] = " "; }
    
    if(!preg_match('/^#[a-f0-9]{6}$/i', $_POST['color'])) {$error[] = array('color' => "Invalid color code");  }
    
    
    if(!empty($_POST['activeSplitColor']) && $_POST['activeSplitColor'] == 'on'){
        if(!preg_match('/^#[a-f0-9]{6}$/i', $_POST['splitColor'])) {$error[] = array('splitColor' => "Invalid color code");  }   
    }
    
    if(!empty($error)){
        $_SESSION['error'] = $error;
        $_SESSION['post'] = $_POST;
        if(!empty($_POST['legendId'])){
            header("Location: " . base_url() . "manage/edit-legend-item/".$calendarId."/?legendId=" . $legendId );
        } else {
            header("Location: " . base_url() . "manage/add-legend/".$calendarId."/");
        }
        die();
    }
    */
    $availableLanguages = json_decode(get_option('wpbs-languages'),true);
    foreach($availableLanguages as $languageCode => $languageName):
        $calendarLegend[$legendId]['name'][$languageCode] = $_POST[$languageCode];
    endforeach;
    
    $calendarLegend[$legendId]['name']['default'] = stripslashes($_POST['legendTitle']);
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

    
    $wpdb->update( $wpdb->prefix.'bs_calendars', array('calendarLegend' => json_encode($calendarLegend)), array('calendarID' => $calendarId));     
endif;

wp_redirect(admin_url('admin.php?page=wp-booking-system&do=edit-legend&id='.$calendarId));
die();
?>     