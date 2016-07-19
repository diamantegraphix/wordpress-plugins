var wpbs = jQuery.noConflict();
var $instance;
function wpbs_htmlEscape(str) {
    return String(str)
            .replace(/&/g, '--AMP--')
            .replace(/"/g, '--DOUBLEQUOTE--')
            .replace(/'/g, '--QUOTE--')
            .replace(/</g, '--LT--')
            .replace(/>/g, '--GT--');
}
function showLoader(){
    wpbs('.wpbs-loading').fadeTo(0,0).css('display','block').fadeTo(200,1);
    wpbs('.wpbs-calendar ul').animate({
        'opacity' : '0.7'
    },200);
}
function hideLoader(){
    wpbs('.wpbs-loading').css('display','none');
}
function changeDay(direction, timestamp){
    var data = {
		action: 'changeDayAdmin',
        calendarDirection: direction,
		totalCalendars: $instance.find(".wpbs-total-calendars").html(), 
        currentTimestamp: timestamp,
        calendarData: $instance.find(".wpbs-calendar-data").html(), 
        calendarHistory: $instance.find(".wpbs-calendar-history").html(),
        calendarLegend: $instance.find(".wpbs-calendar-legend").html(), 
        showDropdown: $instance.find(".wpbs-show-dropdown").html(),
        autoPending: $instance.find(".wpbs-calendar-auto-pending").html(),
        weekNumbers : $instance.find(".wpbs-calendar-week-numbers").html(),
        calendarSelectionType: $instance.find(".wpbs-calendar-selection-type").html(),
        calendarLanguage: $instance.find(".wpbs-calendar-language").html(),
        weekStart : $instance.find(".wpbs-calendar-week-start").html(),
        calendarID : $instance.find(".wpbs-calendar-ID").html()
        
	};
	wpbs.post(ajaxurl, data, function(response) {
		$instance.find('.wpbs-calendars').html(response);
        hideLoader();  
        wpbs('.wpbs-dropdown').customSelect();   
        wpbs('.wpbs-chosen').chosen();   
	});
}

wpbs(document).ready(function(){
    
    wpbs('.wpbs-chosen').chosen();  
    wpbs('.wpbs-wrap .meta-box-sortables').sortable({
        disabled: true
    });
    
    wpbs('.wpbs-dropdown').customSelect();
    wpbs('.wpbs-container').each(function(){
    $instance = wpbs(this);
    wpbs($instance).on('change','.wpbs-dropdown',function(e){
        showLoader();     
        e.preventDefault();        
        changeDay('jump',wpbs(this).val())
    });
    
    wpbs($instance).on('click','.wpbs-prev',function(e){
        showLoader();
        e.preventDefault();
        if($instance.find(".wpbs-current-timestamp a").length == 0)
            timestamp = $instance.find(".wpbs-current-timestamp").html();
        else 
            timestamp = $instance.find(".wpbs-current-timestamp a").html()   
        changeDay('prev',timestamp);
    });
    
    
    wpbs($instance).on('click','.wpbs-next',function(e){  
        showLoader();
        e.preventDefault();        
        if($instance.find(".wpbs-current-timestamp a").length == 0)
            timestamp = $instance.find(".wpbs-current-timestamp").html();
        else 
            timestamp = $instance.find(".wpbs-current-timestamp a").html()   
        changeDay('next',timestamp);
    });
    
    })

    wpbs(document).on('click',"#calendarBatchUpdate",function(e){
        e.preventDefault();
        var wpbsCalendarData = JSON.parse(wpbs(".wpbs-calendar-data").html());  
        if (!wpbsCalendarData) {
            wpbsCalendarData = {};
        } 
        var currentTimestamp = wpbs(".wpbs-current-timestamp").html();
        var currentDate = new Date(currentTimestamp * 1000);
       
        var startDay = wpbs("#startDay").val();
        var startMonth = wpbs("#startMonth").val();
        var startYear = wpbs("#startYear").val();
        
        var endDay = wpbs("#endDay").val();
        var endMonth = wpbs("#endMonth").val();
        var endYear = wpbs("#endYear").val();
        
        var selectStatus = wpbs("#changeStatus").val();
        
        var bookingDetails = wpbs("#bookingDetails").val();

        var startTime = (Date.parse(startDay + " " + startMonth + " " + startYear))/1000;
        var endTime = (Date.parse(endDay + " " + endMonth + " " + endYear))/1000;
        if(startTime < endTime){

            for(i=startTime; i <= endTime + 60*60*23; i = i + 60*60*24){
                var changeDate = new Date(i * 1000);
                            
                if(changeDate.getMonth() == currentDate.getMonth() && changeDate.getFullYear() == currentDate.getFullYear()){
                    if(!wpbs("select.wpbs-day-"+(changeDate.getDate())).find('option.wpbs-option-' + selectStatus).prop('selected')){
                        wpbs("select.wpbs-day-"+(changeDate.getDate())).find('option').prop("selected",false);
                        wpbs("select.wpbs-day-"+(changeDate.getDate())).find('option.wpbs-option-' + selectStatus).prop("selected",true);
                    }
                    wpbs("select.wpbs-day-"+(changeDate.getDate())).parents('li').find('span.wpbs-select-status').removeClass().addClass('wpbs-select-status status-' + selectStatus);
                    wpbs("select.wpbs-day-"+(changeDate.getDate())).parents('li').find('span.wpbs-day-split-top').removeClass().addClass('wpbs-day-split-top wpbs-day-split-top-' + selectStatus);
                    wpbs("select.wpbs-day-"+(changeDate.getDate())).parents('li').find('span.wpbs-day-split-bottom').removeClass().addClass('wpbs-day-split-bottom wpbs-day-split-bottom-' + selectStatus);
                    
                    wpbs("select.wpbs-day-"+(changeDate.getDate())).parents('li').find(".wpbs-input-description").val(bookingDetails);
                    
                    wpbs(".wpbs-calendars li.wpbs-day-" + changeDate.getDate()).removeClass().addClass('wpbs-day wpbs-day-' + changeDate.getDate() + ' status-' + selectStatus);
                    wpbs(".wpbs-calendars li.wpbs-day-" + changeDate.getDate() + " span.wpbs-day-split-top").removeClass().addClass('wpbs-day-split-top wpbs-day-split-top-' + selectStatus);
                    wpbs(".wpbs-calendars li.wpbs-day-" + changeDate.getDate() + " span.wpbs-day-split-bottom").removeClass().addClass('wpbs-day-split-bottom wpbs-day-split-bottom-' + selectStatus);
                }
               
                var currentYear = 'year' + changeDate.getFullYear();
        		var currentMonth = 'month' + (changeDate.getMonth()+1);
        		var currentDay = 'day' + (changeDate.getDate());
                
                var currentTimestamp = wpbs(".wpbs-current-timestamp").html();
                var currentDate = new Date(currentTimestamp * 1000);
                var currentMonth = changeDate.getMonth()+1;
                var currentYear = changeDate.getFullYear();
                var currentDay = changeDate.getDate();
                
                
                if (!(currentYear in wpbsCalendarData)) {
        			wpbsCalendarData[currentYear] = {};
        		}
        		
        		if (!(currentMonth in wpbsCalendarData[currentYear])) {
        			wpbsCalendarData[currentYear][currentMonth] = {};
        		}
                wpbsCalendarData[currentYear][currentMonth][currentDay] = selectStatus;
                wpbsCalendarData[currentYear][currentMonth]['description-' + currentDay] = bookingDetails;
                
                wpbs("span.error").css('display','none');
            }
        } else {
            wpbs("span.error").css('display','block');
        }
        wpbs(".wpbs-calendar-data").html(JSON.stringify(wpbsCalendarData));
        wpbs("#inputCalendarData").val(JSON.stringify(wpbsCalendarData));        
    })
    

    wpbs(document).on('change',".wpbs-day-select",function(e){
        var wpbsCalendarData = JSON.parse(wpbs(".wpbs-calendar-data").html());  
        if (!wpbsCalendarData) {
            wpbsCalendarData = {};
        } 
        var currentTimestamp = wpbs(".wpbs-current-timestamp").html();
        var currentDate = new Date(currentTimestamp * 1000);
        var currentMonth =  wpbs(this).attr('data-month').replace('wpbs-month-','');
        var currentYear =  wpbs(this).attr('data-year').replace('wpbs-year-','');
        var currentDay = wpbs(this).attr('data-name').replace('wpbs-day-','');
        var selectStatus = wpbs(this).val();
        
        if (!(currentYear in wpbsCalendarData)) {
			wpbsCalendarData[currentYear] = {};
		}
		
		if (!(currentMonth in wpbsCalendarData[currentYear])) {
			wpbsCalendarData[currentYear][currentMonth] = {};
		}
        wpbsCalendarData[currentYear][currentMonth][currentDay] = selectStatus;

        //change colors
        
        wpbs(this).parent().find('span.wpbs-select-status').removeClass().addClass('wpbs-select-status status-' + selectStatus);
        wpbs(this).parent().find('span.wpbs-day-split-top').removeClass().addClass('wpbs-day-split-top wpbs-day-split-top-' + selectStatus);
        wpbs(this).parent().find('span.wpbs-day-split-bottom').removeClass().addClass('wpbs-day-split-bottom wpbs-day-split-bottom-' + selectStatus);
        
        if(wpbs(this).parents(".wpbs-modal-box").length == 0){     
            wpbs(".wpbs-calendar li.wpbs-day-" + currentDay).removeClass().addClass('wpbs-day wpbs-day-' + currentDay + ' status-' + selectStatus);
            wpbs(".wpbs-calendar li.wpbs-day-" + currentDay + " span.wpbs-day-split-top").removeClass().addClass('wpbs-day-split-top wpbs-day-split-top-' + selectStatus);
            wpbs(".wpbs-calendar li.wpbs-day-" + currentDay + " span.wpbs-day-split-bottom").removeClass().addClass('wpbs-day-split-bottom wpbs-day-split-bottom-' + selectStatus);
        }
        
        
        wpbs(".wpbs-calendar-data").html(JSON.stringify(wpbsCalendarData));
        wpbs("#inputCalendarData").val(JSON.stringify(wpbsCalendarData));
       
    })
    
    wpbs(document).on('keyup',".wpbs-input-description",function(e){
        var wpbsCalendarData = JSON.parse(wpbs(".wpbs-calendar-data").html());  
        if (!wpbsCalendarData) {
            wpbsCalendarData = {};
        } 
        var currentTimestamp = wpbs(".wpbs-current-timestamp").html();
        var currentDate = new Date(currentTimestamp * 1000);
        var currentMonth =  wpbs(this).attr('data-month').replace('wpbs-month-','');
        var currentYear =  wpbs(this).attr('data-year').replace('wpbs-year-','');
        var currentDay = wpbs(this).attr('data-name').replace('wpbs-day-','');
        var selectStatus = wpbs(this).val();
        
        if (!(currentYear in wpbsCalendarData)) {
			wpbsCalendarData[currentYear] = {};
		}
		
		if (!(currentMonth in wpbsCalendarData[currentYear])) {
			wpbsCalendarData[currentYear][currentMonth] = {};
		}
        wpbsCalendarData[currentYear][currentMonth]['description-' + currentDay] = wpbs_htmlEscape(selectStatus);
        
        wpbs(".wpbs-calendar-data").text(JSON.stringify(wpbsCalendarData));
        wpbs("#inputCalendarData").val(JSON.stringify(wpbsCalendarData));
       
    })
    
    wpbs(".saveCalendar").click(function(){
        
        if (!wpbs.trim(wpbs(".fullTitle").val()).length) {
            wpbs(".fullTitle").addClass('error').focus();
            return false;
        }
        return true;
        
    })
    
    wpbs(document).on('click','.bulk-edit-legend-apply',function(){
        wpbs(".edit-dates-popup select").each(function(){
            wpbs(this).val(wpbs('.bulk-edit-legend-select').val()).trigger('change');
        })
        wpbs(".edit-dates-popup input").each(function(){
            wpbs(this).val(wpbs('.bulk-edit-legend-text').val()).trigger('keyup');
        })
    })
})

function wpbs_select_text(containerid) {
    if (document.selection) {
        var range = document.body.createTextRange();
        range.moveToElementText(containerid);
        range.select();
    } else if (window.getSelection) {
        var range = document.createRange();
        range.selectNode(containerid);
        window.getSelection().addRange(range);
    }
}