var wpbs = jQuery.noConflict();
function showLoader($this){
    $this.find('.wpbs-loading').fadeTo(0,0).css('display','block').fadeTo(200,1);
    $this.find('.wpbs-calendar ul').animate({
        'opacity' : '0.7'
    },200);
}
function hideLoader(){
    wpbs('.wpbs-loading').css('display','none');
}
function changeDay(direction, timestamp, $this){
    var data = {
		action: 'changeDay',
        calendarDirection: direction,
		totalCalendars: $this.find(".wpbs-total-calendars").html(), 
        currentTimestamp: timestamp,
        calendarData: $this.find(".wpbs-calendar-data").attr('data-info'),
        calendarHistory: $this.find(".wpbs-calendar-history").html(),
        calendarLegend: $this.find(".wpbs-calendar-legend").attr('data-info'),
        showDropdown: $this.find(".wpbs-show-dropdown").html(),
        calendarLanguage: $this.find(".wpbs-calendar-language").html(),
        weekStart : $this.find(".wpbs-calendar-week-start").html(),
        showTooltip: $this.find(".wpbs-calendar-tooltip").html(),
        calendarSelection : $this.find(".wpbs-calendar-selection").html(),
        calendarSelectionType : $this.find(".wpbs-calendar-selection-type").html(),
        autoPending : $this.find(".wpbs-calendar-auto-pending").html(),
        weekNumbers : $this.find(".wpbs-calendar-week-numbers").html(),
        minDays : $this.find(".wpbs-calendar-minumum-days").html(),
        calendarID : $this.find(".wpbs-calendar-ID").html(),
        formID : $this.find(".wpbs-calendar-form-ID").html(),
        formPosition : $this.find(".wpbs-calendar-form-position").html()
	};
	wpbs.post(ajaxurl, data, function(response) {
		$this.find('.wpbs-calendars').html(response);
        hideLoader();     
        $this.find('.wpbs-dropdown').customSelect();  
	});
}

function wpbs_clear_selection(){
    startDate = endDate = false; 
    wpbs('.wpbs-not-bookable').removeClass('wpbs-not-bookable').addClass('wpbs-bookable');
    wpbs('.wpbs-bookable-clicked').removeClass('wpbs-bookable-clicked');
    wpbs('.wpbs-bookable-hover').removeClass('wpbs-bookable-hover');
    wpbs('.wpbs-start-date, .wpbs-end-date').val('')
    wpbs('.wpbs-calendar-selection').html('');
}

var CaptchaCallback = function(){
    $('.g-recaptcha').each(function(index, el) {
        grecaptcha.render(el, {'sitekey' : recaptcha_public});
    });
};

var startDate = false;
var endDate = false;
wpbs(document).ready(function(){
    wpbs('.wpbs-dropdown').customSelect();
    wpbs('div.wpbs-container').each(function(){
        
        var $instance = wpbs(this);
        
        /* Calendar */
        
        wpbs($instance).on('change','.wpbs-dropdown',function(e){
            showLoader($instance);     
            e.preventDefault();        
            changeDay('jump',wpbs(this).val(), $instance)
        });
        
        wpbs($instance).on('click','.wpbs-prev',function(e){
            showLoader($instance);
            e.preventDefault();
            if($instance.find(".wpbs-current-timestamp a").length == 0)
                timestamp = $instance.find(".wpbs-current-timestamp").html();
            else 
                timestamp = $instance.find(".wpbs-current-timestamp a").html()
            changeDay('prev',timestamp, $instance);
        });
        
        
        wpbs($instance).on('click','.wpbs-next',function(e){  
            showLoader($instance);
            e.preventDefault();     
            if($instance.find(".wpbs-current-timestamp a").length == 0)
                timestamp = $instance.find(".wpbs-current-timestamp").html();
            else 
                timestamp = $instance.find(".wpbs-current-timestamp a").html()   
            changeDay('next',timestamp, $instance);
        });
        
        /* Form */
        
        wpbs($instance).on('click','.wpbs-form-submit',function(e){  
            e.preventDefault(); 
            $instance.find('.wpbs-form-loading').fadeTo(0,0).css('display','block').fadeTo(200,1);
            $instance.find('.wpbs-form-item').animate({
                'opacity' : '0.7'
            },200);    
            var wpbsFormData = $instance.find('.wpbs-form-form').serialize();
            wpbsFormData = "action=submitForm&" + wpbsFormData;      
        	wpbs.post(ajaxurl, wpbsFormData, function(response) {
        	   
        		$instance.find(".wpbs-form-form").html(response);
                if($("#recaptcha-" + $instance.find('.wpbs-form-form').data('id') + '-' + $instance.find('.wpbs-form-form').data('calendar-id')).length > 0)
        	       grecaptcha.render("recaptcha-" + $instance.find('.wpbs-form-form').data('id') + '-' + $instance.find('.wpbs-form-form').data('calendar-id'),{'sitekey' : recaptcha_public});
        	});            
        });
        
        
        /* Booking - Multiple Days */
        if(wpbs($instance).find('.wpbs-calendar-selection-type').html() == 'multiple'){
            
            wpbs($instance).on('click','.wpbs-bookable',function(e){
                
                e.preventDefault(); 
                $this = wpbs(this);
                
                if(startDate == false){
                    wpbs_clear_selection();                
                    wpbs(this).addClass('wpbs-bookable-clicked');
                    $instance.find('.wpbs-start-date').val($this.attr('data-timestamp'));
                    startDate = true;
                    $instance.find('.wpbs-calendar-selection').html($instance.find('.wpbs-start-date').val() + "-0");
                    //$instance.find('.wpbs-bookable').each(function(){
                    //    if(parseInt(wpbs(this).attr('data-order')) < parseInt($this.attr('data-order'))) wpbs(this).addClass('wpbs-not-bookable').removeClass('wpbs-bookable');
                    //})
                } else if(endDate == false){
                    $this.addClass('wpbs-bookable-clicked');
                    $instance.find('.wpbs-end-date').val($this.attr('data-timestamp'));
                    $instance.find('.wpbs-not-bookable').removeClass('wpbs-not-bookable').addClass('wpbs-bookable');
                    endDate = true;
                    startDate = false;
                    $instance.find('.wpbs-calendar-selection').html($instance.find('.wpbs-start-date').val() + "-" + $instance.find('.wpbs-end-date').val());
                } 
            });
            
            wpbs($instance).find(".wpbs-calendars").mouseleave(function(){
                if(wpbs($instance).find('.wpbs-bookable-clicked').length == 1 && endDate == false){
                    wpbs($instance).find('.wpbs-bookable-clicked').trigger('click');
                    $instance.find('.wpbs-bookable-hover').removeClass('wpbs-bookable-hover');
                    
                }
            })
        }
        
        
        
        
        /* Booking - Single Days */
        if(wpbs($instance).find('.wpbs-calendar-selection-type').html() == 'single'){
            wpbs($instance).on('click','.wpbs-bookable',function(e){
                e.preventDefault(); 
                $this = wpbs(this);
                
                    wpbs_clear_selection();                
                    wpbs(this).addClass('wpbs-bookable-clicked');
                    $instance.find('.wpbs-start-date').val($this.attr('data-timestamp'));
                    $instance.find('.wpbs-end-date').val($this.attr('data-timestamp'));
                    $instance.find('.wpbs-not-bookable').removeClass('wpbs-not-bookable').addClass('wpbs-bookable');
    
                    endDate = false;
                    startDate = false;
                    $instance.find('.wpbs-calendar-selection').html($instance.find('.wpbs-start-date').val() + "-" + $instance.find('.wpbs-end-date').val());
               
            });
        }
        
        wpbs($instance).on('hover','.wpbs-bookable',function(e){
            e.preventDefault();
            $this = wpbs(this);
            var temp = 0;
            if(startDate == true && endDate == false){
                $instance.find('.wpbs-bookable-hover').removeClass('wpbs-bookable-hover');
                
                startHover = parseInt($instance.find('.wpbs-bookable-clicked').attr('data-order'));
                endHover = parseInt(wpbs(this).attr('data-order'));
                if(startHover > endHover){
                    temp = startHover; startHover = endHover; endHover = temp;
                }
                
                for(i = startHover; i <= endHover; i++){
                    if($instance.find('.wpbs-bookable-' + parseInt(i)).hasClass('wpbs-bookable')){
                        $instance.find('.wpbs-bookable-' + parseInt(i)).addClass('wpbs-bookable-hover');    
                    } else {
                        $instance.find('.wpbs-bookable').each(function(){
                            if(temp == 0){
                                if(parseInt(wpbs(this).attr('data-order')) > i) wpbs(this).addClass('wpbs-not-bookable').removeClass('wpbs-bookable wpbs-bookable-hover');    
                            } else {
                                if(parseInt(wpbs(this).attr('data-order')) <= i) wpbs(this).addClass('wpbs-not-bookable').removeClass('wpbs-bookable wpbs-bookable-hover');
                            }
                            
                            
                        })
                    }
                            
                }
                
            }                   
        });
        
        
        
        wpbs('.wpbs-booking-clear').click(function(e){
            e.preventDefault();
            wpbs_clear_selection()
        })
    
    })
    
    
    wpbs("div.wpbs-container").on('mouseenter','li.wpbs-day', function(){        
        $li = wpbs(this);
        if(typeof $li.attr('data-tooltip') != 'undefined'){
            $li.addClass('wpbs-tooltip-active');
            $li.append('<div class="wpbs-tooltip"><strong>' + $li.attr('data-tooltip-date') + '</strong>' + $li.attr('data-tooltip') + '</div>');    
        }            
    });    
      
    wpbs("div.wpbs-container").on('mouseleave','li.wpbs-day', function(){
        wpbs(".wpbs-tooltip-active").removeClass('wpbs-tooltip-active');        
        wpbs("li.wpbs-day .wpbs-tooltip").remove();
            
    });
    
})
$ = jQuery.noConflict();