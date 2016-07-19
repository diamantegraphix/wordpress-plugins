<?php
function changeDayAdmin_callback() {
    global $showDateEditor;
    $showDateEditor = true;
    changeDay_callback();
    die();
}
function changeDay_callback() {

    global $showDateEditor;
    
    if(!empty($_POST['showTooltip']) && in_array($_POST['showTooltip'],array(1,2,3))) $showTooltip = $_POST['showTooltip']; else $showTooltip = 1;
    if(in_array($_POST['totalCalendars'],array(1,2,3,4,5,6,7,8,9,10,11,12))) $totalCalendars = $_POST['totalCalendars']; else $totalCalendars = 1;
    if(in_array($_POST['weekStart'],array(1,2,3,4,5,6,7))) $firstDayOfWeek = $_POST['weekStart']; else $firstDayOfWeek = 1;
    if(in_array($_POST['calendarSelectionType'],array('single','multiple'))) $calendarSelectionType = $_POST['calendarSelectionType']; else $calendarSelectionType = 'multiple';
    if(!empty($_POST['formPosition']) && in_array($_POST['formPosition'],array('below','side'))) $formPosition = $_POST['formPosition']; else $formPosition = 'below';
    
    if(!empty($_POST['currentTimestamp'])) $currentTimestamp = $_POST['currentTimestamp'];
    if(!empty($_POST['calendarData'])) $calendarData = $_POST['calendarData'];
    if(!empty($_POST['calendarLegend'])) $calendarLegend = $_POST['calendarLegend'];
    if(!empty($_POST['showDropdown'])) $showDropdown = $_POST['showDropdown'];
    if(!empty($_POST['calendarHistory'])) $calendarHistory = $_POST['calendarHistory'];
    if(!empty($_POST['calendarID'])) $calendarID = $_POST['calendarID'];
    if(!empty($_POST['autoPending'])) $autoPending = $_POST['autoPending'];
    if(!empty($_POST['minDays'])) $minDays = $_POST['minDays']; else $minDays = 0;
    if(!empty($_POST['weekNumbers'])) $weekNumbers = $_POST['weekNumbers'];
    if(!empty($_POST['formID'])) $formID = $_POST['formID']; else $formID = false;
    

    $calendarSelection = ''; if(!empty($_POST['calendarSelection'])) $calendarSelection = $_POST['calendarSelection'];
    
    
    
    if(!empty($_POST['calendarLanguage'])) $calendarLanguage = $_POST['calendarLanguage'];
    if(!empty($_POST['weekStart'])) $firstDayOfWeek = $_POST['weekStart'];
    
    
    $currentTimestamp = intval($currentTimestamp);    
    //hack $currentTimestamp to be the middle of the month.
    $currentTimestamp = strtotime("15 " . date(' F Y',$currentTimestamp));
    
    if(!empty($_POST['calendarDirection']) && $_POST['calendarDirection'] == 'next'){
        $currentTimestamp = strtotime(date('j F Y',$currentTimestamp) . " + 1 month");
    } elseif(!empty($_POST['calendarDirection']) && $_POST['calendarDirection'] == 'prev'){
        $currentTimestamp = strtotime(date('j F Y',$currentTimestamp) . " - 1 month");
    }

    
    echo wpbs_calendar(array('ajaxCall' => true, 'calendarLanguage' => $calendarLanguage, 'calendarHistory' => $calendarHistory, 'showDateEditor' => $showDateEditor, 'calendarID' => $calendarID, 'calendarData' => stripslashes($calendarData), 'currentTimestamp' => $currentTimestamp, 'showTooltip' => $showTooltip,  'showDropdown' => $showDropdown, 'totalCalendars' => $totalCalendars, 'firstDayOfWeek' => $firstDayOfWeek, 'calendarLegend' => stripslashes($calendarLegend), 'calendarSelection' => $calendarSelection, 'calendarSelectionType' => $calendarSelectionType, 'autoPending' => $autoPending, 'minDays' => $minDays, 'weekNumbers' => $weekNumbers, 'formID' => $formID, 'formPosition' => $formPosition)); 
    
	die(); 
}
