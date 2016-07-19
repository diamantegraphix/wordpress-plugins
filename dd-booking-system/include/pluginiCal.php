<?php
$calendarId = str_replace(".ics","",$_GET['wp-booking-system-ical']);
$sql = 'SELECT * FROM ' . $wpdb->prefix . 'bs_calendars WHERE calendarHash = "' . $calendarId . '"';
$calendar = $wpdb->get_row( $sql, ARRAY_A );
if($wpdb->num_rows > 0){
    
    
    $calendarData = json_decode($calendar['calendarData'],true);
    $calendarLegend = json_decode($calendar['calendarLegend'],true);
    
    
    
    
    
    header("Content-Type: text/calendar"); 
    header("Content-Disposition: inline; filename=".strtolower($calendar['calendarHash']).".ics");
    
    echo "BEGIN:VCALENDAR\r\n";
    echo "VERSION:2.0\r\n";
    echo "PRODID:WP Booking System - Calendar ID: ".$calendarId."\r\n";
    echo "CALSCALE:GREGORIAN\r\n";
    echo "METHOD:PUBLISH\r\n";
    echo "TZID:UTC+0\r\n";
    /*
    echo "BEGIN:VTIMEZONE\r\n";
    if(get_option('timezone_string')){
        $tzid = get_option('timezone_string');  
    } elseif(get_option('gmt_offset')){
        $tzid = wpbs_tz_offset_to_name(get_option('gmt_offset'));
    } else {
        $tzid = '';
    }
    echo "TZID:". $tzid ."\r\n";
    echo "END:VTIMEZONE\r\n";
    
    if(!empty($tzid)){
        $timezone = $tzid;
        $time = new DateTime('now', new DateTimeZone($timezone));
        $timezoneOffset = $time->format('Z');
    } else {
        $timezoneOffset = 0;
    }
    */
    foreach($calendarData as $year => $months){
        if($months) foreach($months as $month => $days){
            if($days) foreach($days as $day => $status){
                
                if(strpos($day,'description') !== false) continue;                
                if(mktime(0,0,0,$month,$day,$year) < (time() - (60*60*24)) ) continue;
                
                if(!empty($calendarLegend[$status]) && !empty($calendarLegend[$status]['sync']) && $calendarLegend[$status]['sync'] == 'yes'){
                    $vCalStart = $vCalEnd = date("Ymd", mktime(0,0,0,$month,$day,$year)); 
                    $vCalEnd = date("Ymd", mktime(0,0,0,$month,$day+1,$year)); 
                
                    $description = (isset($calendarData[$year][$month]['description-' . $day])) ? $calendarData[$year][$month]['description-' . $day] : '';
                    
                    
                    
                    echo "BEGIN:VEVENT\r\n";
                    echo "DTSTART;VALUE=DATE:". $vCalStart . "\r\n";
                    echo "DTEND;VALUE=DATE:". $vCalEnd . "\r\n";
                    echo "CLASS:PUBLIC\r\n";
                    echo "DESCRIPTION:". wpbs_replaceCustom($description) . "\r\n";
                    echo "STATUS:CONFIRMED\r\n";
                    echo "SUMMARY:". 'Booked' . "\r\n";
                    echo "TRANSP:TRANSPARENT\r\n";
                    echo "END:VEVENT\r\n";
                }
                
            }            
        }
    }
    
       
    echo "END:VCALENDAR";

    
} else {
    echo __("Invalid calendar ID.");
}