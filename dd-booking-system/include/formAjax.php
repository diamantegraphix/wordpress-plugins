<?php

function wpbs_submitForm_callback() {
    
    global $wpdb;
    $error = null;
    $submitForm = true;
    $formID = $_POST['wpbs-form-id'];
    $wpbsOptions = json_decode(get_option('wpbs-options'),true);
    
    check_ajax_referer( 'wpbs_submit_form_' . $formID );
    
    $sql = $wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bs_forms WHERE formID=%d',$formID);
    
    if ( $wpbsOptions['enableReCaptcha'] == 'yes' && !captcha_verification() ) {
        $error['failedCaptcha'] = true;
		$submitForm = false; 
	}
    
    
    $form = $wpdb->get_row( $sql, ARRAY_A );
    if(count($form) > 0): 
        $fields = json_decode($form['formData'],true); 
        if(!empty($fields)) foreach($fields as $field):
            //backup form data in case of error
            if(!empty($_POST['wpbs-field-' . $field['fieldId']]))
                $error[$field['fieldId']]['value'] = $_POST['wpbs-field-' . $field['fieldId']];
            
            
            
            if($field['fieldRequired'] == 1 && !is_array(@$_POST['wpbs-field-' . $field['fieldId']]) && esc_html(trim(@$_POST['wpbs-field-' . $field['fieldId']])) === '' ){
                $error[$field['fieldId']]['error'] = true;
                $submitForm = false;
            }    
            if($field['fieldType'] == 'email' && !empty($_POST['wpbs-field-' . $field['fieldId']])){
                if(is_email($_POST['wpbs-field-' . $field['fieldId']]) == false){
                    $error[$field['fieldId']]['error'] = true;
                    $submitForm = false;    
                }
            }                          
        endforeach;
        
        $error['startDate'] = (!empty($_POST['wpbs-form-start-date'])) ? $_POST['wpbs-form-start-date'] : false;
        $error['endDate'] = (!empty($_POST['wpbs-form-end-date'])) ? $_POST['wpbs-form-end-date'] : false;        
        
        if(!(!empty($_POST['wpbs-form-start-date']) && !empty($_POST['wpbs-form-end-date']))){
            $error['noDates'] = true;
            
            $error['startDate'] = $_POST['wpbs-form-start-date'];
            $submitForm = false;
        };

        
        if( !isset($error['noDates']) && (((abs($_POST['wpbs-form-end-date'] - $_POST['wpbs-form-start-date'])) / (60*60*24)) + 1) < $_POST['wpbs-form-minimum-days']){
            $error['minDays'] = true;
            $submitForm = false;
        };
    
        
        
    else:
        return __("WP Booking System: Invalid form ID.",'wpbs');
    endif;
    
    if($submitForm != true){
        echo wpbs_display_form($formID,esc_html($_POST['wpbs-form-language']),$error,esc_html($_POST['wpbs-form-calendar-ID']),$_POST['wpbs-form-auto-pending'], $_POST['wpbs-form-minimum-days']);
    } else {
        $formOptions = json_decode($form['formOptions'],true);
        if(!empty($formOptions['thankYou'][$_POST['wpbs-form-language']]))
            @$thankYou = esc_html($formOptions['thankYou'][$_POST['wpbs-form-language']]);
        else
            @$thankYou = esc_html($formOptions['thankYou']['default']);  
        
        echo "<p>".$thankYou."</p>";
        echo '<script>wpbs_clear_selection();</script>';
        if(!empty($formOptions['trackingScript'])) echo stripslashes($formOptions['trackingScript']);
        //prepare form data
        $bookingData = null;
        if(count($form) > 0):  
            $fields = json_decode($form['formData'],true);

            if(!empty($fields)) foreach($fields as $field): 
                if($field['fieldType'] == 'html') continue; // do not save html field
                if($field['fieldType'] == 'email' && !isset($autoReplyEmailField)) {
                    $autoReplyEmailField = $_POST['wpbs-field-' . $field['fieldId']];
                }
                @$bookingData[$field['fieldName']] = $_POST['wpbs-field-' . $field['fieldId']];
                $translatedField = (!empty($field['fieldLanguages'][$_POST['wpbs-form-language']])) ? $field['fieldLanguages'][$_POST['wpbs-form-language']] : $field['fieldName'];
                @$translatedBookingData[$translatedField] = $_POST['wpbs-field-' . $field['fieldId']];
            endforeach;
        endif;
        $bookingData['submittedLanguage'] = $_POST['wpbs-form-language'];
        
        //update calendar if autopending = true;
        if($_POST['wpbs-form-auto-pending'] == 'yes'){
            
            $sql = $wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bs_calendars WHERE calendarID=%d',$_POST['wpbs-form-calendar-ID']);
            $calendar = $wpdb->get_row( $sql, ARRAY_A );
            if($wpdb->num_rows > 0){
                
                //get auto-pending default legend;
                $calendarLegend = json_decode($calendar['calendarLegend'],true);
                foreach($calendarLegend as $ID => $legend){
                    if($legend['auto-pending'] == 'yes'){
                        $autoPendingID = $ID; 
                        break;
                    }
                }
                //set calendar values
                if(!empty($autoPendingID)){
                    $calendarData = json_decode($calendar['calendarData'],true);
                    for($i=$_POST['wpbs-form-start-date'];$i<=$_POST['wpbs-form-end-date']; $i = $i + (60*60*24)){
                        $calendarData[date('Y',$i)][date('n',$i)][date('j',$i)] = $autoPendingID;
                    }
                    
                    $wpdb->update( $wpdb->prefix.'bs_calendars', array('calendarData' => stripslashes(json_encode($calendarData)), 'modifiedDate' => time()), array('calendarID' => $_POST['wpbs-form-calendar-ID']) );
                }
            }                
        };
        
        //insert data into db
        if($_POST['wpbs-form-start-date'] > $_POST['wpbs-form-end-date']){
            $temp = $_POST['wpbs-form-start-date'];
            $_POST['wpbs-form-start-date'] = $_POST['wpbs-form-end-date'];
            $_POST['wpbs-form-end-date'] = $temp;
        } 
        
        
        $wpdb->insert( $wpdb->prefix.'bs_bookings', array('calendarID' => $_POST['wpbs-form-calendar-ID'], 'formID' => $_POST['wpbs-form-id'], 'startDate' => $_POST['wpbs-form-start-date'], 'endDate' => $_POST['wpbs-form-end-date'], 'createdDate' => time(), 'bookingData' => json_encode($bookingData), 'bookingStatus' => 'pending'));
        $bookingID = $wpdb->insert_id;
        
        $sql = $wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bs_calendars WHERE calendarID=%d',$_POST['wpbs-form-calendar-ID']);
        $calendar = $wpdb->get_row( $sql, ARRAY_A );
        
        
        //send email
        $to = $formOptions['sendTo'];
        $subject = "New booking";
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $replyTo = (!empty($autoReplyEmailField)) ? $autoReplyEmailField : get_option('admin_email');
        $headers .= 'From: ' . $replyTo . "\r\n";
        
        $headers .= "Reply-To: ". $replyTo . "\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        $message  = 'A new booking was made via your website!';
        $message .= '<br /><br />';
        
        $message .= '<strong>Website: </strong>' . get_bloginfo('url') . '<br />';
        $message .= '<strong>Calendar: </strong>' . $calendar['calendarTitle'] . ' (ID: '.$_POST['wpbs-form-calendar-ID'].')<br />';
        $message .= '<strong>Booking ID: </strong>' . $bookingID . '<br /><br />';
        
        $message .= '<strong>Check-in: </strong>' . wpbs_timeFormat($_POST['wpbs-form-start-date']) . '<br />';
        $message .= '<strong>Check-out: </strong>' . wpbs_timeFormat($_POST['wpbs-form-end-date']) . '<br /><br />';
        
        
        if(!empty($bookingData)) foreach($bookingData as $formField => $formValue){ 
            if(!is_array($formValue))
                $message .= '<strong>'.wpbs_replaceCustom(($formField)).': </strong> '.wpbs_replaceCustom(($formValue)).'<br />';
            else
                $message .= '<strong>'.wpbs_replaceCustom(($formField)).': </strong> '.wpbs_replaceCustom((implode(', ',$formValue))).'<br />';
        }        
        $message .= "<br />Powered by WP Booking System<br />";
        wp_mail($to, $subject, $message, $headers);
         
             

        
        if(!empty($formOptions['enableAutoReply']) && $formOptions['enableAutoReply'] == 'yes' && !empty($autoReplyEmailField)){
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
            
            if(!empty($formOptions['autoReplyEmailSubject'][$_POST['wpbs-form-language']])){
                $subject = $formOptions['autoReplyEmailSubject'][$_POST['wpbs-form-language']];
            } else {
                $subject = $formOptions['autoReplyEmailSubject']['default'];
            }
            
            $subject = stripslashes($subject);
            
            if(!empty($formOptions['autoReplyEmailBody'][$_POST['wpbs-form-language']])){
                $message = $formOptions['autoReplyEmailBody'][$_POST['wpbs-form-language']];
            } else {
                $message = $formOptions['autoReplyEmailBody']['default'];
            }
            $message = stripslashes($message);
            
            
            
            $labelBookingId = (!empty($wpbsOptions['translationBookingId'][$_POST['wpbs-form-language']])) ? $wpbsOptions['translationBookingId'][$_POST['wpbs-form-language']] : 'Booking ID';
            $labelCheckIn = (!empty($wpbsOptions['translationCheckIn'][$_POST['wpbs-form-language']])) ? $wpbsOptions['translationCheckIn'][$_POST['wpbs-form-language']] : 'Check-In';
            $labelCheckOut = (!empty($wpbsOptions['translationCheckOut'][$_POST['wpbs-form-language']])) ? $wpbsOptions['translationCheckOut'][$_POST['wpbs-form-language']] : 'Check-Out';
            $labelBookingDetails = (!empty($wpbsOptions['translationYourBookingDetails'][$_POST['wpbs-form-language']])) ? $wpbsOptions['translationYourBookingDetails'][$_POST['wpbs-form-language']] : 'Your Booking Details';
            
             
             
            
            
            if($formOptions['autoReplyIncludeDetails']){
            
                $message = '<p>' .  nl2br($message)  . '</p><br /><br /><strong>'.$labelBookingDetails.'</strong><br /><br />';
                
                $message .= '<strong>'.$labelBookingId.': </strong>' . $bookingID . '<br />';
            
                $message .= '<strong>'.$labelCheckIn.': </strong>' . wpbs_timeFormat($_POST['wpbs-form-start-date']) . '<br />';
                $message .= '<strong>'.$labelCheckOut.': </strong>' . wpbs_timeFormat($_POST['wpbs-form-end-date']) . '<br />';
                if(!empty($translatedBookingData)) foreach($translatedBookingData as $formField => $formValue){
                    if(!is_array($formValue))
                        $message .= '<strong>'.wpbs_replaceCustom(($formField)).': </strong> '.wpbs_replaceCustom(($formValue)).'<br />';
                    else
                        $message .= '<strong>'.wpbs_replaceCustom(($formField)).': </strong> '.wpbs_replaceCustom((implode(', ',$formValue))).'<br />';
                } 
            } else {
                $message = '<p>' . nl2br(wpbs_replaceCustom(($message))) . '</p>';
            }
             

            wp_mail($autoReplyEmailField, $subject, $message, $headers);

        }
        
    }
        
    
	die(); 
}
