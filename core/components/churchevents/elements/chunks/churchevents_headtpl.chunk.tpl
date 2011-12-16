<link rel="stylesheet" href="[[+assets_url]]/components/churchevents/css/calendar.css" type="text/css" />
<link rel="stylesheet" href="[[+assets_url]]/components/churchevents/css/form.css" type="text/css" />
<style type="text/css" media="all">
    /* this is a loop from CategoryHeadTpl */
	[[+categoryHeadTpl]]
</style>
<script type="text/javascript" src="//code.jquery.com/jquery-1.7.1.min.js"></script>
<script type="text/javascript">
    
    // Toggle the elements:
    var show_locfilter = false;
    
    $(function(){
        locFilter = $('#filterLocationsHolder');
        //locFilter.hide();
        $("input.changeToggle").change(function() {
            toggleForm(true);
        });
        toggleForm(false);
    });
    
function toggleForm(slide){
    // End date:
    var filterLocation = $('input:radio[name=filterLocations]:checked').val();
    if ( filterLocation == 1 ) {
        locFilter.slideDown();
        
    } else {
        // show Daily and end date:
        if ( slide ) {
            locFilter.slideUp();
        } else {
            locFilter.hide();
        }
    } 
} 
</script>