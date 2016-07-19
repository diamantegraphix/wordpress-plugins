<?php global $wpdb;?>
<div class="wrap wpbs-wrap">
    <div id="icon-themes" class="icon32"></div>
    <h2><?php echo __("Forms",'wpbs') ;?> <a href="<?php echo admin_url( 'admin.php?page=wp-booking-system-forms&do=edit-form');?>" class="add-new-h2"><?php echo __("Add New",'wpbs') ;?></a></h2>
    <?php if(!empty($status) && $status == 1):?>
    <div id="message" class="updated">
        <p><?php echo __('The form was updated','wpbs')?></p>
    </div>
    <?php endif;?>

    
    <?php $sql = 'SELECT * FROM ' . $wpdb->prefix . 'bs_forms';?>
    <?php $rows = $wpdb->get_results( $sql, ARRAY_A );?>
    
    <?php if($wpdb->num_rows > 0):?>
    <table class="widefat wp-list-table wpbs-table wpbs-table-forms wpbs-table-800">
        <thead>
            <tr>
                <th class="wpbs-table-id"><?php echo __('ID','wpbs')?></th>
                <th><?php echo __('Form Title','wpbs')?></th>   
                
            </tr>
        </thead>
        
        <tbody>                
            <?php $i=0; foreach($rows as $form):?>
            <tr<?php if($i++%2==0):?> class="alternate"<?php endif;?>>
                <td class="wpbs-table-id">#<?php echo $form['formID']; ?></td>
                <td class="post-title page-title column-title">
                    <strong><a class="row-title" href="<?php echo admin_url( 'admin.php?page=wp-booking-system-forms&do=edit-form&id=' . $form['formID']);?>"><?php echo stripslashes($form['formTitle']); ?></a></strong>
                    <div class="row-actions">
                        <span class="edit"><a href="<?php echo admin_url( 'admin.php?page=wp-booking-system-forms&do=edit-form&id=' . $form['formID']);?>" title="<?php echo __("Edit this item",'wpbs') ;?>"><?php echo __("Edit",'wpbs') ;?></a> | </span>
                        <span class="trash"><a onclick="return confirm('<?php echo __("Are you sure you want to delete this form?",'wpbs') ;?>');" class="submitdelete" href="<?php echo admin_url( 'admin.php?page=wp-booking-system-forms&do=delete-form&id=' . $form['formID'] . '&noheader=true');?>"><?php echo __("Delete",'wpbs') ;?></a></span>
                    </div>
                </td>
               
            </tr>
            <?php endforeach;?>
        </tbody>
    </table>
    <?php else:?>
        <?php echo __('No forms found.','wpbs')?> <a href="<?php echo admin_url( 'admin.php?page=wp-booking-system-forms&do=edit-form');?>"><?php echo __("Click here to create your first form.",'wpbs') ;?></a>
    <?php endif;?>
</div>