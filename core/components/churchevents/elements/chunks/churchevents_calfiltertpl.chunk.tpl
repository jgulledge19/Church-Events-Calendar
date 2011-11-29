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
            <li class="small">
                <input name="submit" type="submit" value=" Sort "  class="submit" />
            </li>
        </ul>

    </fieldset>
<!-- must have this for search to work -- >
    <input name="a" type="hidden" value="[[+a]]"  />
    <input name="id" type="hidden" value="[[*id]]"  />
-->
</form>