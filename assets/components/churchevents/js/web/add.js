window.addEvent('domready',function(){
	var minEndDate = new Date();
	
	// Min max date
	/*new DatePicker('minMax',{
		minDate: '03/05/2010',
		maxDate: '04/05/2011'
	});
	// Date object as max/minDate
	new DatePicker('date_object_options', {
		minDate: new Date('03/05/2010'),
		maxDate: new Date('04/05/2011')
	});*/
	
	// Events and vista skin
	var startDate = new DatePicker($('txt_public_start'), {
		pickerClass: 'datepicker_vista',
		startDay: 0,
		onShow: function(){
			//debug('onShow');
		},
		onClose: function(){
			// Set the minDate: to this value:
			minEndDate = new Date($('txt_public_start').get('value'));
			endDate.options.minDate = minEndDate;
		},
		onSelect: function(){
			
		},
		onNext: function(){
			
		},
		onPrevious: function(){
			
		}
		/*
		pickerClass: 'datepicker_jqui',
		positionOffset: {x: 0, y: 5},
		format: '%d.%m.%Y %H:%M:%S'
		*/
	});
	// Events and vista skin
	var endDate = new DatePicker($('txt_public_end'), {
		pickerClass: 'datepicker_vista',
		startDay: 0,
		minDate: minEndDate
	});
	
	// Time Only:
/*
	new DatePicker('txt_public_time',{
		timePicker: true,
		timePickerOnly: true
	});
	new DatePicker('txt_duration',{
		timePicker: true,
		timePickerOnly: true
	});
	new DatePicker('txt_setup_time',{
		timePicker: true,
		timePickerOnly: true
	});
*/
});
	// Toggle the elements:
	var show_times = false;
	var show_end_date = false;
	var show_daily = false;
	var show_weekly = false;
	var show_monthly = false;
	
	var daily_repeat = '';	
	var weekly_repeat = '';
	var monthly_repeat = '';
	var li_end_date = '';
	var ul_time = '';
	
	var myTimer = 0;
	
window.addEvent('load', function() {
	
	// Event repeat:
	daily_repeat = new Fx.Slide('daily_repeat');
	daily_repeat.hide();
	
	weekly_repeat = new Fx.Slide('weekly_repeat');
	weekly_repeat.hide();
	
	monthly_repeat = new Fx.Slide('monthly_repeat');
	monthly_repeat.hide();
	
	// event end date:
	li_end_date = new Fx.Slide('li_end_date');
	li_end_date.hide();
	
	// event times:
	ul_time = new Fx.Slide('ul_time');
	//ul_time.hide(); // hide on load:
	
	loop_toggle();
});


function loop_toggle(){
	var myTimer = delay_toggle.delay(200); //Waits miliseconds then executes myFunction.
}
function delay_toggle(){
	myTimer = $clear(myTimer); //Cancels myFunction.
	// End date:
	if ( $('rd_repeat_type_none').get('checked') && show_end_date ) {
		// hide repeat opitions:
		li_end_date.hide();
		daily_repeat.hide();
		weekly_repeat.hide();
		monthly_repeat.hide();
		show_end_date = false;
		show_daily = false;
		show_weekly = false;
		show_monthly = false;
	} else if ( $('rd_repeat_type_daily').get('checked') && !show_daily ) {
		// show Daily and end date: 
		li_end_date.show();
		daily_repeat.toggle();
		show_end_date = true;
		show_daily = true;
		if ( show_weekly ) {
			weekly_repeat.toggle();
			show_weekly = false;
		}
		if ( show_monthly ) {
			monthly_repeat.toggle();
			show_monthly = false;
		}
	} else if ( $('rd_repeat_type_weekly').get('checked') && !show_weekly ) {
		// show weekly and end date: 
		li_end_date.show();
		weekly_repeat.toggle();
		show_end_date = true;
		show_weekly = true;
		if ( show_daily ) {
			daily_repeat.toggle();
			show_daily = false;
		}
		if ( show_monthly ) {
			monthly_repeat.toggle();
			show_monthly = false;
		}
	} else if ( $('rd_repeat_type_monthly').get('checked') && !show_monthly ) {
		// show monthly and end date: 
		li_end_date.show();
		monthly_repeat.toggle();
		show_end_date = true;
		show_monthly = true;
		if ( show_daily ) {
			daily_repeat.toggle();
			show_daily = false;
		}
		if ( show_weekly ) {
			weekly_repeat.toggle();
			show_weekly = false;
		}
	}
	
	// Time:
	if ( $('rd_event_timed_Y').get('checked') && !show_times ) {
		// show time
		ul_time.show();
		show_times = true;
	} else if ( !$('rd_event_timed_Y').get('checked') && show_times ) {
		// hide time: 
		ul_time.hide();
		show_times = false;
	}
	
	// call back on the function:
	loop_toggle();
} 

