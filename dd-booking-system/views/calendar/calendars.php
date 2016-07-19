<?php global $wpdb;?>
<div class="wrap wpbs-wrap">
    <div id="icon-themes" class="icon32"></div>
    <h2><?php echo __("WP Booking System",'wpbs') ;?> <a href="<?php echo admin_url( 'admin.php?page=wp-booking-system&do=edit-calendar');?>" class="add-new-h2"><?php echo __("Add New",'wpbs') ;?></a></h2>
    <?php if(!empty($status) && $status == 1):?>
    <div id="message" class="updated">
        <p><?php echo __('The calendar was updated','wpbs')?></p>
    </div>
    <?php endif;?>

    
    <?php $sql = 'SELECT * FROM ' . $wpdb->prefix . 'bs_calendars';?>
    <?php $rows = $wpdb->get_results( $sql, ARRAY_A );?>
    
    <?php if($wpdb->num_rows > 0):?>
    <table class="widefat wp-list-table wpbs-table wpbs-table-calendars wpbs-table-800">
        <thead>
            <tr>
                <th class="wpbs-table-id"><?php echo __('ID','wpbs')?></th>
                <th><?php echo __('Calendar Title','wpbs')?></th>   
                <th><?php echo __('Date Created','wpbs')?></th>
                <th><?php echo __('Date Modified','wpbs')?></th>
                <th><?php echo __('Unread Bookings','wpbs')?></th>
                <th><?php echo __('Total Bookings','wpbs')?></th>
            </tr>
        </thead>
        
        <tbody>                
            <?php $i=0; foreach($rows as $calendar):
            if( ! (current_user_can( 'manage_options' ) || @in_array( get_current_user_id(), json_decode($calendar['calendarUsers']) )) ) continue;?>
            <?php $bCount = 'SELECT bookingRead FROM ' . $wpdb->prefix . 'bs_bookings WHERE calendarID = '.$calendar['calendarID'].' AND bookingRead=0';?>
            <?php $wpdb->get_results( $bCount, ARRAY_A ); ?>
            
            
            <tr<?php if($i++%2==0):?> class="alternate"<?php endif;?>>
                <td class="wpbs-table-id">#<?php echo $calendar['calendarID']; ?></td>
                <td class="post-title page-title column-title">
                    <strong><a class="row-title" href="<?php echo admin_url( 'admin.php?page=wp-booking-system&do=edit-calendar&id=' . $calendar['calendarID']);?>"><?php echo $calendar['calendarTitle']; ?></a><div class='wpbs-count wpbs-count-<?php echo $wpdb->num_rows;?>'><?php echo $wpdb->num_rows;?></div></strong>
                    <div class="row-actions">
                        <span class="edit"><a href="<?php echo admin_url( 'admin.php?page=wp-booking-system&do=edit-calendar&id=' . $calendar['calendarID']);?>" title="<?php echo __("Edit this item",'wpbs') ;?>"><?php echo __("Edit",'wpbs') ;?></a> | </span>
                        <span class="trash"><a onclick="return confirm('<?php echo __("Are you sure you want to delete this calendar?",'wpbs') ;?>');" class="submitdelete" href="<?php echo admin_url( 'admin.php?page=wp-booking-system&do=calendar-delete&id=' . $calendar['calendarID'] . '&noheader=true');?>"><?php echo __("Delete",'wpbs') ;?></a></span>
                    </div>
                </td>
                <td><?php echo wpbs_timeFormat($calendar['createdDate'])?></td>
                <td><?php echo wpbs_timeFormat($calendar['modifiedDate']) ?></td>
                <td><?php echo $wpdb->num_rows;?> <?php ($wpdb->num_rows == 1) ? _e('booking','wpbs') : _e('bookings','wpbs');?></td>
                <td>
                    <?php $totalBookings = 'SELECT bookingID FROM ' . $wpdb->prefix . 'bs_bookings WHERE calendarID = '.$calendar['calendarID'].'';?>
                    <?php $wpdb->get_results( $totalBookings, ARRAY_A ); echo $wpdb->num_rows;?> <?php ($wpdb->num_rows == 1) ? _e('booking','wpbs') : _e('bookings','wpbs');?></td>
            </tr>
            <?php endforeach;?>
        </tbody>
    </table>
    <?php else:?>
        <?php echo __('No calendars found.','wpbs')?> <a href="<?php echo admin_url( 'admin.php?page=wp-booking-system&do=edit-calendar');?>"><?php echo __("Click here to create your first calendar.",'wpbs') ;?></a>
    <?php endif;?>
</div>