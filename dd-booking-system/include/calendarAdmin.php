<?php
function wpbs_edit_dates($options){
    
    foreach($options as $key => $value){
        if(empty($$key))
            $$key = $value;
    }
    
    if(!empty($customRange) && $customRange == true){
        
        $calendarLanguage = wpbs_get_admin_language();
        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];
        
        
        global $wpdb;
        $sql = 'SELECT bookingData,formID FROM ' . $wpdb->prefix . 'bs_bookings WHERE bookingID = '. $bookingID .'';
        $booking = $wpdb->get_row( $sql, ARRAY_A );
        $bookingData = json_decode($booking['bookingData'],true);
        

        
        $sql = 'SELECT formData FROM ' . $wpdb->prefix . 'bs_forms WHERE formID = '. $booking['formID'] .'';
        $form = $wpdb->get_row( $sql, ARRAY_A );
        $formData = json_decode($form['formData'],true);

        if(!empty($formData)) foreach($formData as $field): 

            if($field['fieldType'] == 'email' && !isset($autoReplyEmailField)) {
                $autoReplyEmailField = $field['fieldName']; break;
            }            
        endforeach;
        
        $sendMessageTo = false;
        if(!empty($autoReplyEmailField) && !empty($bookingData[$autoReplyEmailField])) $sendMessageTo = $bookingData[$autoReplyEmailField];
        
        
        $output =  "<div class='edit-dates-popup'>";
            $output .= "<h3>".__(date('F Y',$startDate))."</h3>";
            $output .= '<div class="wpbs-dates-editor wp-dates-editor-popup"><ul>';
            
            $currentMonth = date('F',$startDate);
            for($i=$startDate;$i<=($endDate+3600);$i=$i + 60*60*24):
                if($currentMonth != date('F',$i)){
                    $currentMonth = date('F',$i);
                    $output .= "</ul></div><div class='wpbs-clear'></div><h3>".__(date('F',$i))." ".date('Y',$i)."</h3><div class='wpbs-dates-editor'><ul>";
                }
                $output .= wpbs_edit_date($calendarData,$calendarLegend,date('j',$i),$i,$calendarLanguage);
            endfor;
            $output .= "</ul></div>";   
        $output .= "</div>";
        
        $output .= "<div class='edit-dates-sidebar'>";
        
            $output .= " <div class='bulk-edit-dates-popup'>";
                $output .= "<h3>".__("Bulk Edit Dates",'wpbs')."</h3>";
                $output .= "<div class='bulk-edit-dates-popup-container'>";
                    $output .= '<select class="bulk-edit-legend-select">';
                    foreach(json_decode($calendarLegend,true) as $key => $value ): $selected = null;
                        if(!empty($value['name'][$calendarLanguage])) $legendName = $value['name'][$calendarLanguage]; else $legendName = $value['name']['default'];
                        if(!empty($status) && $status == $key) $selected = ' selected="selected"';
                        $output .= '<option class="wpbs-option-'.$key.'" value="' . $key . '"' . $selected . '>' . $legendName . '</option>';
                    endforeach;
                    $output .= "</select>";
                    
                    $output .= "<input type='text' class='bulk-edit-legend-text'>";
                    
                    $output .= "<input type='button' class='button button-secondary bulk-edit-legend-apply' value='".__("Apply Changes",'wpbs')."' />";
                $output .= "</div>";
            $output .= "</div>";
            
            $output .= " <div class='edit-dates-popup-messages'>";
                $output .= "<h3>".__("Messages",'wpbs')."</h3>";
                $output .= " <div class='edit-dates-popup-messages-container'>";
                $output .= "<select class='send-confirmation-message' name='send-confirmation-message'>";
                    $output .= "<option value='0'>".__("Don't send confirmation message",'wpbs')."</option>";
                    $output .= "<option value='1'>".__("Send confirmation message",'wpbs')."</option>";
                $output .= "</select>";
                
                $output .= "<div class='send-confirmation-message-options'>";
                    $output .= '<input type="hidden" name="send-confirmation-message-to" value="'.$sendMessageTo.'" />';
                    if(!empty($sendMessageTo)){
                        $output .= "<textarea class='send-confirmation-message-additional' name='send-confirmation-message-additional' placeholder='".__('Additional message (optional)...','wpbs')."'></textarea>";                       
                        
                        $output .= "<p class='small'>".__('The email will be sent to','wpbs')." ".$sendMessageTo.", ".__('the booking details will be automatically included','wpbs')."</p>";    
                    } else {
                        $output .= "<p>".__('There is no email address field in the form or the email address was not entered.','wpbs')."</p>";
                    }
                    
                $output .= "</div>";
                    
                $output .= "</div>";
            $output .= "</div>";
            
        $output .= "</div>";
        
    } else {
        $output = '<div class="wp-dates-editor-wrapper"><div class="wpbs-dates-editor"><ul>';
        for($i=1;$i<=date('t',$currentTimestamp);$i++):
            $output .= wpbs_edit_date($calendarData,$calendarLegend,$i,$currentTimestamp,$calendarLanguage);
        endfor;
        $output .= "</ul></div></div>";     
        $output .= "<input type='hidden' name='wpbsCalendarData' id='inputCalendarData' value='".$calendarData."' />";
        $output .= "<input type='hidden' id='wpbs_booking_action' name='wpbs_booking_action' />";
        $output .= "<input type='hidden' id='wpbs_booking_id' name='wpbs_booking_id' />";
        $output .= "<input type='hidden' id='wpbs_send_confirmation_message' name='wpbs_send_confirmation_message' />"; 
        $output .= "<input type='hidden' id='wpbs_confirmation_message' name='wpbs_confirmation_message' />";   
    }
    
    return $output;
    
    
}

function wpbs_edit_date($calendarData,$legend,$day,$timestamp,$language){
    $calendarData = json_decode($calendarData,true);
    $status = 'default';
    if(!empty($calendarData[date('Y',$timestamp)][date('n',$timestamp)][$day]))
        $status = $calendarData[date('Y',$timestamp)][date('n',$timestamp)][$day];
    $description = '';   
    if(!empty($calendarData[date('Y',$timestamp)][date('n',$timestamp)]["description-" . $day]))
        $description = $calendarData[date('Y',$timestamp)][date('n',$timestamp)]["description-" . $day]; 
        
    $output = '<li><span class="wpbs-day-and-status">';
        $output .= '<span class="wpbs-select-status status-'.$status.'">';
            $output .= '<span class="wpbs-day-split-top wpbs-day-split-top-'.$status.'"></span>';
            $output .= '<span class="wpbs-day-split-bottom wpbs-day-split-bottom-'.$status.'"></span>';    
            $output .= '<span class="wpbs-day-split-day">'.$day.'</span>';
        $output .= '</span>';
        
        $output .= '<select class="wpbs-day-select wpbs-day-'.$day.'" data-name="wpbs-day-'.$day.'" data-year="wpbs-year-'.date('Y',$timestamp).'" data-month="wpbs-month-'.date('n',$timestamp).'">';
        foreach(json_decode($legend,true) as $key => $value ): $selected = null;
            if(!empty($value['name'][$language])) $legendName = $value['name'][$language]; else $legendName = $value['name']['default'];
            if(!empty($status) && $status == $key) $selected = ' selected="selected"';
            $output .= '<option class="wpbs-option-'.$key.'" value="' . $key . '"' . $selected . '>' . $legendName . '</option>';
        endforeach;
        $output .= "</select></span>";
        $output .= '<input class="wpbs-input-description" type="text" value="'. htmlentities(wpbs_replaceCustom(stripslashes($description)),ENT_QUOTES,'UTF-8').'" data-name="wpbs-day-'.$day.'" data-year="wpbs-year-'.date('Y',$timestamp).'" data-month="wpbs-month-'.date('n',$timestamp).'" />';
    $output .= "</li>";
    return $output;
}

function wpbs_edit_legend($calendarLegend,$showEdit, $calendarID){
    ob_start();
    ?>
    <div class="wpbs-calendar-legend-container">
        <?php echo wpbs_print_legend($calendarLegend,wpbs_get_admin_language(),false);?>
        <a class="button button-secondary" href="<?php echo admin_url( 'admin.php?page=wp-booking-system&do=edit-legend&id=' . $calendarID);?>"><?php echo __("Edit Legend",'wpbs') ;?></a>
    </div>
    <?php
    $output = ob_get_contents();
    ob_clean();
    return $output;
}

function wpbs_edit_users($calendarID){
    global $wpdb;
    ob_start();
    if( current_user_can( 'manage_options' ) ):
        $sql = $wpdb->prepare('SELECT * FROM ' . $wpdb->base_prefix . 'bs_calendars WHERE calendarID=%d',$calendarID); 
        $calendar = $wpdb->get_row( $sql, ARRAY_A );
        $calendarUsers = json_decode($calendar['calendarUsers']);
        ?>
        
        <div class="wpbs-calendar-users">
            <p><?php echo __('Assign users to this calendar','wpbs');?></p>
            <select data-placeholder="<?php _e('Select users','wpbs');?>" class="wpbs-chosen" name="wpbs-calendar-users[]" multiple="multiple">
            <?php $users = get_users(); foreach($users as $user): if($user->roles[0] == 'administrator') continue; ?>
                <option<?php if( !empty($calendarUsers) && in_array($user->ID, $calendarUsers ) ):?> selected="selected"<?php endif;?> value="<?php echo $user->ID; ?>"><?php echo $user->user_nicename; ?></option>
            <?php endforeach;?> 
            </select>
        </div>
        <?php
    endif;
    $output = ob_get_contents();
    ob_clean();
    return $output;
}

function wpbs_batch_update($calendarLegend){
    ob_start();
    ?>
    <div class="wpbs-batch-update">
            <span class="error"><?php echo __("Start time must be lower than end time",'wpbs') ;?></span>
    		<p>
    			<label for="startYear"><?php echo __("Start Date:",'wpbs') ;?></label>
    			<select name="startYear" id="startYear">
                    <?php for($i = date("Y"); $i<= date("Y") + 10; $i++):?>
                    <option value="<?php echo $i;?>"><?php echo $i;?></option>
                    <?php endfor?>
                </select>
                <select name="startMonth" id="startMonth">
                    <?php for($i = strtotime('1 January ' . date('Y')); $i<= strtotime('1 January ' . date('Y') . ' + 1 year'); $i = $i + (60*60*24*31)):?>
                    <option<?php if(date('F') == date("F",$i)):?> selected="selected"<?php endif;?> value="<?php echo date('F',$i);?>"><?php echo __(date('M',$i));?></option>
                    <?php endfor?>
                </select>
                <select name="startDay" id="startDay">
                    <?php for($i = 1; $i<= 31; $i++):?>
                    <option value="<?php echo $i;?>"><?php echo $i;?></option>
                    <?php endfor?>
                </select>
    		</p>
            
            <p>
    			<label for="endYear"><?php echo __("End Date:",'wpbs') ;?></label>
    			<select name="endYear" id="endYear">
                    <?php for($i = date("Y"); $i<= date("Y") + 10; $i++):?>
                    <option value="<?php echo $i;?>"><?php echo $i;?></option>
                    <?php endfor?>
                </select>
                <select name="endMonth" id="endMonth">
                    <?php for($i = strtotime('1 January ' . date('Y')); $i<= strtotime('1 January ' . date('Y') . ' + 1 year'); $i = $i + (60*60*24*31)):?>
                    <option<?php if(date('F') == date("F",$i)):?> selected="selected"<?php endif;?> value="<?php echo date('F',$i);?>"><?php echo __(date('M',$i));?></option>
                    <?php endfor?>
                </select>
                <select name="endDay" id="endDay">
                    <?php for($i = 1; $i<= 31; $i++):?>
                    <option value="<?php echo $i;?>"><?php echo $i;?></option>
                    <?php endfor?>
                </select>
    		</p>
            
            <p>
    			<label for="bookingDetails"><?php echo __('Booking Details','wpbs');?>:</label>
                <input type="text" id="bookingDetails" name="bookingDetails" />
                
    		</p>
            
            <p>
    			<label for="changeStatus"><?php echo __("Status:",'wpbs') ;?></label>            
    			<select name="changeStatus" id="changeStatus">
    				<?php foreach (json_decode($calendarLegend,true) as $statusKey => $statusName): ?>
                    <?php if(!empty($statusName['name'][wpbs_get_admin_language()])) $legendName = $statusName['name'][wpbs_get_admin_language()]; else $legendName = $statusName['name']['default'];?>
                    <?php $selected = ''; if($statusKey == 'default') $selected = ' selected="selected"';?>
    				<option<?php echo $selected;?> class="ac-status-<?php echo $statusKey ?>" value="<?php echo $statusKey ?>"><?php echo $legendName; ?></option>
    				<?php endforeach ?>
    			</select>
    		</p>
            <input type="button" id="calendarBatchUpdate" class="button button-secondary" value="<?php echo __("Apply Changes",'wpbs') ;?>" />
            
    </div>
<?php
    $output = ob_get_contents();
    ob_clean();
    return $output;
}
