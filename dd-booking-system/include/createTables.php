<?php
global $wpbs_db_version;
$wpbs_db_version = "1.4";

function wpbs_install(){
    global $wpdb;
    global $wpbs_db_version;
    
    $wpbs_current_db_version = get_option( "wpbs_db_version" );
    if( $wpbs_current_db_version != $wpbs_db_version ):   
        $sql = "CREATE TABLE ".$wpdb->prefix."bs_bookings (
              bookingID int(10) NOT NULL AUTO_INCREMENT,
              calendarID int(10) NOT NULL DEFAULT '0',
              formID int(10) NOT NULL DEFAULT '0',
              startDate int(11) NOT NULL DEFAULT '0',
              endDate int(11) NOT NULL DEFAULT '0',
              createdDate int(11) NOT NULL DEFAULT '0',
              bookingData text NOT NULL,
              bookingStatus tinytext NOT NULL,
              bookingRead varchar(1) NOT NULL DEFAULT '0',
            UNIQUE KEY (bookingID)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='WP Booking System - Bookings';";
        $sql .= "CREATE TABLE ".$wpdb->prefix."bs_calendars (
              calendarID int(10) NOT NULL AUTO_INCREMENT,
              calendarTitle text,
              createdDate int(11) DEFAULT NULL,
              modifiedDate int(11) DEFAULT NULL,
              calendarData text,
              calendarLegend text,
              calendarUsers text,
              calendarHash varchar(32),
            UNIQUE KEY (calendarID)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='WP Booking System - Calendars';";
        $sql .= "CREATE TABLE ".$wpdb->prefix."bs_forms (
              formID int(10) NOT NULL AUTO_INCREMENT,
              formTitle text,
              formData text,
              formOptions text,
            UNIQUE KEY (formID)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='WP Booking System - Forms';";
            
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
        
        add_option( "wpbs_db_version", $wpbs_db_version );
        add_option( "wpbs-languages", '{"en":"English"}', '' );
        add_option( "wpbs-options", '{"selectedColor":"#3399cc","selectedBorder":"#336699","dateFormat":"j F Y","historyColor":"#E4E4E4"}', '' );
        add_option( "wpbs-default-legend", '{"default":{"name":{"default":"Available","hr":"Slobodno","cs":"Volno","da":"Ledigt","nl":"Vrij","en":"Available","fr":"Libre","de":"Frei","hu":"Szabad","it":"Libero","ro":"Disponobil","ru":"\u0414\u043e\u0441\u0442\u0443\u043f\u043d\u043e","sk":"Vo\u013en\u00fd","es":"Libre","sv":"Ledigt","uk":"B\u0456\u043b\u044c\u043d\u043e","no":""},"color":"#DDFFCC","splitColor":false,"bookable":"yes","auto-pending":"no","sync":"no"},"1":{"name":{"default":"Booked","hr":"Zauzeto","cs":"Obsazeno","da":"Booket","nl":"Bezet","en":"Booked","fr":"Occup\u00e9","de":"Belegt","hu":"Foglalt","it":"Prenotato","ro":"Rezervat","ru":"\u0417\u0430\u043d\u044f\u0442\u043e","sk":"Obsaden\u00fd","es":"Reservado","sv":"Bokat","uk":"\u0417\u0430\u0439\u043d\u044f\u0442\u043e","no":""},"color":"#FFC0BD","splitColor":false,"bookable":false,"auto-pending":"yes","sync":"yes"},"2":{"name":{"en":"Changeover 1","nl":"","et":"","fi":"","fr":"","de":"","pt":"","ro":"","default":"Changeover 1"},"color":"#DDFFCC","splitColor":"#FFC0BD","bookable":"yes","hide":"hide","auto-pending":"no","sync":"no"},"3":{"name":{"en":"Changeover 2","nl":"","et":"","fi":"","fr":"","de":"","pt":"","ro":"","default":"Changeover 2"},"color":"#FFC0BD","splitColor":"#DDFFCC","bookable":"yes","hide":"hide","auto-pending":"no","sync":"no"}}' );
        
        $sql = 'SELECT * FROM ' . $wpdb->prefix . 'bs_calendars WHERE calendarHash IS NULL';
        $rows = $wpdb->get_results( $sql, ARRAY_A ); foreach($rows as $row){
            $random_string = wpbs_generateRandomString(32);
            $wpdb->update( $wpdb->prefix.'bs_calendars', array('calendarHash' => $random_string), array('calendarID' => $row['calendarID']) );
        }
        
        
    endif;
}
