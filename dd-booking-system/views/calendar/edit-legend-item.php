<?php global $wpdb;?>
<div class="wrap wpbs-wrap">
    <div id="icon-themes" class="icon32"></div>
    <h2><?php echo __("Edit Legend",'wpbs') ;?> </h2>
    <?php if(!empty($_GET['save']) && $_GET['save'] == 'ok'):?>
    <div id="message" class="updated">
        <p><?php echo __('The calendar was updated','wpbs')?></p>
    </div>
    <?php endif;?>
    
    <?php $sql = $wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bs_calendars WHERE calendarID=%d',$_GET['id']); ?>
    <?php $calendar = $wpdb->get_row( $sql, ARRAY_A );?>
    <?php if($wpdb->num_rows > 0): $legendID = (!empty($_GET['legendID'])) ? $_GET['legendID'] : ''; 
    $calendarLegend = json_decode($calendar['calendarLegend'],true); if(!empty($_GET['legendID'])) $legend = $calendarLegend[$legendID]; ?>    
        <div class="postbox-container meta-box-sortables">
            <form action="<?php echo admin_url( 'admin.php?page=wp-booking-system&do=save-legend&noheader=true');?>" method="post">
             
            <input type="hidden" name="legendID" value="<?php echo $legendID;?>" />
            <input type="hidden" name="calendarID" value="<?php echo $calendar['calendarID'];?>" />
            <input type="text" class="fullTitle" name="legendTitle" id="legendTitle" placeholder="<?php echo __("Legend title",'wpbs') ;?>" value="<?php echo (!empty($legend['name']['default'])) ? $legend['name']['default'] : "" ;?>"/>
            <div class="wpbs-buttons-wrapper">
                <input type="submit" class="button button-primary button-h2 saveCalendar" value="<?php echo __("Save Changes",'wpbs') ;?>" />
                <a class="button secondary-button button-h2 button-h2-back-margin" href="<?php echo admin_url( 'admin.php?page=wp-booking-system&do=edit-legend&id=' . $_GET['id']);?>"><?php echo __("Back",'wpbs') ;?></a>
            </div>
            <div class="metabox-holder">
                <div class="postbox">
                    <div class="handlediv" title="<?php echo __("Click to toggle",'wpbs') ;?>"><br /></div>
                    <h3 class="hndle"><?php echo __("Legend Item",'wpbs') ;?></h3>
                    <div class="inside edit-legend-container">

                        <div class="edit-legend-top">
                            <label><strong><?php echo __("Color:",'wpbs') ;?></strong></label><span class="color-box" id="color-box"  style="background-color:<?php if(!empty($legend['color'])) echo $legend['color'];?>;"><!-- --></span>
                            <input class="small" type="text" id="colorPicker" name="color" value="<?php if(!empty($legend['color'])) echo $legend['color'];?>" />
                            
                            <span class="color-wrapper"><input class="split-color" name="activeSplitColor" type="checkbox" <?php if(!empty($legend['splitColor']) || !empty($post['splitColor'])):?> checked="checked"<?php endif;?> /> <?php echo __("Split Color",'wpbs') ;?></span>
                            
                            <div class="show-split-color <?php if(!(!empty($legend['splitColor']) || !empty($post['splitColor']))):?> wpbs-hide<?php endif;?>">                                
                                <span class="color-box" id="split-color-box" style="background-color:<?php if(!empty($legend['splitColor'])) echo $legend['splitColor'];?>;"><!-- --></span>
                                <input class="small" type="text" id="splitColorPicker" name="splitColor" value="<?php if(!empty($legend['splitColor'])) echo $legend['splitColor'];?>" />
                                
                            </div>
                        </div> 
                          <div class="wpbs-clear"><!-- --></div>
                          
                          <div class="wpbs-can-be-booked">
                            <label><strong><?php echo __("Can be booked",'wpbs') ;?></strong></label>
                            <input type="checkbox" name="bookable" <?php if(!empty($legend['bookable']) || !empty($post['bookable'])):?> checked="checked"<?php endif;?> />
                          </div>
                          <hr />

                            <?php $activeLanguages = json_decode(get_option('wpbs-languages'),true);?>
                            
                            <label><strong><?php echo __("Translations",'wpbs') ;?></strong></label>
                            <ul class="wpbs-legend-translations">
                                <?php $i=0; foreach($activeLanguages as $languageCode => $languageName): ++$i;?>
                                <li <?php if($i%2==0): ?> class="odd" <?php endif ;?>>
                                    <label><img src="<?php echo WPBS_PATH;?>/images/flags/<?php echo $languageCode;?>.png" /><?php echo $languageName;?>:</label>
                                    <input type="text" name="<?php echo $languageCode;?>" value="<?php if(!empty($legend['name'][$languageCode])) echo $legend['name'][$languageCode];?>" />
                                </li>
                                <?php endforeach;?>
                            </ul>
                        <div class="wpbs-clear"><!-- --></div>
                        
                    </div>
                </div>
            </div>
            <br /><input type="submit" class="button button-primary saveCalendar" value="<?php echo __("Save Changes",'wpbs') ;?>" /> 
            </form>
        </div>

<script>
var wpbs = jQuery.noConflict();
wpbs(document).ready(function(){
    wpbs('#colorPicker').ColorPicker({
			color: '<?php if(!empty($legend['color'])) echo $legend['color'];?>',
			onShow: function (colpkr) {
				wpbs(colpkr).fadeIn(500);
				return false;
			},
			onHide: function (colpkr) {
				wpbs(colpkr).fadeOut(500);
				return false;
			},
			onChange: function (hsb, hex, rgb) {
				wpbs('#color-box').css('backgroundColor', '#' + hex);
                wpbs("#colorPicker").val('#' + hex);
			}
		});
  wpbs('#splitColorPicker').ColorPicker({
			color: '<?php if(!empty($legend['splitColor'])) echo $legend['splitColor'];?>',
			onShow: function (colpkr) {
				wpbs(colpkr).fadeIn(500);
				return false;
			},
			onHide: function (colpkr) {
				wpbs(colpkr).fadeOut(500);
				return false;
			},
			onChange: function (hsb, hex, rgb) {
				wpbs('#split-color-box').css('backgroundColor', '#' + hex);
                wpbs("#splitColorPicker").val('#' + hex);
			}
		});
  wpbs('input.split-color').click(function(){
        if(wpbs(this).prop('checked')){
            wpbs(".show-split-color").removeClass('wpbs-hide');
        } else {
            wpbs(".show-split-color").addClass('wpbs-hide');
        }
  })
})
</script>
    <?php else:?>
        <?php echo __('Invalid calendar ID.','wpbs')?>
    <?php endif;?>     
</div>

