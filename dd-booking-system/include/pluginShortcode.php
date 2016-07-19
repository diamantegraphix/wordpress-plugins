<?php
function wpbs_shortcode( $atts ) {
    
    $wpbsOptions = json_decode(get_option('wpbs-options'),true);
    
    wp_enqueue_style( 'wpbs-calendar', WPBS_PATH . '/css/wpbs-calendar.css' );
    wp_enqueue_script('wpbs', WPBS_PATH . '/js/wpbs.js', array('jquery'));
    wp_enqueue_script('custom-select', WPBS_PATH . '/js/custom-select.js', array('jquery'));
    
    if($wpbsOptions['enableReCaptcha'] == 'yes'){
        wp_enqueue_script('recaptcha', 'https://www.google.com/recaptcha/api.js?onload=CaptchaCallback&render=explicit');    
    }
    
    
    
    
	extract( shortcode_atts( array(
		'id'        => null,
        'form'        => null,
		'title'     => 'no',
        'legend'    => 'no',
        'start'     => '1',
        'display'   => '1',
        'language'  => 'en',
        'dropdown' => '1',
        'history'  => '1',
        'tooltip'  => '1',
        'month' => 0,
        'year' => 0,
        'selection' => 'multiple',
        'autopending' => 'no',
        'minimumdays' => 0,
        'weeknumbers' => 'no',
        'formposition' => 'below'
	), $atts, 'wpbs' ) );
    
    
    if($id == null) return "WP Booking System: ID parameter missing.";
    if($form == null) return "WP Booking System: Form ID parameter missing.";
    
    if(!in_array($month,array(1,2,3,4,5,6,7,8,9,10,11,12))) {$month = date('m');}
    if(intval($year) < 1970 || intval($year) > 2100) { $year = date("Y");}
    
    if(!in_array($title,array('yes','no'))) $title = 'no';
    if(!in_array($selection,array('multiple','single'))) $selection = 'multiple';
    if(!in_array($formposition,array('below','side'))) $formposition = 'below';
    if(!in_array($legend,array('yes','no'))) $legend = 'no';
    if(!in_array($dropdown,array('yes','no'))) $dropdown = 'yes';
    if(!in_array($tooltip,array(1,2,3))) $tooltip = '1';
    if(!in_array($autopending,array('yes','no'))) $autopending = 'no';
    if(!in_array($weeknumbers,array('yes','no'))) $weeknumbers = 'no';
    if(!in_array(absint($start),array(1,2,3,4,5,6,7))) $start = 1;
    if(intval($display) < 1 || intval($display) > 12) $display = 1;
    if(!in_array(absint($history),array(1,2,3))) $history = 1;    
    if(intval($minimumdays) > 31 || intval($minimumdays) < 0) $minimumdays = 0;
    
    global $wpdb;
    
    if($language == 'auto'){
        $language = wpbs_get_locale();
    } else {
        $activeLanguages = json_decode(get_option('wpbs-languages'),true); if(!array_key_exists($language,$activeLanguages)) $language = 'en';    
    }
    
    $sql = $wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bs_calendars WHERE calendarID=%d',$id);
    $calendar = $wpdb->get_row( $sql, ARRAY_A );
    if($wpdb->num_rows > 0):
        
        $output = wpbs_print_legend_css($calendar['calendarLegend'],$calendar['calendarID']);
        if($title == 'yes') $output .= '<h2>' . $calendar['calendarTitle'] . "</h2>";
        $output .= wpbs_calendar(array('ajaxCall' => false, 'calendarHistory' => $history, 'calendarID' => $calendar['calendarID'], 'formID' => $form, 'calendarData' => $calendar['calendarData'], 'totalCalendars' => $display, 'firstDayOfWeek' => $start, 'showDateEditor' => false, 'calendarLegend' => $calendar['calendarLegend'], 'showLegend' => $legend, 'calendarLanguage' => $language, 'showTooltip' => $tooltip, 'currentTimestamp' => strtotime(date("F", mktime(0, 0, 0, $month, 15, date('Y'))) . " " . $year), 'calendarSelectionType' => $selection, 'showDropdown' => $dropdown, 'autoPending' => $autopending, 'weekNumbers' => $weeknumbers, 'minDays' => $minimumdays, 'formPosition' => $formposition ));
        
        return $output;
    else:
        return __('WP Booking System: Invalid calendar ID.','wpbs');
    endif;
	
}
add_shortcode( 'wpbs', 'wpbs_shortcode' );

