<form name="sort_form" action="" method="get"  class="standard" >
    <fieldset class="clear">
        <legend>Sort the Calendars</legend>
        <ul class="plan">
            <li class="autoWidth spaceRight">
                <label for="sel_church_calendar_id">Choose a Calendar</label> 
                
                <select name="church_calendar_id" class="displayBlock" id="sel_church_calendar_id" > 
                    [[+select_calendar]]
                </select>
            </li>
            <li class="autoWidth spaceRight">
                <label for="sel_church_ecategory_id">Choose a Category</label> 
                
                <select name="church_ecategory_id" class="displayBlock" id="sel_church_ecategory_id" > 
                    [[+select_category]]
                </select>
            </li>
            <!-- Search the tilte option -->
            <li class="small spaceRight">
                <label for="txt_filterSearch" >[[+filterSearch_label]]</label> 
                <input name="filterSearch" type="text" value="[[+filterSearch]]" id="txt_filterSearch" class="full" />
            </li>
            <li class="small spaceRight">
                <input name="submit" type="submit" value="Sort"  class="submit" />
            </li>
            <li class="small spaceLeft">
                <p><a href="[[+icalUrl]]"><img title="iCal Download" src="assets/components/churchevents/images/smical.png" alt="iCal"/></a> 
                [[+rssUrl:notempty=` <a href="[[+rssUrl]]"><img title="RSS Feed" src="assets/components/churchevents/images/smrss.png" alt="RSS"/></a>`]]
                </p>
            </li>
        </ul>
         
        [[+locationInfo:notempty=`
        <p class="clear">
            <input name="filterLocations" type="radio" value="0" [[+filterLocations:isequalto=`0`:then=`checked="checked"`:else=``]] id="rd_filterLocationsN" class="radio changeToggle" /> 
            <label for="rd_filterLocationsN">[[+filterLocationsN_label]]</label>
            <input name="filterLocations" type="radio" value="1" [[+filterLocations:isequalto=`1`:then=`checked="checked"`:else=``]] id="rd_filterLocations" class="radio changeToggle" /> 
            <label for="rd_filterLocations">[[+filterLocationsY_label]]</label>
        </p>
        <div id="filterLocationsHolder">
            [[+locationInfo]]
        </div>`]]
        
        <!-- [[~[[*id]]? clear=`Y`]] -->
        
    </fieldset>
<!-- must have this for search to work for pages with out furl -->
    <input name="id" type="hidden" value="[[*id]]" />

</form>

<ul id="view_tabs">
    <li class=""><a href="[[~[[*id]]? &view=`day` &month=`[[+cMonth]]` &day=`[[+cDay]]` &year=`[[+cYear]]` ]]">Today</a></li>
    <li class="[[+view:isequalto=`day`:then=`selected`:else:``]]"><a href="[[~[[*id]]? &view=`day`]]">Day</a></li>
    <li class="[[+view:isequalto=`week`:then=`selected`:else:``]]"><a href="[[~[[*id]]? &view=`week`]]">Week</a></li>
    <li class="[[+view:isequalto=`month`:then=`selected`:else:``]]"><a href="[[~[[*id]]? &view=`month`]]">Month</a></li>
    <li class="[[+view:isequalto=`year`:then=`selected`:else:``]]"><a href="[[~[[*id]]? &view=`year` ]]">Year</a></li>
</ul>