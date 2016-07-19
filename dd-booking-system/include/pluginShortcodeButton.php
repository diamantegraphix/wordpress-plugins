<?php
add_action('media_buttons', 'wpbs_add_form_button', 20);
function wpbs_add_form_button(){
    $is_post_edit_page = in_array(basename($_SERVER['PHP_SELF']), array('post.php', 'page.php', 'page-new.php', 'post-new.php'));
    if(!$is_post_edit_page)
        return;

    // do a version check for the new 3.5 UI
    $version = get_bloginfo('version');

    if ($version < 3.5) {
        // show button for v 3.4 and below
        $image_btn =  WPBS_PATH.'/images/date-button.gif';
        echo '<a href="#TB_inline?width=480&inlineId=wpbs_add_calendar" class="thickbox" id="add_wpbs" title="' . __("Add Gravity Form", 'wpbs') . '"><img src="'.$image_btn.'" alt="' . __("Add Calendar", 'wpbs') . '" /></a>';
    } else {
        // display button matching new UI
        echo '<style>.wpbs_media_icon{
                background:url('.WPBS_PATH.'/images/date-button.gif) no-repeat top left;
                display: inline-block;
                height: 16px;
                margin: 0 2px 0 0;
                vertical-align: text-top;
                width: 16px;
                }
                .wp-core-ui a.wpbs_media_link{
                 padding-left: 0.4em;
                }
                #TB_window {width:800px !important; margin-left:-400px !important;}
                #TB_ajaxContent {width: 940px !important; height: 580px !important;}
             </style>
              <a href="#TB_inline?width=480&inlineId=wpbs_add_calendar" class="thickbox button wpbs_media_link" id="add_wpbs" title="' . __("Add Calendar", 'wpbs') . '"><span class="wpbs_media_icon "></span> ' . __("Add Calendar", "wpbs") . '</a>';
    }
}

add_action('admin_footer',  'wpbs_add_mce_popup');    
function wpbs_add_mce_popup(){
    global $wpdb;
    ?>
    <script>
        function wpbs_insert_shortcode(){
            var calendar_id = jQuery("#wpbs_calendar_id").val();
            if(calendar_id == ""){
                alert("Please select a calendar");
                return;
            }
            
            var form_id = jQuery("#wpbs_form_id").val();
            if(form_id == ""){
                alert("Please select a form");
                return;
            }

            var wpbs_calendar_title = jQuery("#wpbs_calendar_title").val();
            var wpbs_calendar_legend = jQuery("#wpbs_calendar_legend").val();
            var wpbs_calendar_start = jQuery("#wpbs_calendar_start").val();
            var wpbs_calendar_dropdown = jQuery("#wpbs_calendar_dropdown").val();
            var wpbs_calendar_display = jQuery("#wpbs_calendar_display").val();
            var wpbs_calendar_language = jQuery("#wpbs_calendar_language").val();
            var wpbs_calendar_history = jQuery("#wpbs_calendar_history").val();
            var wpbs_calendar_start_month = jQuery("#wpbs_calendar_start_month").val();
            var wpbs_calendar_tooltip = jQuery("#wpbs_calendar_tooltip").val();
            var wpbs_calendar_start_year = jQuery("#wpbs_calendar_start_year").val();            
            var wpbs_calendar_selection_type = jQuery("#wpbs_calendar_selection_type").val();
            var wpbs_calendar_auto_pending = jQuery("#wpbs_calendar_auto_pending").val();
            var wpbs_calendar_weeknumbers = jQuery("#wpbs_calendar_weeknumbers").val();
            var wpbs_calendar_mindays = jQuery("#wpbs_calendar_mindays").val();
            var wpbs_form_position = jQuery("#wpbs_form_position").val();

            window.send_to_editor('[wpbs id="' + calendar_id + '" form="' + form_id + '" title="' + wpbs_calendar_title + '"  legend="' + wpbs_calendar_legend + '" dropdown="' + wpbs_calendar_dropdown + '"  start="' + wpbs_calendar_start + '"  display="' + wpbs_calendar_display + '" language="' + wpbs_calendar_language + '" history="' + wpbs_calendar_history + '" tooltip="' + wpbs_calendar_tooltip +'" month="' + wpbs_calendar_start_month + '" year="' + wpbs_calendar_start_year + '" selection="' + wpbs_calendar_selection_type + '" autopending="' + wpbs_calendar_auto_pending + '" weeknumbers="' + wpbs_calendar_weeknumbers + '" minimumdays="' + wpbs_calendar_mindays + '" formposition="' + wpbs_form_position + '"]');
        }
    </script>
    <?php $CalendarQuery = 'SELECT * FROM ' . $wpdb->prefix . 'bs_calendars';?>
    <?php $calendars = $wpdb->get_results( $CalendarQuery, ARRAY_A ); $calendarRows = $wpdb->num_rows;?>  
    
    <?php $FormQuery = 'SELECT * FROM ' . $wpdb->prefix . 'bs_forms';?>
    <?php $forms = $wpdb->get_results( $FormQuery, ARRAY_A );  $formRows = $wpdb->num_rows;?>     
    <div id="wpbs_add_calendar" style="display:none;">
        <div class="wrap">
            <div>
            
                <div style="padding:0 15px 0 15px;">
                    
                    <h3><?php echo __("Calendar Options",'wpbs') ;?></h3>                        
                    
                </div>
                <?php if($calendarRows > 0):?>
                <div style="padding:15px 15px 0 15px; float:left; width:160px; ">
                    <strong><?php echo __("Calendar",'wpbs') ;?></strong><br />
                    <select id="wpbs_calendar_id" style="width: 160px;">                
                                               
                        <?php foreach($calendars as $calendar):?>
                            <option value="<?php echo absint($calendar['calendarID']) ?>"><?php echo esc_html($calendar['calendarTitle']) ?></option>
                        <?php endforeach; ?>
                    </select> <br/>
                    
                </div>
                
                <div style="padding:15px 15px 0 15px; float:left; width:160px; ">
                    <strong><?php echo __("Display title?",'wpbs') ;?></strong><br />
                    <select id="wpbs_calendar_title" style="width: 160px;">
                        <option value="yes"><?php echo __("Yes",'wpbs') ;?></option>
                        <option value="no"><?php echo __("No",'wpbs') ;?></option>                        
                    </select> <br/>                    
                </div>
                
                <div style="padding:15px 15px 0 15px; float:left; width:160px; ">
                    <strong><?php echo __("Display legend?",'wpbs') ;?></strong><br />
                    <select id="wpbs_calendar_legend" style="width: 160px;">
                        <option value="yes"><?php echo __("Yes",'wpbs') ;?></option>
                        <option value="no"><?php echo __("No",'wpbs') ;?></option>                        
                    </select> <br/>                    
                </div>
                
                <div style="padding:15px 15px 0 15px; float:left; width:160px; ">
                    <strong><?php echo __("Display dropdown?",'wpbs') ;?></strong><br />
                    <select id="wpbs_calendar_dropdown" style="width: 160px;">
                        <option value="yes"><?php echo __("Yes",'wpbs') ;?></option>
                        <option value="no"><?php echo __("No",'wpbs') ;?></option>                        
                    </select> <br/>                    
                </div>
                
                
                <div style="padding:15px 15px 0 15px; float:left; width:160px;  ">
                    <strong><?php echo __("Week starts on",'wpbs') ;?></strong><br />
                    <select id="wpbs_calendar_start" style="width: 160px;">
                        <option value="1"><?php echo __("Monday",'wpbs') ;?></option>
                        <option value="2"><?php echo __("Tuesday",'wpbs') ;?></option>
                        <option value="3"><?php echo __("Wednesday",'wpbs') ;?></option>
                        <option value="4"><?php echo __("Thursday",'wpbs') ;?></option>
                        <option value="5"><?php echo __("Friday",'wpbs') ;?></option>
                        <option value="6"><?php echo __("Saturday",'wpbs') ;?></option>
                        <option value="7"><?php echo __("Sunday",'wpbs') ;?></option>
                        
                    </select> <br/>                    
                </div>
                
                <div style="padding:15px 15px 0 15px; float:left; width:160px; ">
                    <strong><?php echo __("Months to display?",'wpbs') ;?></strong><br />
                    <select id="wpbs_calendar_display" style="width: 160px;">
                        <?php for($i=1;$i<=12; $i++):?>
                            <option value="<?php echo $i;?>"><?php echo $i;?></option>
                        <?php endfor;?>                        
                    </select> <br/>                    
                </div>
                
                <div style="padding:15px 15px 0 15px; float:left; width:160px; ">
                    <strong><?php echo __("Language",'wpbs') ;?></strong><br />
                    <select id="wpbs_calendar_language" style="width: 160px;">
                        <option value="auto"><?php echo __("Auto (let WP choose)",'wpbs') ;?></option>
                        <?php $activeLanguages = json_decode(get_option('wpbs-languages'),true);?>
                        <?php foreach($activeLanguages as $code => $language):?>
                            <option value="<?php echo $code;?>"><?php echo $language;?></option>
                        <?php endforeach;?>                   
                    </select> <br/>                    
                </div>
                
                <div style="padding:15px 15px 0 15px; float:left; width:160px; ">
                    <strong><?php echo __("Show history?",'wpbs') ;?></strong><br />
                    <select id="wpbs_calendar_history" style="width: 160px;">
                        <option value="1"><?php echo __("Display booking history",'wpbs') ;?></option>
                        <option value="2"><?php echo __("Replace booking history with the default legend item",'wpbs') ;?></option>
                        <option value="3"><?php echo __("Use the Booking History Color from the Settings",'wpbs') ;?></option>                        
                    </select> <br/>                    
                </div>
                
                <div style="padding:15px 15px 0 15px; float:left; width:160px; ">
                    <strong><?php echo __("Start Month",'wpbs') ;?></strong><br />
                    <select id="wpbs_calendar_start_month" style="width: 160px;">
                        <option value="0"><?php echo __("Current Month",'wpbs') ;?></option>
                        <option value="1"><?php echo __("January",'wpbs') ;?></option>
                        <option value="2"><?php echo __("February",'wpbs') ;?></option>
                        <option value="3"><?php echo __("March",'wpbs') ;?></option>
                        <option value="4"><?php echo __("April",'wpbs') ;?></option>
                        <option value="5"><?php echo __("May",'wpbs') ;?></option>
                        <option value="6"><?php echo __("June",'wpbs') ;?></option>
                        <option value="7"><?php echo __("July",'wpbs') ;?></option>
                        <option value="8"><?php echo __("August",'wpbs') ;?></option>
                        <option value="9"><?php echo __("September",'wpbs') ;?></option>
                        <option value="10"><?php echo __("October",'wpbs') ;?></option>
                        <option value="11"><?php echo __("November",'wpbs') ;?></option>
                        <option value="12"><?php echo __("December",'wpbs') ;?></option>   
                    </select> <br/>                    
                </div>
                
                <div style="padding:15px 15px 0 15px; float:left; width:160px;">
                    <strong><?php echo __("Start Year",'wpbs') ;?></strong><br />
                    <select id="wpbs_calendar_start_year" style="width: 160px;">
                        <option value="0"><?php echo __("Current Year",'wpbs') ;?></option>
                        <?php for($i = date("Y"); $i<= date("Y") + 10; $i++):?>
                            <option value="<?php echo $i;?>"><?php echo $i;?></option>
                        <?php endfor;?>                 
                    </select> <br/>                    
                </div>
                
                <div style="padding:15px 15px 0 15px; float:left; width:160px; ">
                    <strong><?php echo __("Show Tooltip?",'wpbs') ;?></strong><br />
                    <select id="wpbs_calendar_tooltip" style="width: 160px;">
                        <option selected="selected" value="1"><?php _e("No", "wpbs"); ?></option>
                        <option value="2"><?php _e("Yes", "wpbs"); ?></option>
                        <option value="3"><?php _e("Yes, with red indicator", "wpbs"); ?></option>                    
                    </select> <br/>                    
                </div>
                
                <div style="padding:15px 15px 0 15px; float:left; width:160px; ">
                    <strong><?php echo __("Selection Type",'wpbs') ;?></strong><br />
                    <select id="wpbs_calendar_selection_type" style="width: 160px;">
                        <option selected="selected" value="multiple"><?php _e("Multiple Dates (Range)", "wpbs"); ?></option>
                        <option value="single"><?php _e("Single Date", "wpbs"); ?></option>                        
                    </select> <br/>                    
                </div>
                
                <div style="padding:15px 15px 0 15px; float:left; width:160px; ">
                    <strong><?php echo __("Auto Pending?",'wpbs') ;?></strong><br />
                    <select id="wpbs_calendar_auto_pending" style="width: 160px;">
                        <option value="yes"><?php _e("Yes", "wpbs"); ?></option>
                        <option selected="selected" value="no"><?php _e("No", "wpbs"); ?></option>                        
                    </select> <br/>                    
                </div>
                
                <div style="padding:15px 15px 0 15px; float:left; width:160px; ">
                    <strong><?php echo __("Show week numbers?",'wpbs') ;?></strong><br />
                    <select id="wpbs_calendar_weeknumbers" style="width: 160px;">
                        <option value="yes"><?php _e("Yes", "wpbs"); ?></option>
                        <option selected="selected" value="no"><?php _e("No", "wpbs"); ?></option>                        
                    </select> <br/>                    
                </div>
                
                <div style="padding:15px 15px 0 15px; float:left; width:160px; ">
                    <strong><?php echo __("Minimum Days?",'wpbs') ;?></strong><br />
                    <select id="wpbs_calendar_mindays" style="width: 160px;">
                        <option value="0">0</option>
                        <?php for($i=1;$i<=14;$i++):?>
                        <option value="<?php echo $i;?>"><?php echo $i;?></option>
                        <?php endfor;?>                        
                    </select> <br/>                    
                </div>
                
                
                
                <?php else:?>
                <p style="padding:15px 15px 0 15px;"><?php echo __("You have to create a calendar first.",'wpbs') ;?></p>
                <?php endif;?>
                
                
                
                
                <div style="padding:15px 15px 0 15px; clear:both;">
                    <h3>
                        <?php echo __("Form Options",'wpbs') ;?>
                    </h3>
                </div>
                <?php if($formRows > 0):?>
                <div style="padding:15px 15px 0 15px; float:left; width:160px; ">
                    <strong><?php echo __("Form",'wpbs') ;?></strong><br />
                    <select id="wpbs_form_id" style="width: 160px;">
                            <option value="no-form">Dont' display form</option>                                        
                        <?php foreach($forms as $form):?>
                            <option value="<?php echo absint($form['formID']) ?>"><?php echo esc_html(stripslashes($form['formTitle'])) ?></option>
                        <?php endforeach; ?>
                    </select> <br/>
                    
                </div>
                
                <div style="padding:15px 15px 0 15px; float:left; width:160px; ">
                    <strong><?php echo __("Form position",'wpbs') ;?></strong><br />
                    <select id="wpbs_form_position" style="width: 160px;">
                        <option selected="selected" value="below"><?php _e("Below the calendar", "wpbs"); ?></option>
                        <option value="side"><?php _e("To the right of the calendar", "wpbs"); ?></option>                        
                    </select> <br/>                    
                </div>
                
                
                <?php else:?>
                <p style="padding:15px 15px 0 15px;"><?php echo __("You have to create a form first.",'wpbs') ;?></p>
                <?php endif;?>
                
               
                <div style="clear:left; padding:15px 15px 0 15px;">
                    <?php if($formRows > 0 && $calendarRows > 0):?>
                    <input type="button" class="button-primary" value="<?php echo __("Insert Calendar",'wpbs') ;?>" onclick="wpbs_insert_shortcode();"/>&nbsp;&nbsp;&nbsp;
                    <?php endif;?>
                    <a class="button button-secondary" style="color: #333 !important;" href="#" onclick="tb_remove(); return false;"><?php echo __("Cancel",'wpbs') ;?></a>
                </div>
            </div>
        </div>
    </div>
    
    <?php
}