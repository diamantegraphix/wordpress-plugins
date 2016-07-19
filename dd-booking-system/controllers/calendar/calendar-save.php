<?php
global $wpdb;

if(empty($_POST['wpbs-calendar-users'])) $_POST['wpbs-calendar-users'] = array();

if(!empty($_POST['calendarID'])){
    $wpdb->update( $wpdb->prefix.'bs_calendars', array('calendarTitle' => $_POST['calendarTitle'], 'modifiedDate' => time()), array('calendarID' => $_POST['calendarID']) );
    
    if(json_decode(stripslashes($_POST['wpbsCalendarData']))){
        $wpdb->update( $wpdb->prefix.'bs_calendars', array('calendarData' => stripslashes($_POST['wpbsCalendarData'])), array('calendarID' => $_POST['calendarID']) ); 
    }
    
    $goto = '';
    if(!empty($_POST['wpbs_booking_action']) && !empty($_POST['wpbs_booking_id'])){
        if($_POST['wpbs_booking_action'] == 'accept'){
            $wpdb->update( $wpdb->prefix.'bs_bookings', array('bookingStatus' => 'accepted'), array('bookingID' => $_POST['wpbs_booking_id']) );  
        } elseif($_POST['wpbs_booking_action'] == 'delete'){
            $wpdb->update( $wpdb->prefix.'bs_bookings', array('bookingStatus' => 'trash'), array('bookingID' => $_POST['wpbs_booking_id']) );
            $goto = '&goto=accepted';
        }
        
        if(!empty($_POST['wpbs_send_confirmation_message']) && $_POST['wpbs_send_confirmation_message'] == '1'){
            $sql = 'SELECT * FROM ' . $wpdb->prefix . 'bs_bookings WHERE bookingID = '. $_POST['wpbs_booking_id'] .'';
            $booking = $wpdb->get_row( $sql, ARRAY_A );
            $bookingData = json_decode($booking['bookingData'],true);     
            
            $sql = 'SELECT formData,formOptions FROM ' . $wpdb->prefix . 'bs_forms WHERE formID = '. $booking['formID'] .'';
            $form = $wpdb->get_row( $sql, ARRAY_A );
            $formData = json_decode($form['formData'],true);    
                    
            if(!empty($formData)) foreach($formData as $field):
                if($field['fieldType'] == 'email' && !isset($autoReplyEmailField)) {
                    $autoReplyEmailField = $field['fieldName']; break;
                }            
            endforeach;
            
           
            
            $sendMessageTo = $bookingData[$autoReplyEmailField];
            
            $wpbsOptions = json_decode(get_option('wpbs-options'),true);
            $formOptions = json_decode($form['formOptions'],true);

            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
            $headers .= "X-Priority: 1\r\n"; 

            if(!empty($formOptions['replyFromEmail'])){
                $headers .= 'From: '. stripslashes(html_entity_decode($formOptions['replyFromName'])) . ' <' . stripslashes(html_entity_decode($formOptions['replyFromEmail'])) . '>' . "\r\n";
                $headers .= "Reply-To: ". stripslashes(html_entity_decode($formOptions['replyFromName'])) . ' <' . stripslashes(html_entity_decode($formOptions['replyFromEmail'])) . '>' . "\r\n";
                $headers .= "X-Mailer: PHP/" . phpversion();
            } else {
                $headers .= 'From: '.get_option('blogname').' <'.get_option('admin_email').'>' . "\r\n";   
                $headers .= "Reply-To: ".get_option('blogname').' <'.get_option('admin_email').'>' . "\r\n";   
                $headers .= "X-Mailer: PHP/" . phpversion(); 
            }
            

            $subject = (!empty($wpbsOptions['translationBookingStatusUpdated'][$bookingData['submittedLanguage']])) ? $wpbsOptions['translationBookingStatusUpdated'][$bookingData['submittedLanguage']] : __('Booking status updated','wpbs');           
            $message = '<p>' . nl2br($_POST['wpbs_confirmation_message']) . '</p>';
            

            $message .= __('Your booking details:','wpbs');
            $message .= '<br /><br /><strong>Booking ID: </strong>' . $booking['bookingID'] . '<br /><br />';
        
            $message .= '<strong>Check-in: </strong>' . wpbs_timeFormat($booking['startDate']) . '<br />';
            $message .= '<strong>Check-out: </strong>' . wpbs_timeFormat($booking['endDate']) . '<br /><br />';
            
            
            if(!empty($bookingData)) foreach($bookingData as $formField => $formValue){ if($formField == 'submittedLanguage') continue;
                if(!is_array($formValue))
                    $message .= '<strong>'.wpbs_replaceCustom(($formField)).': </strong> '.wpbs_replaceCustom(($formValue)).'<br />';
                else
                    $message .= '<strong>'.wpbs_replaceCustom(($formField)).': </strong> '.wpbs_replaceCustom((implode(', ',$formValue))).'<br />';
            }       
            

            wp_mail($sendMessageTo, $subject, $message, $headers);
        }
        
    }      
    
    if( current_user_can( 'manage_options' ) ){
        $wpdb->update( $wpdb->base_prefix.'bs_calendars', array('calendarUsers' => json_encode($_POST['wpbs-calendar-users']) ), array('calendarID' => $_POST['calendarID']) );    
    }
       
    wp_redirect(admin_url('admin.php?page=wp-booking-system&do=edit-calendar&id='.$_POST['calendarID'].'&save=ok' . $goto));
} else {
    
    $random_string = wpbs_generateRandomString(32);
    
    $wpdb->insert( $wpdb->prefix.'bs_calendars', array('calendarTitle' => $_POST['calendarTitle'], 'modifiedDate' => time(), 'createdDate' => time(), 'calendarLegend' => wpbs_defaultCalendarLegend(), 'calendarHash' => $random_string));    
    
    if(json_decode(stripslashes($_POST['wpbsCalendarData']))){
        $wpdb->update( $wpdb->prefix.'bs_calendars', array('calendarData' => stripslashes($_POST['wpbsCalendarData'])), array('calendarID' => $wpdb->insert_id) ); 
    }
    
    if( current_user_can( 'manage_options' ) ){
        $wpdb->update( $wpdb->base_prefix.'bs_calendars', array('calendarUsers' => json_encode($_POST['wpbs-calendar-users']) ), array('calendarID' => $wpdb->insert_id) );    
    }
    
    
    wp_redirect(admin_url('admin.php?page=wp-booking-system&do=edit-calendar&id='.$wpdb->insert_id.'&save=ok'));     
}
die();


?>