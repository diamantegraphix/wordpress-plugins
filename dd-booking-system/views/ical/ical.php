<?php global $wpdb;?>
<div class="wrap wpbs-wrap">
    <div id="icon-themes" class="icon32"></div>
    <h2><?php _e('Sync');?></h2>
    
    <?php $wpbsOptions = json_decode(get_option('wpbs-options'),true); if(@$wpbsOptions['enableiCal'] != 'yes'):?>
        <div class="error settings-error notice"> 
            <p><?php echo __("Sync is disabled. Please go to the settings page to enable it.",'wpbs') ;?></p>
        </div>
    <?php else:?>
        <?php $sql = 'SELECT * FROM ' . $wpdb->prefix . 'bs_calendars';?>
        <?php $rows = $wpdb->get_results( $sql, ARRAY_A );?>
        
        <?php if($wpdb->num_rows > 0):?>
        <table class="widefat wp-list-table wpbs-table wpbs-table-ical wpbs-table-800">
            <thead>
                <tr>
                    <th class="wpbs-table-id"><?php echo __('ID','wpbs')?></th>
                    <th><?php echo __('Calendar Title','wpbs')?></th>   
                    <th style="width: 80%;"><?php echo __('iCalendar Link','wpbs')?></th>
                    <th style="width: 80%;"><?php echo __('Options','wpbs')?></th>
                    
                </tr>
            </thead>
            
            <tbody>                
                <?php $i=0; foreach($rows as $calendar):
                if( ! (current_user_can( 'manage_options' ) || @in_array( get_current_user_id(), json_decode($calendar['calendarUsers']) )) ) continue;?>
                
                
                
                <tr<?php if($i++%2==0):?> class="alternate"<?php endif;?>>
                    <td class="wpbs-table-id">#<?php echo $calendar['calendarID']; ?></td>
                    <td class="post-title page-title column-title wpbs-table-ical-title">
                        <strong><?php echo $calendar['calendarTitle']; ?></strong>
                       
                    </td>
                    <td><span class="wpbs-ical-link" onclick="wpbs_select_text(this);"><?php echo site_url();?>/?wp-booking-system-ical=<?php echo $calendar['calendarHash'];?></span></td>
                    <td class="wpbs-table-options"><a onclick="return confirm('<?php echo __("Are you sure you want to reset the private key? The current key will no longer work.",'wpbs') ;?>');" href="<?php echo admin_url( 'admin.php?page=wp-booking-system&do=reset-private-key&id=' . $calendar['calendarID'] . '&noheader=true' );?>">reset private link</a></td>
                </tr>
                <?php endforeach;?>
            </tbody>
        </table>
        <?php else:?>
            <?php echo __('No calendars found.','wpbs')?> <a href="<?php echo admin_url( 'admin.php?page=wp-booking-system&do=edit-calendar');?>"><?php echo __("Click here to create your first calendar.",'wpbs') ;?></a>
        <?php endif;?>
    <?php endif;?>
</div>