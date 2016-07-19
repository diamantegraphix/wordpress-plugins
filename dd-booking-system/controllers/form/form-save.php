<?php
global $wpdb;

if(!empty($_POST['formID'])){
    $wpdb->update( $wpdb->prefix.'bs_forms', array('formTitle' => $_POST['formTitle'], 'formData' => stripslashes($_POST['formData'])), array('formID' => $_POST['formID']) );     
    $formID = $_POST['formID'];
} else {
    $wpdb->insert( $wpdb->prefix.'bs_forms', array('formTitle' => $_POST['formTitle'], 'formData' => stripslashes($_POST['formData'])));
    $formID = $wpdb->insert_id;     
}

$emails = '';
if(!empty($_POST['receive_emails']) && $_POST['receive_emails'] == 'yes' && !empty($_POST['sendto'])){
    $emails = explode(",",$_POST['sendto']);
    foreach($emails as $email){
        if(is_email($email))
            $emailList[] = sanitize_email($email);
    }
    $emails = implode(",",$emailList);
}

$formOptions['sendTo'] = $emails;

$formOptions['trackingScript'] = $_POST['tracking_script'];

$formOptions['enableAutoReply'] = $_POST['enable_autoreply'];




$formOptions['submitLabel']['default'] = esc_html(trim(stripslashes($_POST['submitLabel'])));
$formOptions['thankYou']['default'] = esc_html(trim(stripslashes($_POST['thankYou'])));
$formOptions['selectDate']['default'] = esc_html(stripslashes(trim($_POST['selectDate'])));

$formOptions['replyFromName'] = esc_html(trim(stripslashes($_POST['reply_from_name'])));
$formOptions['replyFromEmail'] = esc_html(trim(stripslashes($_POST['reply_from_email'])));

if($_POST['enable_autoreply'] == 'yes'){    
    $formOptions['autoReplyEmailBody']['default'] = esc_html(trim(stripslashes($_POST['autoreply_email_body'])));
    $formOptions['autoReplyEmailSubject']['default'] = esc_html(trim(stripslashes($_POST['autoreply_email_subject'])));
    $formOptions['autoReplyIncludeDetails'] = $_POST['autoreply_include_details'];
}
$activeLanguages = json_decode(get_option('wpbs-languages'),true); foreach ($activeLanguages as $code => $language){
    $formOptions['submitLabel'][$code] = esc_html(trim(stripslashes($_POST['submitLabel_' . $code])));
    $formOptions['thankYou'][$code] = esc_html(trim(stripslashes($_POST['thankYou_' . $code])));
    $formOptions['selectDate'][$code] = esc_html(trim(stripslashes($_POST['selectDate_' . $code])));
    if($_POST['enable_autoreply'] == 'yes'){
        $formOptions['autoReplyEmailBody'][$code] = esc_html(trim(stripslashes($_POST['autoreply_email_body_' . $code])));
        $formOptions['autoReplyEmailSubject'][$code] = esc_html(trim(stripslashes($_POST['autoreply_email_subject_' . $code])));
    }
}





if(empty($formOptions['submitLabel']['default'])) $formOptions['submitLabel']['default'] = "Book";
if(empty($formOptions['thankYou']['default'])) $formOptions['thankYou']['default'] = "The form was successfully submitted.";
if(empty($formOptions['selectDate']['default'])) $formOptions['selectDate']['default'] = "Please select a date.";

$wpdb->update( $wpdb->prefix.'bs_forms', array('formOptions' => json_encode($formOptions)), array('formID' => $formID) );   

wp_redirect(admin_url('admin.php?page=wp-booking-system-forms&do=edit-form&id='.$formID.'&save=ok'));
die();
?>