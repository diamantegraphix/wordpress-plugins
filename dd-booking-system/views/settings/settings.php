<?php global $wpdb;?>

<div class="wrap wpbs-wrap">
    <div id="icon-themes" class="icon32"></div>
    <h2><?php echo __("Settings",'wpbs') ;?></h2>
    <?php if(!empty($_GET['save']) && $_GET['save'] == 'ok'):?>
    <div id="message" class="updated">
        <p><?php echo __('The settings were saved.','wpbs')?></p>
    </div>
    <?php endif;?>
    
        <div class="postbox-container meta-box-sortables">
            
            <form action="<?php echo admin_url( 'admin.php?page=wp-booking-system-settings&do=save&noheader=true');?>" method="post">
            <div class="wpbs-buttons-wrapper">
                <input type="submit" class="button button-primary button-h2" value="<?php echo __("Save Changes",'wpbs') ;?>" /> 
            </div>            
            <div class="metabox-holder">
                <div class="postbox">
                    <div class="handlediv" title="<?php echo __("Click to toggle",'wpbs') ;?>"><br /></div>
                    <h3 class="hndle"><?php echo __("General Settings",'wpbs') ;?></h3>
                    <div class="inside">     
                        <?php $wpbsOptions = json_decode(get_option('wpbs-options'),true);?>  
                        <div class="wpbs-settings-col">
                            <div class="wpbs-settings-col-left">
                                <strong><?php echo __("Date Format",'wpbs') ;?></strong>                                
                            </div>
                            <div class="wpbs-settings-col-right wpbs-date-format">
                                <label><input class="small" type="radio" id="" name="dateFormat" <?php if($wpbsOptions['dateFormat'] == 'j F Y'): ?>checked="checked"<?php endif;?> value="j F Y" /> 25 July 2013</label>
                                <label><input class="small" type="radio" id="" name="dateFormat"<?php if($wpbsOptions['dateFormat'] == 'F j, Y'): ?>checked="checked"<?php endif;?> value="F j, Y" /> July 25, 2013</label>
                                <label><input class="small" type="radio" id="" name="dateFormat"<?php if($wpbsOptions['dateFormat'] == 'Y/m/d'): ?>checked="checked"<?php endif;?> value="Y/m/d" /> 2013/07/25</label>
                                <label><input class="small" type="radio" id="" name="dateFormat"<?php if($wpbsOptions['dateFormat'] == 'm/d/Y'): ?>checked="checked"<?php endif;?> value="m/d/Y" /> 07/25/2013</label>
                                <label><input class="small" type="radio" id="" name="dateFormat"<?php if($wpbsOptions['dateFormat'] == 'd/m/Y'): ?>checked="checked"<?php endif;?> value="d/m/Y" /> 25/07/2013</label>
                            </div>
                            <div class="wpbs-clear"></div>                            
                        </div>                  
                        <div class="wpbs-settings-col wpbs-colorpicker">
                            <div class="wpbs-settings-col-left">
                                <strong><?php echo __("Selected date background",'wpbs') ;?></strong>                                
                            </div>
                            <div class="wpbs-settings-col-right">
                                <span class="color-box" id="selectedColorBox"  style="background-color:<?php echo $wpbsOptions['selectedColor'];?>"><!-- --></span>
                                <input class="small" type="text" id="selectedColor" name="selectedColor" value="<?php echo $wpbsOptions['selectedColor'];?>" /> 
                                <small><?php echo __("The color that is being used for selected days on the front-end.",'wpbs') ;?></small>
                            </div>
                            <div class="wpbs-clear"></div>                            
                        </div>  
                        
                        <div class="wpbs-settings-col">
                            <div class="wpbs-settings-col-left">
                                <strong><?php echo __("Selected date border color",'wpbs') ;?></strong>                                
                            </div>
                            <div class="wpbs-settings-col-right wpbs-colorpicker">
                                <span class="color-box" id="selectedBorderBox"  style="background-color:<?php echo $wpbsOptions['selectedBorder'];?>;"><!-- --></span>
                                <input class="small" type="text" id="selectedBorder" name="selectedBorder" value="<?php echo $wpbsOptions['selectedBorder'];?>" />
                                <small><?php echo __("The border color that is being used for selected days on the front-end.",'wpbs') ;?></small>
                            </div>
                            <div class="wpbs-clear"></div>                            
                        </div>  
                        <div class="wpbs-settings-col">
                            <div class="wpbs-settings-col-left">
                                <strong><?php echo __("Booking History Color",'wpbs') ;?></strong>                                
                            </div>
                            <div class="wpbs-settings-col-right wpbs-colorpicker">
                                <span class="color-box" id="historyColorBox"  style="background-color:<?php echo $wpbsOptions['historyColor'];?>;"><!-- --></span>
                                <input class="small" type="text" id="historyColor" name="historyColor" value="<?php echo $wpbsOptions['historyColor'];?>" />
                                <small><?php echo __("The color that will be used if you select 'Use Booking History Color' when you generate a shortcode.",'wpbs') ;?></small>
                            </div>
                            <div class="wpbs-clear"></div>                            
                        </div>  
                        
                        <div class="wpbs-settings-col">
                            <div class="wpbs-settings-col-left">
                                <strong><?php echo __('Backend Start Day','wpbs');?></strong>                                
                            </div>
                            <?php if(empty($wpbsOptions['backendStartDay'])) $wpbsOptions['backendStartDay'] = 1;?>
                            <div class="wpbs-settings-col-right">
                                <select name="backend-start-day">
                                    <option <?php if($wpbsOptions['backendStartDay'] == 1):?>selected="selected"<?php endif;?> value="1"><?php _e('Monday');?></option>
                                    <option <?php if($wpbsOptions['backendStartDay'] == 2):?>selected="selected"<?php endif;?> value="2"><?php _e('Tuesday');?></option>
                                    <option <?php if($wpbsOptions['backendStartDay'] == 3):?>selected="selected"<?php endif;?> value="3"><?php _e('Wednesday');?></option>
                                    <option <?php if($wpbsOptions['backendStartDay'] == 4):?>selected="selected"<?php endif;?> value="4"><?php _e('Thursday');?></option>
                                    <option <?php if($wpbsOptions['backendStartDay'] == 5):?>selected="selected"<?php endif;?> value="5"><?php _e('Friday');?></option>
                                    <option <?php if($wpbsOptions['backendStartDay'] == 6):?>selected="selected"<?php endif;?> value="6"><?php _e('Saturday');?></option>
                                    <option <?php if($wpbsOptions['backendStartDay'] == 7):?>selected="selected"<?php endif;?> value="7"><?php _e('Sunday');?></option>
                                </select>
                            </div> 
                            <div class="wpbs-clear"></div>                            
                        </div> 
                        
                        <div class="wpbs-settings-col">
                            <div class="wpbs-settings-col-left">
                                <strong><?php echo __("iCalendar Sync",'wpbs') ;?></strong><br />
                            </div>
                            <div class="wpbs-settings-col-right">

                                    <label><input type="checkbox" name="enable_ical" <?php if(!empty($wpbsOptions['enableiCal']) && $wpbsOptions['enableiCal'] == 'yes'):?>checked="checked"<?php endif;?> value="yes" /> <?php _e("Enable",'wpbs');?></label>
                                
                            </div>
                            <div class="wpbs-clear"></div>
                            
                        </div> 
                        
                    </div>
                </div>
            </div>
            
            <div class="metabox-holder">
                <div class="postbox">
                    <div class="handlediv" title="<?php echo __("Click to toggle",'wpbs') ;?>"><br /></div>
                    <h3 class="hndle"><?php echo __("Captcha",'wpbs') ;?></h3>
                    <div class="inside">
                        
                        <div class="wpbs-settings-col">
                            <div class="wpbs-settings-col-left">
                                <strong><?php echo __("reCaptcha",'wpbs') ;?></strong><br />
                            </div>
                            <div class="wpbs-settings-col-right">

                                    <label><input type="checkbox" name="enable_recaptcha" <?php if(!empty($wpbsOptions['enableReCaptcha']) && $wpbsOptions['enableReCaptcha'] == 'yes'):?>checked="checked"<?php endif;?> value="yes" /> <?php _e("Enable",'wpbs');?></label>
                                
                            </div>
                            <div class="wpbs-clear"></div>
                            
                        </div> 
                        
                        <div class="wpbs-settings-col">
                            <div class="wpbs-settings-col-left">
                                <strong><?php echo __("reCaptcha Site Key",'wpbs') ;?></strong><br />
                            </div>
                            <div class="wpbs-settings-col-right">

                                    <input type="text" name="recaptcha_public" value="<?php if(!empty($wpbsOptions['recaptcha_public'])) echo esc_html($wpbsOptions['recaptcha_public']);?>" />
                                
                            </div>
                            <div class="wpbs-clear"></div>
                            
                        </div> 
                        
                        <div class="wpbs-settings-col">
                            <div class="wpbs-settings-col-left">
                                <strong><?php echo __("reCaptcha Secret Key",'wpbs') ;?></strong><br />
                            </div>
                            <div class="wpbs-settings-col-right">

                                    <input type="text" name="recaptcha_secret" value="<?php if(!empty($wpbsOptions['recaptcha_secret'])) echo esc_html($wpbsOptions['recaptcha_secret']);?>" />
                                
                            </div>
                            <div class="wpbs-clear"></div>
                            
                        </div> 
                        
                        <div class="wpbs-settings-col">
                        <p>Get your API keys from <a href="https://www.google.com/recaptcha/admin" target="_blank">https://www.google.com/recaptcha/admin</a>. You will need a google account for this.</p>
                        </div>
                    </div>
                </div>
            </div>  
             
            
            <div class="metabox-holder">
                <div class="postbox">
                    <div class="handlediv" title="<?php echo __("Click to toggle",'wpbs') ;?>"><br /></div>
                    <h3 class="hndle"><?php echo __("Languages",'wpbs') ;?></h3>
                    <div class="inside">
                        <?php $activeLanguages = json_decode(get_option('wpbs-languages'),true);?>
                        <?php $languages = array('en' => 'English','ca' => 'Catalan','zh' => 'Chinese','hr' => 'Croatian','cz' => 'Czech','da' => 'Danish','nl' => 'Dutch','et' => 'Estonian','fi' => 'Finnish','fr' => 'French','de' => 'German','el' => 'Greek','hu' => 'Hungarian','it' => 'Italian', 'no' => 'Norwegian' ,'pl' => 'Polish','pt' => 'Portugese','ro' => 'Romanian','ru' => 'Russian','sk' => 'Slovak','sl' => 'Slovenian','es' => 'Spanish','sv' => 'Swedish','tr' => 'Turkish','uk' => 'Ukrainian');?>    
                        <div class="wpbs-settings-col">
                            <div class="wpbs-settings-col-left">
                                <strong><?php echo __("Languages",'wpbs') ;?></strong><br />
                                <small><?php echo __("What languages do you",'wpbs') ;?> <br /><?php echo __("want to use?",'wpbs') ;?></small>
                            </div>
                            <div class="wpbs-settings-col-right">
                                <?php foreach($languages as $code => $language):?>
                                    <label><input type="checkbox" name="<?php echo $code;?>" <?php if(in_array($language,$activeLanguages)):?>checked="checked"<?php endif;?> value="<?php echo $code;?>" /> <?php echo $language;?></label>
                                <?php endforeach;?>
                            </div>
                            <div class="wpbs-clear"></div>
                            
                        </div> 
                        
                    </div>
                </div>
            </div>  
            
            <div class="metabox-holder">
                <div class="postbox">
                    <div class="handlediv" title="<?php echo __("General Translations",'wpbs') ;?>"><br /></div>
                    <h3 class="hndle"><?php echo __("General Translations",'wpbs') ;?></h3>
                    <div class="inside">
                        <?php $activeLanguages = json_decode(get_option('wpbs-languages'),true);?>
                         
                        <h4><?php echo __("Booking ID",'wpbs') ;?>:</h4>  
                        <?php foreach ($activeLanguages as $code => $language):?>
                        <div class="wpbs-settings-col">
                            <div class="wpbs-settings-col-left">
                                <strong><img src="<?php echo WPBS_PATH;?>/images/flags/<?php echo $code;?>.png" /> <?php echo $language;?></strong>                                
                            </div>
                            <div class="wpbs-settings-col-right">
                                <input type="text" name="translation_bookingid_<?php echo $code;?>" value="<?php if(!empty($wpbsOptions['translationBookingId'][$code])) echo esc_html($wpbsOptions['translationBookingId'][$code]);?>" />
                            </div>
                            <div class="wpbs-clear"></div>                            
                        </div>
                        <?php endforeach;?>
                        
                        <h4><?php echo __("Your Booking Details",'wpbs') ;?>:</h4>  
                        <?php foreach ($activeLanguages as $code => $language):?>
                        <div class="wpbs-settings-col">
                            <div class="wpbs-settings-col-left">
                                <strong><img src="<?php echo WPBS_PATH;?>/images/flags/<?php echo $code;?>.png" /> <?php echo $language;?></strong>                                
                            </div>
                            <div class="wpbs-settings-col-right">
                                <input type="text" name="translation_yourbookingdetails_<?php echo $code;?>" value="<?php if(!empty($wpbsOptions['translationYourBookingDetails'][$code])) echo esc_html($wpbsOptions['translationYourBookingDetails'][$code]);?>" />
                            </div>
                            <div class="wpbs-clear"></div>                            
                        </div>
                        <?php endforeach;?>
                        
                        <h4><?php echo __("Check-In",'wpbs') ;?>:</h4>  
                        <?php foreach ($activeLanguages as $code => $language):?>
                        <div class="wpbs-settings-col">
                            <div class="wpbs-settings-col-left">
                                <strong><img src="<?php echo WPBS_PATH;?>/images/flags/<?php echo $code;?>.png" /> <?php echo $language;?></strong>                                
                            </div>
                            <div class="wpbs-settings-col-right">
                                <input type="text" name="translation_checkin_<?php echo $code;?>" value="<?php if(!empty($wpbsOptions['translationCheckIn'][$code])) echo esc_html($wpbsOptions['translationCheckIn'][$code]);?>" />
                            </div>
                            <div class="wpbs-clear"></div>                            
                        </div>
                        <?php endforeach;?>
                        
                        <h4><?php echo __("Check-Out",'wpbs') ;?>:</h4>  
                        <?php foreach ($activeLanguages as $code => $language):?>
                        <div class="wpbs-settings-col">
                            <div class="wpbs-settings-col-left">
                                <strong><img src="<?php echo WPBS_PATH;?>/images/flags/<?php echo $code;?>.png" /> <?php echo $language;?></strong>                                
                            </div>
                            <div class="wpbs-settings-col-right">
                                <input type="text" name="translation_checkout_<?php echo $code;?>" value="<?php if(!empty($wpbsOptions['translationCheckOut'][$code])) echo esc_html($wpbsOptions['translationCheckOut'][$code]);?>" />
                            </div>
                            <div class="wpbs-clear"></div>                            
                        </div>
                        <?php endforeach;?>
                        
                        <h4><?php echo __("Booking status updated",'wpbs') ;?>:</h4>  
                        <?php foreach ($activeLanguages as $code => $language):?>
                        <div class="wpbs-settings-col">
                            <div class="wpbs-settings-col-left">
                                <strong><img src="<?php echo WPBS_PATH;?>/images/flags/<?php echo $code;?>.png" /> <?php echo $language;?></strong>                                
                            </div>
                            <div class="wpbs-settings-col-right">
                                <input type="text" name="translation_booking_status_updated_<?php echo $code;?>" value="<?php if(!empty($wpbsOptions['translationBookingStatusUpdated'][$code])) echo esc_html($wpbsOptions['translationBookingStatusUpdated'][$code]);?>" />
                            </div>
                            <div class="wpbs-clear"></div>                            
                        </div>
                        <?php endforeach;?>
                        
                        <h4><?php echo __("Select a minimum of %x days",'wpbs') ;?>:</h4>  
                        <?php foreach ($activeLanguages as $code => $language):?>
                        <div class="wpbs-settings-col">
                            <div class="wpbs-settings-col-left">
                                <strong><img src="<?php echo WPBS_PATH;?>/images/flags/<?php echo $code;?>.png" /> <?php echo $language;?></strong>                                
                            </div>
                            <div class="wpbs-settings-col-right">
                                <input type="text" name="translation_min_days_<?php echo $code;?>" value="<?php if(!empty($wpbsOptions['translationMinDays'][$code])) echo esc_html($wpbsOptions['translationMinDays'][$code]);?>" />
                            </div>
                            <div class="wpbs-clear"></div>    
                                                    
                        </div>
                        <?php endforeach;?>
                        <small>(note that <strong>%x</strong> will be replaced with the number of days)</small>
                    </div>
                </div>
            </div> 
            <br /><input type="submit" class="button button-primary" value="<?php echo __("Save Changes",'wpbs') ;?>" /> 
            </form>
        </div>
</div>
<script>
var wpbs = jQuery.noConflict();
wpbs(document).ready(function(){
    wpbs('#selectedColor').ColorPicker({
		color: '<?php echo $wpbsOptions['selectedColor'];?>',
		onShow: function (colpkr) {
			wpbs(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			wpbs(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			wpbs('#selectedColorBox').css('backgroundColor', '#' + hex);
            wpbs('#selectedColor').val('#' + hex);
		}
	});
    wpbs('#selectedBorder').ColorPicker({
		color: '<?php echo $wpbsOptions['selectedBorder'];?>',
		onShow: function (colpkr) {
			wpbs(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			wpbs(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			wpbs('#selectedBorderBox').css('backgroundColor', '#' + hex);
            wpbs('#selectedBorder').val('#' + hex);
		}
	});
    wpbs('#historyColor').ColorPicker({
		color: '<?php echo $wpbsOptions['historyColor'];?>',
		onShow: function (colpkr) {
			wpbs(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			wpbs(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			wpbs('#historyColorBox').css('backgroundColor', '#' + hex);
            wpbs('#historyColor').val('#' + hex);
		}
	});
 });
</script>

