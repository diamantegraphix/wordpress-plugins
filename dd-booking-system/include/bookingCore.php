<?php
function wpbs_display_bookings($calendarID = ""){
    global $wpdb; 
    
    $bookingStatuses = array(
        'pending' => array('title' => __("Pending",'wpbs'),'sql' => 'ORDER BY createdDate ASC'), 
        'accepted' => array('title' => __("Accepted",'wpbs'),'sql' => 'ORDER BY startDate ASC'), 
        'trash' => array('title' => __("Trash",'wpbs'),'sql' => 'ORDER BY createdDate ASC')
    );
    
   
    
    $sql = 'SELECT bookingID FROM ' . $wpdb->prefix . 'bs_bookings WHERE calendarID = "'. $calendarID .'"';
    $rows = $wpdb->get_results( $sql, ARRAY_A );
    if($wpdb->num_rows > 0):
        echo '<div class="wpbs-bookings-container">';
            //menu
            echo '<div class="wpbs-bookings-tabs">';
            $i = 0; foreach($bookingStatuses as $bookingStatusKey => $bookingStatus):
                
                if(++$i != 1) echo " | "; 
                $bookingsQuery = 'SELECT bookingID FROM ' . $wpdb->prefix . 'bs_bookings WHERE bookingStatus = "'.$bookingStatusKey.'" AND calendarID = '. $calendarID .' ' . $bookingStatus['sql'];
                $bookings = $wpdb->get_results( $bookingsQuery, ARRAY_A );
                echo '<a href="#wpbs-bookings-'.$bookingStatusKey.'" class="wpbs-bookings-tab-button" id="wpbs-bookings-tab-'.$bookingStatusKey.'">'.$bookingStatus['title'].'</a>';
                echo ' <span class="wpbs-bookings-count">('.$wpdb->num_rows.')</span>';                  
                
            endforeach;
            echo '</div>';        
        
            //listings
            echo '<div class="wpbs-bookings-statuses">';
            foreach($bookingStatuses as $bookingStatusKey => $bookingStatus):
                
                $bookingsQuery = 'SELECT bookingID,bookingStatus FROM ' . $wpdb->prefix . 'bs_bookings WHERE bookingStatus = "'.$bookingStatusKey.'" AND calendarID = '. $calendarID .' ' . $bookingStatus['sql'];
                $bookings = $wpdb->get_results( $bookingsQuery, ARRAY_A );
                
                echo '<div id="wpbs-bookings-'.$bookingStatusKey.'" class="wpbs-bookings-status wpbs-bookings-'.$bookingStatusKey.'">';
                echo '<div class="wpbs-data-table-pagination"><span class="wpbs-data-table-total-items"></span><a href="#" class="button button-secondary wpbs-data-table-first-page">&laquo;</a> <a href="#" class="button button-secondary wpbs-data-table-prev-page">&lsaquo;</a> <input type="text" class="wpbs-data-table-current" /> of <span class="wpbs-data-table-total"></span> <a href="#" class="button button-secondary wpbs-data-table-next-page">&rsaquo;</a>  <a href="#" class="button button-secondary wpbs-data-table-last-page">&raquo;</a></div>';
                echo '<table border="0" cellpadding="0" cellspacing="0" id="data-table-'.$bookingStatusKey.'" class="wpbs-data-table"><thead><tr><th></th></tr></thead><tbody>';
                    if($wpdb->num_rows > 0):                
                        foreach($bookings as $booking):
                            echo wpbs_display_single_booking($booking['bookingID']);

                        endforeach;
                    endif;   
                echo '</tbody></table>';               
                echo '</div>';   
                   
            endforeach;
            echo '</div>';
        echo '</div>';
        
        $acceptedPast = 'SELECT bookingID FROM ' . $wpdb->prefix . 'bs_bookings WHERE bookingStatus = "accepted" AND startDate < '. time();
        $wpdb->get_results( $acceptedPast, ARRAY_A );
        echo "<div id='wpbs-past-accepted-bookings'>". $wpdb->num_rows."</div>";
        
    else:
        echo __("No bookings were made yet.",'wpbs');
    endif;

}


function wpbs_display_single_booking($bookingID){
    global $wpdb; 
    $sql = 'SELECT * FROM ' . $wpdb->prefix . 'bs_bookings WHERE bookingID = '. $bookingID .'';
    $booking = $wpdb->get_row( $sql, ARRAY_A );
    $bookingData = json_decode($booking['bookingData'],true);
    $preview = '';
    if(!empty($bookingData)) foreach($bookingData as $formField => $formValue): if($formField == 'submittedLanguage') continue;
        if(!is_array($formValue) && !empty($formValue))
            $preview .= "<strong>".wpbs_replaceCustom($formField)."</strong>: " . $formValue . " ";
    endforeach;

    echo '<tr><td class="wpbs-booking-field wpbs-booking-field-read-'.$booking['bookingRead'].'" id="wpbs-booking-field-'.$bookingID.'">';
             wpbs_booking_delete_button($bookingID);
    echo     '<span class="wpbs-booking-field-date wpbs-booking-field-date-id wpbs-booking-open-options"><strong>ID</strong>: #'.$booking['bookingID'].'&nbsp;</span>';
    echo     '<span class="wpbs-booking-field-date wpbs-booking-open-options wpbs-booking-field-date-padding"><strong>'.__("Check In:",'wpbs').' </strong>'.wpbs_timeFormat($booking['startDate']).'&nbsp;</span><span class="wpbs-booking-field-date wpbs-booking-open-options"><strong>'.__("Check Out:",'wpbs').' </strong>'.wpbs_timeFormat($booking['endDate']).'</span>';
    echo     '<span class="wpbs-booking-field-preview wpbs-booking-open-options"><span class="wpbs-booking-field-preview-wrap">'.wpbs_html_cut(wpbs_replaceCustom($preview),150).'...</span></span>';
    
    echo     '<div class="wpbs-booking-field-options" style="display:none;">';
    if(!empty($bookingData)) foreach($bookingData as $formField => $formValue): if($formField == 'submittedLanguage') continue;
        if(!is_array($formValue))
            echo         '<p><strong>'.wpbs_replaceCustom($formField).': </strong> <span class="wpbs-booking-field-text-wrap">'.stripslashes($formValue).'</span></p>';
        else
            echo         '<p><strong>'.wpbs_replaceCustom($formField).': </strong> <span class="wpbs-booking-field-text-wrap">'.implode(', ',$formValue).'</span></p>';
    endforeach;
    echo        '<div class="wpbs-booking-line"><!-- --></div>';
                wpbs_booking_status_button($bookingID);
    echo     '</div>';
    echo '</td></tr>';

    

}

function wpbs_booking_delete_button($ID){
    
    global $wpdb;
    $sql = 'SELECT * FROM ' . $wpdb->prefix . 'bs_bookings WHERE bookingID = '. $ID .'';
    $booking = $wpdb->get_row( $sql, ARRAY_A );
    switch($booking['bookingStatus']){
        case 'pending':
            echo '<a onclick="return confirm('."'".__("Are you sure you want to delete this booking?",'wpbs')."'".');" href="'. admin_url( "admin.php?page=wp-booking-system&do=booking-delete&bookingID=".$ID."&calendarID=".$booking['calendarID'] . "&noheader=true") . '" class="wpbs-button-delete wpbs-booking-delete"></a>';
            
            break;
        case 'accepted':
            
            echo '<a href="#" class="wpbs-booking-delete wpbs-booking-modal wpbs-button-accept" data-action="delete" data-id="'. $booking['calendarID'] .'" data-booking-id="'. $booking['bookingID'] .'" data-start="'.$booking['startDate'].'" data-end="'.$booking['endDate'].'"></a> ';
            break;
        case 'trash':
            echo '<a onclick="return confirm('."'".__('Are you sure you want to permanently delete this booking?','wpbs')."'".');" href="'. admin_url( "admin.php?page=wp-booking-system&do=booking-delete&bookingID=".$ID."&calendarID=".$booking['calendarID'] . "&noheader=true") . '" class="wpbs-booking-delete wpbs-button-delete"></a>';
            break;
        default:
            echo __("Invalid Status",'wpbs');
            
                
    }
}

function wpbs_booking_status_button($ID){
    global $wpdb;
    $sql = 'SELECT * FROM ' . $wpdb->prefix . 'bs_bookings WHERE bookingID = '. $ID .'';
    $booking = $wpdb->get_row( $sql, ARRAY_A );
    switch($booking['bookingStatus']){
        case 'pending':
            echo '<a href="#" class="wpbs-booking-modal wpbs-button-accept" data-action="accept" data-id="'. $booking['calendarID'] .'" data-booking-id="'. $booking['bookingID'] .'" data-start="'.$booking['startDate'].'" data-end="'.$booking['endDate'].'">'.__("Accept",'wpbs').'</a> ';
            echo '<a onclick="return confirm('."'".__('Are you sure you want to delete this booking?','wpbs')."'".');" href="'. admin_url( "admin.php?page=wp-booking-system&do=booking-delete&bookingID=".$ID."&calendarID=".$booking['calendarID'] . "&noheader=true") . '" class="wpbs-button-delete">'.__("Delete",'wpbs').'</a>';
            
            break;
        case 'accepted':
            echo '<a href="#" class="wpbs-booking-modal wpbs-button-accept" data-action="edit" data-id="'. $booking['calendarID'] .'" data-booking-id="'. $booking['bookingID'] .'" data-start="'.$booking['startDate'].'" data-end="'.$booking['endDate'].'">'.__("Edit Availability",'wpbs').'</a> ';
            echo '<a href="#" class="wpbs-booking-modal wpbs-button-accept" data-action="delete" data-id="'. $booking['calendarID'] .'" data-booking-id="'. $booking['bookingID'] .'" data-start="'.$booking['startDate'].'" data-end="'.$booking['endDate'].'">'.__("Delete",'wpbs').'</a> ';
            break;
        case 'trash':
            echo '<a onclick="return confirm('."'".__('Are you sure you want to permanently delete this booking?','wpbs')."'".');" href="'. admin_url( "admin.php?page=wp-booking-system&do=booking-delete&bookingID=".$ID."&calendarID=".$booking['calendarID'] . "&noheader=true") . '" class="wpbs-button-delete">'.__("Delete",'wpbs').'</a>';
            break;
        default:
            echo __("Invalid Status");
            
                
    }
    
}
add_action('admin_footer',  'wpbs_modal_overlay'); 
function wpbs_modal_overlay(){
    ?>
        <div class="wpbs-modal-overlay">
            <div class="wpbs-modal-box">
                <div class="wpbs-modal-box-header">
                    <div class="wpbs-modal-box-header-buttons">
                        <a href="#" class="button button-secondary wpbs-close-modal"><?php echo __("Cancel",'wpbs') ;?></a>
                        <a href="#" class="button button-primary wpbs-accept-booking"><?php echo __("Accept Booking",'wpbs') ;?></a>
                    </div>
                    <h2><?php echo __("Accept Booking - Edit Availability",'wpbs') ;?></h2>
                </div>
                <div class="wpbs-modal-box-container"></div>
            </div>
        </div>
    <?php
}












