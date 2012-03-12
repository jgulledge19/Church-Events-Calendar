<link rel="stylesheet" href="assets/components/churchevents/css/form.css" type="text/css" />
<!-- jQuery 
    http://docs.jquery.com/Downloading_jQuery
    http://docs.jquery.com/UI/Datepicker
    http://multidatespickr.sourceforge.net/
-- >
<link type="text/css" href="assets/components/churchevents/jquery/css/ui-lightness/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
-->
<link type="text/css" href="assets/components/churchevents/js/jquery/css/smoothness/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
<script type="text/javascript" src="//code.jquery.com/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="assets/components/churchevents/js/jquery/js/jquery-ui-1.8.16.custom.min.js"></script>
<!-- loads mdp -->
<script type="text/javascript" src="assets/components/churchevents/js/jquery/multidate/jquery-ui.multidatespicker.js"></script>
<script type="text/javascript">
    
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
    
    $(function(){
        // Tabs
        $('#tabs').tabs();

        // Dialog           
        $('#dialog').dialog({
            autoOpen: false,
            width: 600,
            buttons: {
                "Ok": function() { 
                    $(this).dialog("close"); 
                }, 
                "Cancel": function() { 
                    $(this).dialog("close"); 
                } 
            }
        });
        
        // Dialog Link
        $('#dialog_link').click(function(){
            $('#dialog').dialog('open');
            return false;
        });

        // Datepicker
        $('#txt_public_start').datepicker({
            inline: true
            ,dateFormat: '[[+datepickerFormat]]' // 'yy-mm-dd' - http://docs.jquery.com/UI/Datepicker/%24.datepicker.formatDate
            ,onSelect: function(dateText, inst) { 
                //setter
                $( "#txt_public_end" ).datepicker( "option", "minDate", new Date(dateText) );
                $( "#txt_exceptions" ).datepicker( "option", "minDate", new Date(dateText)  );
            }
        });
        $('#txt_public_end').datepicker({
            inline: true
            ,dateFormat: '[[+datepickerFormat]]'
            ,onSelect: function(dateText, inst) { 
                //setter
                $( "#txt_exceptions" ).datepicker( "option", "maxDate", new Date(dateText)  );
            }
        });
        $('#txt_exceptions').multiDatesPicker({showButtonPanel: true, dateFormat: '[[+datepickerFormat]]'});
        
        $( "#txt_public_end" ).datepicker( "option", "minDate", new Date($('#txt_public_start').val()) );
        $( "#txt_exceptions" ).datepicker( "option", "minDate", new Date($('#txt_public_start').val()) );
        $( "#txt_exceptions" ).datepicker( "option", "maxDate", new Date($('#txt_public_end').val()) );
                
        //hover states on the static widgets
        $('#dialog_link, ul#icons li').hover(
            function() { $(this).addClass('ui-state-hover'); }, 
            function() { $(this).removeClass('ui-state-hover'); }
        );
        
        
        
        // Event repeat:
        daily_repeat = $('#daily_repeat');
        daily_repeat.hide();
        weekly_repeat = $('#weekly_repeat');
        weekly_repeat.hide();
        monthly_repeat = $('#monthly_repeat');
        monthly_repeat.hide();
        // event end date:
        li_end_date = $('.repeat_info');
        li_end_date.hide();
        // event times:
        ul_time = $('#ul_time');
        ul_time.hide();
        
        $("input.changeToggle").change(function() {
            toggleForm();
        });
        toggleForm();
    });
    
    

function toggleForm(){
    // End date:
    var repeatType = $('input:radio[name=repeat_type]:checked').val();
    if ( repeatType == 'none' && show_end_date ) {
        // hide repeat opitions:
        li_end_date.slideUp();
        daily_repeat.slideUp();
        weekly_repeat.slideUp();
        monthly_repeat.slideUp();
        show_end_date = false;
        show_daily = false;
        show_weekly = false;
        show_monthly = false;
    } else if ( repeatType == 'daily' && !show_daily ) {
        // show Daily and end date: 
        li_end_date.slideDown();
        daily_repeat.slideDown();
        show_end_date = true;
        show_daily = true;
        if ( show_weekly ) {
            weekly_repeat.slideUp();
            show_weekly = false;
        }
        if ( show_monthly ) {
            monthly_repeat.slideUp();
            show_monthly = false;
        }
    } else if ( repeatType == 'weekly' && !show_weekly ) {
        // show weekly and end date: 
        li_end_date.slideDown();
        weekly_repeat.slideDown();
        show_end_date = true;
        show_weekly = true;
        if ( show_daily ) {
            daily_repeat.slideUp();
            show_daily = false;
        }
        if ( show_monthly ) {
            monthly_repeat.slideUp();
            show_monthly = false;
        }
    } else if (  repeatType == 'monthly' && !show_monthly ) {
        // show monthly and end date: 
        li_end_date.slideDown();
        monthly_repeat.slideDown();
        show_end_date = true;
        show_monthly = true;
        if ( show_daily ) {
            daily_repeat.slideUp();
            show_daily = false;
        }
        if ( show_weekly ) {
            weekly_repeat.slideUp();
            show_weekly = false;
        }
    }
    
    var eventTimed = $('input:radio[name=event_timed]:checked').val();
    // Time:
    if ( eventTimed == 'Y' && !show_times ) {
        // show time
        ul_time.slideDown();
        show_times = true;
    } else if ( eventTimed != 'Y' && show_times ) {
        // hide time: 
        ul_time.slideUp();
        show_times = false;
    }
} 
</script>
<style >
.ui-state-highlight {
    background: none repeat scroll 0 0 #FCEFA1;
   /*    background: #fbf9ee url(images/ui-bg_glass_55_fbf9ee_1x400.png) 50% 50% repeat-x; */
}
.ui-state-highlight a.ui-state-default {
    background: #fff;
}
</style>

<!-- This if the orginal Mootools, you can use it if you want but I will no longer be developing it
<link rel="stylesheet" href="assets/components/churchevents/js/web/datepicker_vista/datepicker_vista.css" type="text/css" />
<script type="text/javascript" src="assets/components/churchevents/js/web/mootools-core-1.3.js"></script>
<script type="text/javascript" src="assets/components/churchevents/js/web/mootools-fx-1.3.js"></script>

<script type="text/javascript" src="assets/components/churchevents/js/web/mootools-more-date.js"></script>
<script type="text/javascript" src="assets/components/churchevents/js/web/datepicker.js"></script>
<script type="text/javascript" src="assets/components/churchevents/js/web/add.js"></script>
-->