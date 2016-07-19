<?php
class wpbs_widget extends WP_Widget {
    public function __construct(){
        parent::__construct(false, $name = 'WP Booking System', array(
            'description' => 'WP Booking System Widget'
        ));
    }
    function widget($args, $instance) {
        global $post;
        extract( $args );        
        
        echo $args['before_widget'];
        
        echo '<div class="wpbs-widget">';       
        echo do_shortcode('[wpbs id="'.$instance['wpbs_select_calendar'].'" form="'.$instance['wpbs_select_form'].'" title="'.$instance['wpbs_show_title'].'" legend="'.$instance['wpbs_show_legend'].'" dropdown="'.$instance['wpbs_show_dropdown'].'" start="'.$instance['wpbs_calendar_start'].'" display="'.$instance['wpbs_calendar_view'].'" language="'.$instance['wpbs_calendar_language'].'" tooltip="'.$instance['wpbs_calendar_tooltip'].'" history="'.$instance['wpbs_calendar_history'].'" selection="'.$instance['wpbs_calendar_selection_type'].'" autopending="'.$instance['wpbs_calendar_auto_pending'].'" weeknumbers="'.$instance['wpbs_calendar_weeknumbers'].'" minimumdays="'.$instance['wpbs_calendar_minimumdays'].'" formposition="'.$instance['wpbs_form_position'].'"]');
        echo '</div>';
        
        echo $args['after_widget'];

    }
    function update($new_instance, $old_instance) {
        return $new_instance;
    }
    function form($instance) {
        global $wpdb;
        /**
        'id'        => null,
		'title'     => 'no',
        'legend'    => 'no',
        'start'     => '1',
        'display'   => '1',
        'language'  => 'en'
        */
        
        $calendarId = 0; if(!empty($instance['wpbs_select_calendar'])) 
            $calendarId = $instance['wpbs_select_calendar'];
            
        $formId = 0; if(!empty($instance['wpbs_select_form'])) 
            $formId = $instance['wpbs_select_form'];
        
        $showTitle = 'yes'; if(!empty($instance['wpbs_show_title'])) 
            $showTitle = $instance['wpbs_show_title'];
            
        $showLegend = 'yes'; if(!empty($instance['wpbs_show_legend'])) 
            $showLegend = $instance['wpbs_show_legend'];
        
        $showDropdown = 'yes'; if(!empty($instance['wpbs_show_dropdown'])) 
            $showDropdown = $instance['wpbs_show_dropdown'];

        $calendarView = '1'; if(!empty($instance['wpbs_calendar_view'])) 
            $calendarView = $instance['wpbs_calendar_view'];
            
        
        $calendarStart = '1'; if(!empty($instance['wpbs_calendar_start'])) 
            $calendarStart = $instance['wpbs_calendar_start'];
        
        $calendarLanguage = 'en'; if(!empty($instance['wpbs_calendar_language'])) 
            $calendarLanguage = $instance['wpbs_calendar_language'];
        
        $calendarHistory = '1'; if(!empty($instance['wpbs_calendar_history'])) 
            $calendarHistory = $instance['wpbs_calendar_history'];
        
        $calendarTooltip = '1'; if(!empty($instance['wpbs_calendar_tooltip'])) 
            $calendarTooltip = $instance['wpbs_calendar_tooltip'];
           
        $calendarSelectionType = 'multiple'; if(!empty($instance['wpbs_calendar_selection_type'])) 
            $calendarSelectionType = $instance['wpbs_calendar_selection_type'];
            
        $calendarAutoPending = 'no'; if(!empty($instance['wpbs_calendar_auto_pending'])) 
            $calendarAutoPending = $instance['wpbs_calendar_auto_pending'];    
        
        $calendarWeekNumbers = 'no'; if(!empty($instance['wpbs_calendar_weeknumbers'])) 
            $calendarWeekNumbers = $instance['wpbs_calendar_weeknumbers'];
        
        $calendarMinDays = 0; if(!empty($instance['wpbs_calendar_minimumdays'])) 
            $calendarMinDays = $instance['wpbs_calendar_minimumdays'];
        
        $formPosition = 'below'; if(!empty($instance['wpbs_form_position'])) 
            $formPosition = $instance['wpbs_form_position'];
            
        
            
            
            
            
            
        $sql = 'SELECT * FROM ' . $wpdb->prefix . 'bs_calendars';
        $rows = $wpdb->get_results( $sql, ARRAY_A );
        
        $formQuery = 'SELECT * FROM ' . $wpdb->prefix . 'bs_forms';
        $forms = $wpdb->get_results( $formQuery, ARRAY_A );
        
        ?>
        
        <h3><?php _e('Calendar options','wpbs');?></h3>
        
        <p>
            <label for="<?php echo $this->get_field_id('wpbs_select_calendar'); ?>"><?php echo __('Calendar','wpbs');?></label>
            <select name="<?php echo $this->get_field_name('wpbs_select_calendar'); ?>" id="<?php echo $this->get_field_id('wpbs_select_calendar'); ?>" class="widefat">
            <?php foreach($rows as $calendar):?>
                <option<?php if($calendar['calendarID']==$calendarId) echo ' selected="selected"';?> value="<?php echo $calendar['calendarID'];?>"><?php echo $calendar['calendarTitle'];?></option>
            <?php endforeach;?>   
            </select>
         </p> 
         
           
         <p>
            <label for="<?php echo $this->get_field_id('wpbs_show_title'); ?>"><?php echo __('Display title?','wpbs');?></label>
            <select name="<?php echo $this->get_field_name('wpbs_show_title'); ?>" id="<?php echo $this->get_field_id('wpbs_show_title'); ?>" class="widefat">
                <option value="yes"><?php _e("Yes", "wpbs"); ?></option>
                <option value="no"<?php if($showTitle=='no'):?> selected="selected"<?php endif;?>><?php _e("No", "wpbs"); ?></option>
            </select>
         </p>   
         <p>   
            <label for="<?php echo $this->get_field_id('wpbs_show_legend'); ?>"><?php echo __('Display legend?','wpbs');?></label>
            <select name="<?php echo $this->get_field_name('wpbs_show_legend'); ?>" id="<?php echo $this->get_field_id('wpbs_show_legend'); ?>" class="widefat">
                <option value="yes"><?php _e("Yes", "wpbs"); ?></option>
                <option value="no"<?php if($showLegend=='no'):?> selected="selected"<?php endif;?>><?php _e("No", "wpbs"); ?></option>
            </select>
         </p>  
         <p>   
            <label for="<?php echo $this->get_field_id('wpbs_show_dropdown'); ?>"><?php echo __('Display dropdown?','wpbs');?></label>
            <select name="<?php echo $this->get_field_name('wpbs_show_dropdown'); ?>" id="<?php echo $this->get_field_id('wpbs_show_dropdown'); ?>" class="widefat">
                <option value="yes"><?php _e("Yes", "wpbs"); ?></option>
                <option value="no"<?php if($showDropdown=='no'):?> selected="selected"<?php endif;?>><?php _e("No", "wpbs"); ?></option>
            </select>
         </p>   
         <p>   
            <label for="<?php echo $this->get_field_id('wpbs_calendar_start'); ?>"><?php echo __('Week starts on','wpbs');?></label>
            <select name="<?php echo $this->get_field_name('wpbs_calendar_start'); ?>" id="<?php echo $this->get_field_id('wpbs_calendar_start'); ?>" class="widefat">
            
                <option value="1"<?php if($calendarStart==1):?> selected="selected"<?php endif;?>><?php _e("Monday", "wpbs"); ?></option>
                <option value="2"<?php if($calendarStart==2):?> selected="selected"<?php endif;?>><?php _e("Tuesday", "wpbs"); ?></option>
                <option value="3"<?php if($calendarStart==3):?> selected="selected"<?php endif;?>><?php _e("Wednesday", "wpbs"); ?></option>
                <option value="4"<?php if($calendarStart==4):?> selected="selected"<?php endif;?>><?php _e("Thursday", "wpbs"); ?></option>
                <option value="5"<?php if($calendarStart==5):?> selected="selected"<?php endif;?>><?php _e("Friday", "wpbs"); ?></option>
                <option value="6"<?php if($calendarStart==6):?> selected="selected"<?php endif;?>><?php _e("Saturday", "wpbs"); ?></option>
                <option value="7"<?php if($calendarStart==7):?> selected="selected"<?php endif;?>><?php _e("Sunday", "wpbs"); ?></option>
            </select>
         </p>   
         <p>   
            <label for="<?php echo $this->get_field_id('wpbs_calendar_view'); ?>"><?php echo __('Calendar View','wpbs');?></label>
            <select name="<?php echo $this->get_field_name('wpbs_calendar_view'); ?>" id="<?php echo $this->get_field_id('wpbs_calendar_view'); ?>" class="widefat">
                <?php for($i=1;$i<=12; $i++):?>
                    <option value="<?php echo $i;?>"<?php if($calendarView==$i):?> selected="selected"<?php endif;?>><?php echo $i;?></option>
                <?php endfor;?>
            </select>
        </p>   
         <p>    
            <label for="<?php echo $this->get_field_id('wpbs_calendar_language'); ?>"><?php echo __('Language','wpbs');?></label>
            <select name="<?php echo $this->get_field_name('wpbs_calendar_language'); ?>" id="<?php echo $this->get_field_id('wpbs_calendar_language'); ?>" class="widefat">
                <option value="auto"><?php echo __("Auto (let WP choose)",'wpbs') ;?></option>
                <?php $activeLanguages = json_decode(get_option('wpbs-languages'),true);?>
                <?php foreach($activeLanguages as $code => $language):?>
                    <option value="<?php echo $code;?>"<?php if($calendarLanguage == $code):?> selected="selected"<?php endif;?>><?php echo $language;?></option>
                <?php endforeach;?>   
            </select>
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('wpbs_calendar_tooltip'); ?>"><?php _e("Show Tooltip?", "wpbs"); ?></label>
            <select name="<?php echo $this->get_field_name('wpbs_calendar_tooltip'); ?>" id="<?php echo $this->get_field_id('wpbs_calendar_tooltip'); ?>" class="widefat">
                <option <?php if($calendarTooltip == '1'):?> selected="selected"<?php endif;?> value="1"><?php _e("No", "wpbs"); ?></option>
                <option <?php if($calendarTooltip == '2'):?> selected="selected"<?php endif;?> value="2"><?php _e("Yes", "wpbs"); ?></option>
                <option <?php if($calendarTooltip == '3'):?> selected="selected"<?php endif;?> value="3"><?php _e("Yes, with red indicator", "wpbs"); ?></option>
                            
            </select>              

        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('wpbs_calendar_history'); ?>"><?php _e("Show history?", "wpbs"); ?></label>
            <select name="<?php echo $this->get_field_name('wpbs_calendar_history'); ?>" id="<?php echo $this->get_field_id('wpbs_calendar_history'); ?>" class="widefat">
                <option <?php if($calendarHistory == 1):?> selected="selected"<?php endif;?> value="1"><?php echo __("Display booking history",'wpbs') ;?></option>
                <option <?php if($calendarHistory == 2):?> selected="selected"<?php endif;?> value="2"><?php echo __("Replace booking history with the default legend item",'wpbs') ;?></option>
                <option <?php if($calendarHistory == 3):?> selected="selected"<?php endif;?> value="3"><?php echo __("Use the Booking History Color from the Settings",'wpbs') ;?></option>                        
            </select>              

        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('wpbs_calendar_selection_type'); ?>"><?php _e("Selection Type?", "wpbs"); ?></label>
            <select name="<?php echo $this->get_field_name('wpbs_calendar_selection_type'); ?>" id="<?php echo $this->get_field_id('wpbs_calendar_selection_type'); ?>" class="widefat">
                <option <?php if($calendarSelectionType == 'multiple'):?> selected="selected"<?php endif;?> value="multiple"><?php _e("Multiple Dates (Range)", "wpbs"); ?></option>
                <option <?php if($calendarSelectionType == 'single'):?> selected="selected"<?php endif;?> value="single"><?php _e("Single Dates", "wpbs"); ?></option>
                            
            </select>              

        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('wpbs_calendar_auto_pending'); ?>"><?php _e("Auto Pending?", "wpbs"); ?></label>
            <select name="<?php echo $this->get_field_name('wpbs_calendar_auto_pending'); ?>" id="<?php echo $this->get_field_id('wpbs_calendar_auto_pending'); ?>" class="widefat">
                <option <?php if($calendarAutoPending == 'no'):?> selected="selected"<?php endif;?> value="no"><?php _e("No", "wpbs"); ?></option>
                <option <?php if($calendarAutoPending == 'yes'):?> selected="selected"<?php endif;?> value="yes"><?php _e("Yes", "wpbs"); ?></option>
                            
            </select>              

        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('wpbs_calendar_weeknumbers'); ?>"><?php _e("Show week numbers?", "wpbs"); ?></label>
            <select name="<?php echo $this->get_field_name('wpbs_calendar_weeknumbers'); ?>" id="<?php echo $this->get_field_id('wpbs_calendar_weeknumbers'); ?>" class="widefat">
                <option <?php if($calendarWeekNumbers == 'no'):?> selected="selected"<?php endif;?> value="no"><?php _e("No", "wpbs"); ?></option>
                <option <?php if($calendarWeekNumbers == 'yes'):?> selected="selected"<?php endif;?> value="yes"><?php _e("Yes", "wpbs"); ?></option>
                            
            </select>              

        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('wpbs_calendar_minimumdays'); ?>"><?php _e("Minimum days to book?", "wpbs"); ?></label>
            <select name="<?php echo $this->get_field_name('wpbs_calendar_minimumdays'); ?>" id="<?php echo $this->get_field_id('wpbs_calendar_minimumdays'); ?>" class="widefat">
                <?php for($i=0;$i<=14;$i++):?>
                <option <?php if($calendarMinDays == $i):?> selected="selected"<?php endif;?> value="<?php echo $i;?>"><?php echo $i;?></option>
                <?php endfor;?>
                
                            
            </select>              

        </p>
        
        <h3><?php _e('Form options','wpbs');?></h3>
        
        <p>
            <label for="<?php echo $this->get_field_id('wpbs_select_form'); ?>"><?php echo __('Form','wpbs');?></label>
            <select name="<?php echo $this->get_field_name('wpbs_select_form'); ?>" id="<?php echo $this->get_field_id('wpbs_select_form'); ?>" class="widefat">
            <option value="no-form"><?php _e("Don't display form",'wpbs');?></option>           
            <?php foreach($forms as $form):?>
                <option<?php if($form['formID']==$formId) echo ' selected="selected"';?> value="<?php echo $form['formID'];?>"><?php echo $form['formTitle'];?></option>
            <?php endforeach;?>   
            </select>
         </p> 
         
         <p>
            <label for="<?php echo $this->get_field_id('wpbs_form_position'); ?>"><?php _e("Form position", "wpbs"); ?></label>
            <select name="<?php echo $this->get_field_name('wpbs_form_position'); ?>" id="<?php echo $this->get_field_id('wpbs_form_position'); ?>" class="widefat">
                <option <?php if($formPosition == 'below'):?> selected="selected"<?php endif;?> value="below"><?php _e("Below the calendar", "wpbs"); ?></option>
                <option <?php if($formPosition == 'side'):?> selected="selected"<?php endif;?> value="side"><?php _e("To the right of the calendar", "wpbs"); ?></option>
                            
            </select>              

        </p>
        
        
        <?php
    }
}
function wpbs_register_widget() {
	register_widget( 'wpbs_widget' );
}
add_action( 'widgets_init', 'wpbs_register_widget' );