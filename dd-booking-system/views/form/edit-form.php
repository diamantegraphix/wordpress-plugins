<?php global $wpdb;?>
<?php $wpbsOptions = json_decode(get_option('wpbs-options'),true);?>
<div class="wrap wpbs-wrap">
    <div id="icon-themes" class="icon32"></div>
    <h2><?php echo __("Edit Form",'wpbs') ;?></h2>
    <?php if(!empty($_GET['save']) && $_GET['save'] == 'ok'):?>
    <div id="message" class="updated">
        <p><?php echo __('The form was updated','wpbs')?></p>
    </div>
    <?php endif;?>
    <?php if(!(!empty($_GET['id']))) $_GET['id'] = 'wpbs-new-form';?>
    <?php $sql = $wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bs_forms WHERE formID=%d',$_GET['id']); ?>
    <?php $form = $wpdb->get_row( $sql, ARRAY_A );?>
    <?php if($wpdb->num_rows > 0 || $_GET['id'] == 'wpbs-new-form'): $formOptions = json_decode($form['formOptions'],true);?>
        <div class="postbox-container meta-box-sortables">
            
            <form action="<?php echo admin_url( 'admin.php?page=wp-booking-system-forms&do=save-form&noheader=true');?>" method="post">
            <div class="wpbs-buttons-wrapper">
                <input type="submit" class="button button-primary button-h2 saveCalendar" value="<?php echo __("Save Changes",'wpbs') ;?>" /> 
                <a class="button secondary-button button-h2 button-h2-back-margin" href="<?php echo admin_url( 'admin.php?page=wp-booking-system-forms' );?>"><?php echo __("Back",'wpbs') ;?></a>
            </div>
            <input type="text" name="formTitle" class="fullTitle" id="formTitle" placeholder="<?php echo __("Form title",'wpbs') ;?>" value="<?php echo esc_html(stripslashes(((!empty($form['formTitle'])) ? $form['formTitle'] : ""))) ;?>"/>
            
                
            
            <div class="metabox-holder">
                    <?php wpbs_edit_form( array('formData' => $form['formData']) );?>
                    <input type="hidden" value="<?php echo (!empty($form['formID'])) ? $form['formID'] : "" ;?>" name="formID" />   
            </div> 
            
            
            <?php $activeLanguages = json_decode(get_option('wpbs-languages'),true);?>
            <div class="metabox-holder">
                <div class="postbox closed">
                    <div class="handlediv" title="<?php echo __("Click to toggle",'wpbs') ;?>"><br /></div>
                    <h3 class="hndle"><?php echo __("Translations",'wpbs') ;?></h3>
                    <div class="inside translations">
                        <h4><?php echo __("Submit Button",'wpbs') ;?></h4>
                        <div class="wpbs-settings-col">
                            <div class="wpbs-settings-col-left">
                                <strong><?php echo __("Default Label",'wpbs') ;?></strong>                                
                            </div>
                            <div class="wpbs-settings-col-right">
                                <input type="text" name="submitLabel" value="<?php echo esc_html($formOptions['submitLabel']['default']);?>" />
                            </div>
                            <div class="wpbs-clear"></div>                            
                        </div>
                        <?php  foreach ($activeLanguages as $code => $language):?>
                        <div class="wpbs-settings-col">
                            <div class="wpbs-settings-col-left">
                                <strong><img src="<?php echo WPBS_PATH;?>/images/flags/<?php echo $code;?>.png" /> <?php echo $language;?></strong>                                
                            </div>
                            <div class="wpbs-settings-col-right">
                                <input type="text" name="submitLabel_<?php echo $code;?>" value="<?php if(!empty($formOptions['submitLabel'][$code])) echo esc_html($formOptions['submitLabel'][$code]);?>" />
                            </div>
                            <div class="wpbs-clear"></div>                            
                        </div>
                        <?php endforeach;?>
                        
                        <h4><?php echo __("Thank you message",'wpbs') ;?></h4>
                        <div class="wpbs-settings-col">
                            <div class="wpbs-settings-col-left">
                                <strong><?php echo __("Default Label",'wpbs') ;?></strong>                                
                            </div>
                            <div class="wpbs-settings-col-right">
                                <input type="text" name="thankYou" value="<?php echo esc_html($formOptions['thankYou']['default']);?>" />
                            </div>
                            <div class="wpbs-clear"></div>                            
                        </div>
                        <?php  foreach ($activeLanguages as $code => $language):?>
                        <div class="wpbs-settings-col">
                            <div class="wpbs-settings-col-left">
                                <strong><img src="<?php echo WPBS_PATH;?>/images/flags/<?php echo $code;?>.png" /> <?php echo $language;?></strong>                                
                            </div>
                            <div class="wpbs-settings-col-right">
                                <input type="text" name="thankYou_<?php echo $code;?>" value="<?php if(!empty($formOptions['thankYou'][$code])) echo esc_html($formOptions['thankYou'][$code]);?>" />
                            </div>
                            <div class="wpbs-clear"></div>                            
                        </div>
                        <?php endforeach;?>
                        
                        <h4><?php echo __("Select Date message",'wpbs') ;?></h4>
                        <div class="wpbs-settings-col">
                            <div class="wpbs-settings-col-left">
                                <strong><?php echo __("Default Label",'wpbs') ;?></strong>                                
                            </div>
                            <div class="wpbs-settings-col-right">
                                <input type="text" name="selectDate" value="<?php echo esc_html($formOptions['selectDate']['default']);?>" />
                            </div>
                            <div class="wpbs-clear"></div>                            
                        </div>
                        <?php foreach ($activeLanguages as $code => $language):?>
                        <div class="wpbs-settings-col">
                            <div class="wpbs-settings-col-left">
                                <strong><img src="<?php echo WPBS_PATH;?>/images/flags/<?php echo $code;?>.png" /> <?php echo $language;?></strong>                                
                            </div>
                            <div class="wpbs-settings-col-right">
                                <input type="text" name="selectDate_<?php echo $code;?>" value="<?php if(!empty($formOptions['selectDate'][$code])) echo esc_html($formOptions['selectDate'][$code]);?>" />
                            </div>
                            <div class="wpbs-clear"></div>                            
                        </div>
                        <?php endforeach;?>
                        
                    </div>
                    
                </div>
            </div> 
            
            <div class="metabox-holder">
                <div class="postbox closed">
                    <div class="handlediv" title="<?php echo __("Click to toggle",'wpbs') ;?>"><br /></div>
                    <h3 class="hndle"><?php echo __("Form Settings",'wpbs') ;?></h3>
                    <div class="inside">
                        <div class="wpbs-settings-col">
                            <div class="wpbs-settings-col-left">
                                <strong><?php echo __("Receive messages by mail",'wpbs') ;?></strong>                                
                            </div>
                            <div class="wpbs-settings-col-right">
                                <select name="receive_emails" id="receive_emails">
                                    <option value="yes"><?php echo __("Yes",'wpbs') ;?></option>
                                    <option value="no"<?php if(empty($formOptions['sendTo'])):?> selected="selected"<?php endif;?>><?php echo __("No",'wpbs') ;?></option>
                                </select>
                            </div>
                            <div class="wpbs-clear"></div>                            
                        </div>
                        
                        <div class="wpbs-settings-col" id="send_to_emails" <?php if(empty($formOptions['sendTo'])):?>style="display:none;"<?php endif;?>>
                            <div class="wpbs-settings-col-left">
                                <strong><?php echo __("Send to:",'wpbs') ;?></strong>                                
                            </div>
                            <div class="wpbs-settings-col-right">
                                <input type="text" name="sendto" value="<?php echo esc_html($formOptions['sendTo']);?>" />
                                <small><?php echo __("Separate multiple e-mail addresses with a comma",'wpbs') ;?></small>
                            </div>
                            <div class="wpbs-clear"></div>                            
                        </div>
                        
                        <div class="wpbs-settings-col">
                            <div class="wpbs-settings-col-left">
                                <strong><?php echo __("Tracking Script",'wpbs') ;?></strong>                                
                            </div>
                            <div class="wpbs-settings-col-right">
                                <textarea name="tracking_script" class="tracking_script widefat" rows="4"><?php echo stripslashes(esc_html($formOptions['trackingScript']));?></textarea>
                                
                            </div>
                            <div class="wpbs-clear"></div>                            
                        </div>
                         
                    </div>
                </div>
            </div> 
        
            
            <div class="metabox-holder">
                <div class="postbox closed">
                    <div class="handlediv" title="<?php echo __("Click to toggle",'wpbs') ;?>"><br /></div>
                    <h3 class="hndle"><?php echo __("Auto Reply",'wpbs') ;?></h3>
                    <div class="inside translations">
                        
                        <div class="wpbs-settings-col">
                            <div class="wpbs-settings-col-left">
                                <strong><label for="enable_autoreply"><?php echo __("Enable Auto-Reply",'wpbs') ;?></label></strong>                                
                            </div>
                            <div class="wpbs-settings-col-right">
                                <select name="enable_autoreply" id="enable_autoreply">
                                    <option value="no"<?php if(!empty($formOptions['enableAutoReply']) && $formOptions['enableAutoReply'] == 'no'):?> selected="selected"<?php endif;?>><?php echo __("No",'wpbs') ;?></option>
                                    <option value="yes"<?php if(!empty($formOptions['enableAutoReply']) && $formOptions['enableAutoReply'] == 'yes'):?> selected="selected"<?php endif;?>><?php echo __("Yes",'wpbs') ;?></option>
                                </select>
                            </div>
                            <div class="wpbs-clear"></div>                            
                        </div>
                        
                        <div id="wpbs-auto-reply" class="<?php if(!empty($formOptions['enableAutoReply']) && $formOptions['enableAutoReply'] == 'yes'):?>show<?php endif;?>">   
                            <?php if(!(!empty($wpbsOptions['enableReCaptcha']) && $wpbsOptions['enableReCaptcha'] == 'yes') ):?>
                            <a href="<?php echo admin_url( 'admin.php?page=wp-booking-system-settings');?>" class="wpbs-warning">
                                <?php echo __("If you are using the Auto-Reply feature, we strongly recommend enabling reCaptcha in the plugins settings page to prevent spammers from abusing the plugin.",'wpbs') ;?>
                            </a>
                            <?php endif;?>
                            <?php if(wpbs_form_get_email_field($form['formData'])):?>
                            
                            <h4><?php echo __("Email Subject",'wpbs') ;?></h4>
                            <div class="wpbs-settings-col">
                                <div class="wpbs-settings-col-left">
                                    <strong><label for="autoreply_email_subject"><?php echo __("Default Subject",'wpbs') ;?></label></strong>                                
                                </div>
                                <div class="wpbs-settings-col-right">
                                    <input type="text" name="autoreply_email_subject" value="<?php if(!empty($formOptions['autoReplyEmailSubject']['default'])) echo esc_html($formOptions['autoReplyEmailSubject']['default']);?>" />
                                </div>
                                <div class="wpbs-clear"></div>                            
                            </div>
                            <?php foreach ($activeLanguages as $code => $language):?>
                            <div class="wpbs-settings-col">
                                <div class="wpbs-settings-col-left">
                                    <strong><img src="<?php echo WPBS_PATH;?>/images/flags/<?php echo $code;?>.png" /> <?php echo $language;?></strong>                                
                                </div>
                                <div class="wpbs-settings-col-right">
                                    <input type="text" name="autoreply_email_subject_<?php echo $code;?>" value="<?php if(!empty($formOptions['autoReplyEmailSubject'][$code])) echo esc_html($formOptions['autoReplyEmailSubject'][$code]);?>" />
                                </div>
                                <div class="wpbs-clear"></div>                            
                            </div>
                            <?php endforeach;?>
                            
                            <h4><?php echo __("Email Body",'wpbs') ;?></h4>
                            
                            <div class="wpbs-settings-col">
                                <div class="wpbs-settings-col-left">
                                    <strong><label for="autoreply_email_body"><?php echo __("Default Body",'wpbs') ;?></label></strong>                                
                                </div>
                                <div class="wpbs-settings-col-right">
                                    <textarea class="widefat" rows="5" name="autoreply_email_body"><?php if(!empty($formOptions['autoReplyEmailBody']['default'])) echo esc_html($formOptions['autoReplyEmailBody']['default']);?></textarea>
                                </div>
                                <div class="wpbs-clear"></div>                            
                            </div>
                            <?php  foreach ($activeLanguages as $code => $language):?>
                            <div class="wpbs-settings-col">
                                <div class="wpbs-settings-col-left">
                                    <strong><img src="<?php echo WPBS_PATH;?>/images/flags/<?php echo $code;?>.png" /> <?php echo $language;?></strong>                                
                                </div>
                                <div class="wpbs-settings-col-right">
                                    <textarea class="widefat" rows="5" name="autoreply_email_body_<?php echo $code;?>"><?php if(!empty($formOptions['autoReplyEmailBody'][$code])) echo esc_html($formOptions['autoReplyEmailBody'][$code]);?></textarea>
                                </div>
                                <div class="wpbs-clear"></div>                            
                            </div>
                            <?php endforeach;?>
                            <div class="wpbs-settings-col">
                                <div class="wpbs-settings-col-left">
                                    <strong><label for="autoreply_include_details"><?php echo __("Include details of the submitted booking",'wpbs') ;?></label></strong>                                
                                </div>
                                <div class="wpbs-settings-col-right">
                                    <input type="checkbox" value="1" name="autoreply_include_details" <?php if(!empty($formOptions['autoReplyIncludeDetails'])) echo ' checked="checked"';?> />
                                </div>
                                <div class="wpbs-clear"></div>                            
                            </div>
                            <?php else:?>
                                <p><?php echo __("To enable auto reply you must add an email field to the form. If more than one email field exists, the first one will be used.",'wpbs') ;?></p>
                            <?php endif;?>
                        
                        </div>
                    </div>
                    
                </div>
            </div> 
            
            <div class="metabox-holder">
                <div class="postbox closed">
                    <div class="handlediv" title="<?php echo __("Click to toggle",'wpbs') ;?>"><br /></div>
                    <h3 class="hndle"><?php echo __("Email Settings",'wpbs') ;?></h3>
                    <div class="inside translations">
                        <div class="wpbs-settings-col">
                            <div class="wpbs-settings-col-left">
                                <strong><label id="reply_from_name"><?php echo __("Sender Name",'wpbs') ;?></label></strong>                                
                            </div>
                            <div class="wpbs-settings-col-right">
                                <input type="text" id="reply_from_name" name="reply_from_name" value="<?php if(!empty($formOptions['replyFromName'])) echo esc_html($formOptions['replyFromName']);?>" />
                                <br /><small>(eg. <strong>John Doe</strong>)</small>
                            </div>
                            <div class="wpbs-clear"></div>                            
                        </div>
                        
                        <div class="wpbs-settings-col">
                            <div class="wpbs-settings-col-left">
                                <strong><label id="reply_from_email"><?php echo __("Sender Email",'wpbs') ;?></label></strong>                                
                            </div>
                            <div class="wpbs-settings-col-right">
                                <input type="text" id="reply_from_email" name="reply_from_email" value="<?php if(!empty($formOptions['replyFromEmail'])) echo esc_html($formOptions['replyFromEmail']);?>" />
                                <br /><small>(eg. <strong>john@email.com</strong>)</small>
                            </div>
                            <div class="wpbs-clear"></div>                            
                        </div>
                    </div>
                </div>
            </div>
            <input type="submit" class="button button-primary saveCalendar" style="margin-top: 20px;" value="<?php echo __("Save Changes",'wpbs') ;?>" /> 
            </form>
        </div>
    <?php else:?>
        <?php echo __('Invalid calendar ID.','wpbs')?>
    <?php endif;?>     
</div>

