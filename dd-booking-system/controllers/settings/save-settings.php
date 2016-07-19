<?php

/** Save Languages **/
$languages = array('en' => 'English','ca' => 'Catalan','zh' => 'Chinese','hr' => 'Croatian','cz' => 'Czech','da' => 'Danish','nl' => 'Dutch','et' => 'Estonian','fi' => 'Finnish','fr' => 'French','de' => 'German','el' => 'Greek','hu' => 'Hungarian','it' => 'Italian', 'no' => 'Norwegian','pl' => 'Polish','pt' => 'Portugese','ro' => 'Romanian','ru' => 'Russian','sk' => 'Slovak','sl' => 'Slovenian','es' => 'Spanish','sv' => 'Swedish','tr' => 'Turkish','uk' => 'Ukrainian');

foreach($languages as $code => $language):
    if(!empty($_POST[$code]))
        $activeLanguages[$code] = $language;
endforeach;
if(empty($activeLanguages)) $activeLanguages['en'] = 'English';

update_option('wpbs-languages',json_encode($activeLanguages));


$activeLanguages = json_decode(get_option('wpbs-languages'),true); foreach ($activeLanguages as $code => $language){
    $wpbsOptions['translationBookingId'][$code] = esc_html(trim(stripslashes($_POST['translation_bookingid_' . $code])));
    $wpbsOptions['translationYourBookingDetails'][$code] = esc_html(trim(stripslashes($_POST['translation_yourbookingdetails_' . $code])));
    $wpbsOptions['translationCheckIn'][$code] = esc_html(trim(stripslashes($_POST['translation_checkin_' . $code])));
    $wpbsOptions['translationCheckOut'][$code] = esc_html(trim(stripslashes($_POST['translation_checkout_' . $code])));
    $wpbsOptions['translationBookingStatusUpdated'][$code] = esc_html(trim(stripslashes($_POST['translation_booking_status_updated_' . $code])));
    $wpbsOptions['translationMinDays'][$code] = esc_html(trim(stripslashes($_POST['translation_min_days_' . $code])));
}

 

if(!empty($_POST['selectedColor']))
    $wpbsOptions['selectedColor'] = $_POST['selectedColor'];
else $wpbsOptions['selectedColor'] = '#3399cc';

if(!empty($_POST['selectedBorder']))
    $wpbsOptions['selectedBorder'] = $_POST['selectedBorder'];
else $wpbsOptions['selectedBorder'] = '#336699';

if(!empty($_POST['historyColor']))
    $wpbsOptions['historyColor'] = $_POST['historyColor'];
else $wpbsOptions['historyColor'] = '#eaeaea';

$wpbsOptions['dateFormat'] = $_POST['dateFormat'];

$wpbsOptions['enableiCal'] = $_POST['enable_ical'];
$wpbsOptions['enableReCaptcha'] = $_POST['enable_recaptcha'];
$wpbsOptions['recaptcha_public'] = $_POST['recaptcha_public'];
$wpbsOptions['recaptcha_secret'] = $_POST['recaptcha_secret'];

$wpbsOptions['backendStartDay'] = $_POST['backend-start-day'];

update_option('wpbs-options',json_encode($wpbsOptions));


wp_redirect(admin_url('admin.php?page=wp-booking-system-settings&save=ok'));
die();
