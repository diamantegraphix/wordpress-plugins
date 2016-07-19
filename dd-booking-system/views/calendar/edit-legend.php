<?php global $wpdb;?>
<div class="wrap wpbs-wrap">
    <div id="icon-themes" class="icon32"></div>
    <h2><?php echo __("Edit Legend",'wpbs') ;?> <a href="<?php echo admin_url( 'admin.php?page=wp-booking-system&do=edit-legend-item&id=' . $_GET['id']);?>" class="add-new-h2"><?php echo __("Add New",'wpbs') ;?></a></h2>
    <?php if(!empty($_GET['save']) && $_GET['save'] == 'ok'):?>
    <div id="message" class="updated">
        <p><?php echo __('The calendar was updated','wpbs')?></p>
    </div>
    <?php endif;?>
    
    <?php $sql = $wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bs_calendars WHERE calendarID=%d',$_GET['id']); ?>
    <?php $calendar = $wpdb->get_row( $sql, ARRAY_A );?>
    <?php if($wpdb->num_rows > 0):?>    
        <?php echo wpbs_print_legend_css($calendar['calendarLegend'], $calendar['calendarID']); ?>
        <div class="wpbs-buttons-wrapper">
            <a href="<?php echo admin_url( 'admin.php?page=wp-booking-system&do=edit-calendar&id=' . $_GET['id']);?>" class="button button-secondary button-h2 button-h2-back"><?php echo __("Back",'wpbs') ;?></a>
        </div>
        <table class="widefat wp-list-table wpbs-table wpbs-table-legend wpbs-table-800 wpbs-calendar-<?php echo $calendar['calendarID'];?>">
            <thead>
                <tr>
                    <th><?php echo __('Title','wpbs')?></th>
                    <th class="wpbs-responsive-options"><?php echo __('Options','wpbs')?></th>
                    <th><?php echo __('Color','wpbs')?></th>   
                    <th><?php echo __('Default','wpbs')?></th>
                    <th><?php echo __('Auto-Pending','wpbs')?></th>
                    <th><?php echo __('Visible','wpbs')?></th>
                    <th><?php echo __('Ordering','wpbs')?></th>
                    <th><?php echo __('Sync as booked','wpbs')?></th>
                </tr>
            </thead>
            <tbody> 
            <?php $i=0; foreach(json_decode($calendar['calendarLegend'],true) as $ID => $legendItem): ++$i;?>
                
                <tr <?php if($i%2==0):?> class="alternate"<?php endif;?>>
                    
                    <td class="post-title page-title column-title">
                        <strong><a class="row-title" href="<?php echo admin_url( 'admin.php?page=wp-booking-system&do=edit-legend-item&legendID='.$ID.'&id=' . $calendar['calendarID']);?>"><?php echo (!empty($legendItem['name'][wpbs_get_admin_language()])) ? $legendItem['name'][wpbs_get_admin_language()] : $legendItem['name']['default'];?></a></strong>
                        <div class="row-actions">
                            <span class="edit"><a href="<?php echo admin_url( 'admin.php?page=wp-booking-system&do=edit-legend-item&legendID='.$ID.'&id=' . $calendar['calendarID']);?>" title="<?php _e("Edit this item",'wpbs');?>"><?php _e("Edit",'wpbs');?></a></span>
                            <?php if($ID != 'default'):?>
                            <span class="trash"> | <a onclick="return confirm('<?php echo __("Are you sure you want to delete this legend item?",'wpbs') ;?>');" class="submitdelete" title="<?php echo __("Move this item to the Trash",'wpbs') ;?>" href="<?php echo admin_url( 'admin.php?page=wp-booking-system&do=legend-delete&legendID='.$ID.'&id=' . $calendar['calendarID'] . '&noheader=true');?>"><?php echo __("Delete",'wpbs') ;?></a></span>
                            <?php endif;?>
                        </div>
                    </td>
                    <td class="wpbs-responsive-options">
                        <?php if($ID != 'default'):?>
                            <a href="<?php echo admin_url( 'admin.php?page=wp-booking-system&do=legend-set-default&legendID='.$ID.'&id=' . $calendar['calendarID'] . '&noheader=true');?>"><?php echo _e('Make Default Legend','wpbs')?></a>
                        <?php else:?>
                            <?php echo _e('Default Legend','wpbs')?>
                        <?php endif;?>
                        <br />
                        <?php if(!empty($legendItem['auto-pending']) && $legendItem['auto-pending'] == 'yes'):?>
                            <?php echo _e('Auto-Pending Default','wpbs')?>
                        <?php else:?>
                            <a href="<?php echo admin_url( 'admin.php?page=wp-booking-system&do=legend-set-auto-pending&legendID='.$ID.'&id=' . $calendar['calendarID'] . '&noheader=true');?>"><?php echo _e('Make Auto-Pending Default','wpbs')?></a>
                        <?php endif;?>
                        <br />
                        <?php if(!empty($legendItem['hide']) && $legendItem['hide'] == 'hide'):?>
                            <a href="<?php echo admin_url( 'admin.php?page=wp-booking-system&do=legend-set-visibility&legendID='.$ID.'&id=' . $calendar['calendarID'] . '&noheader=true');?>"><?php echo _e('Hidden, click to show','wpbs')?>.</a>
                        <?php else:?>
                            <a href="<?php echo admin_url( 'admin.php?page=wp-booking-system&do=legend-set-visibility&legendID='.$ID.'&id=' . $calendar['calendarID'] . '&noheader=true');?>"><?php echo _e('Visible, click to hide','wpbs')?>.</a>
                        <?php endif;?>
                        <br />
                        <?php if($i != 1):?>
                            <a href="<?php echo admin_url( 'admin.php?page=wp-booking-system&do=legend-set-order&direction=up&legendID='.$ID.'&id=' . $calendar['calendarID'] . '&noheader=true');?>"><?php echo _e('Move Up','wpbs')?> </a>
                        <?php endif;?>     
                        <?php if($i != 1 && $i != count(json_decode($calendar['calendarLegend'],true))):?>-<?php endif;?>                                       
                        <?php if($i != count(json_decode($calendar['calendarLegend'],true))):?>
                            <a href="<?php echo admin_url( 'admin.php?page=wp-booking-system&do=legend-set-order&direction=down&legendID='.$ID.'&id=' . $calendar['calendarID'] . '&noheader=true');?>"><?php echo _e('Move Down','wpbs')?> </a><?php endif;?>
                        <br />
                        <?php if(!empty($legendItem['sync']) && $legendItem['sync'] == 'yes'):?>
                            <a href="<?php echo admin_url( 'admin.php?page=wp-booking-system&do=legend-set-sync&legendID='.$ID.'&id=' . $calendar['calendarID'] . '&noheader=true');?>"><?php echo _e('Syncing, click to remove sync','wpbs')?>.</a>
                        <?php else:?>                            
                            <a href="<?php echo admin_url( 'admin.php?page=wp-booking-system&do=legend-set-sync&legendID='.$ID.'&id=' . $calendar['calendarID'] . '&noheader=true');?>"><?php echo _e('Not Syncing, click to sync','wpbs')?>.</a>
                        <?php endif;?>
                        
                    </td>
                    <td class="status-icon">
                        <div class="wpbs-select-status wpbs-legend-color-edit status-<?php echo $ID;?>">
                            <div class="wpbs-day-split-top wpbs-day-split-top-<?php echo $ID;?>"></div>
                            <div class="wpbs-day-split-bottom wpbs-day-split-bottom-<?php echo $ID;?>"></div>  
                        </div>
                    </td>
                    <td class="middle default-icon">
                        <?php if($ID != 'default'):?>
                            <a href="<?php echo admin_url( 'admin.php?page=wp-booking-system&do=legend-set-default&legendID='.$ID.'&id=' . $calendar['calendarID'] . '&noheader=true');?>"><img src="<?php echo WPBS_PATH;?>/images/not-default-legend.png" /></a>
                        <?php else:?>
                            <img class="default-icon" src="<?php echo WPBS_PATH;?>/images/default-legend.png" />
                        <?php endif;?>
                    </td>
                    
                    <td class="middle default-icon auto-pending">
                        <?php if(!empty($legendItem['auto-pending']) && $legendItem['auto-pending'] == 'yes'):?>
                            <img class="default-icon" src="<?php echo WPBS_PATH;?>/images/default-legend.png" />
                        <?php else:?>
                            <a href="<?php echo admin_url( 'admin.php?page=wp-booking-system&do=legend-set-auto-pending&legendID='.$ID.'&id=' . $calendar['calendarID'] . '&noheader=true');?>"><img src="<?php echo WPBS_PATH;?>/images/not-default-legend.png" /></a>
                        <?php endif;?>
                    </td>
                    
                    <td class="middle visible-icon">
                        <?php if(!empty($legendItem['hide']) && $legendItem['hide'] == 'hide'):?>
                            <a href="<?php echo admin_url( 'admin.php?page=wp-booking-system&do=legend-set-visibility&legendID='.$ID.'&id=' . $calendar['calendarID'] . '&noheader=true');?>"><img src="<?php echo WPBS_PATH;?>/images/visible-no.png" /></a>
                        <?php else:?>
                            <a href="<?php echo admin_url( 'admin.php?page=wp-booking-system&do=legend-set-visibility&legendID='.$ID.'&id=' . $calendar['calendarID'] . '&noheader=true');?>"><img src="<?php echo WPBS_PATH;?>/images/visible-yes.png" /></a>
                        <?php endif;?>
                    </td>
                    <td class="middle order-icon <?php if($i != count(json_decode($calendar['calendarLegend'],true)) && $i !=1 ):?>double<?php endif;?>">
                        <?php if($i != 1):?>
                            <a href="<?php echo admin_url( 'admin.php?page=wp-booking-system&do=legend-set-order&direction=up&legendID='.$ID.'&id=' . $calendar['calendarID'] . '&noheader=true');?>"><img src="<?php echo WPBS_PATH;?>/images/up-arrow.png" /></a>
                        <?php endif;?>                                            
                        <?php if($i != count(json_decode($calendar['calendarLegend'],true))):?>
                            <a href="<?php echo admin_url( 'admin.php?page=wp-booking-system&do=legend-set-order&direction=down&legendID='.$ID.'&id=' . $calendar['calendarID'] . '&noheader=true');?>"><img src="<?php echo WPBS_PATH;?>/images/down-arrow.png" /></a><?php endif;?>
                    </td>
                    
                    <td class="middle visible-icon">
                        <?php if(!empty($legendItem['sync']) && $legendItem['sync'] == 'yes'):?>
                            <a href="<?php echo admin_url( 'admin.php?page=wp-booking-system&do=legend-set-sync&legendID='.$ID.'&id=' . $calendar['calendarID'] . '&noheader=true');?>"><img src="<?php echo WPBS_PATH;?>/images/visible-yes.png" /></a>
                        <?php else:?>                            
                            <a href="<?php echo admin_url( 'admin.php?page=wp-booking-system&do=legend-set-sync&legendID='.$ID.'&id=' . $calendar['calendarID'] . '&noheader=true');?>"><img src="<?php echo WPBS_PATH;?>/images/visible-no.png" /></a>
                        <?php endif;?>
                    </td>
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>


    <?php else:?>
        <?php echo __('Invalid calendar ID.','wpbs')?>
    <?php endif;?>     
</div>

