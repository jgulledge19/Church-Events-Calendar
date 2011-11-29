<form name="sort_form" action="" method="get"  class="standard" >
	<fieldset class="clear">
		<legend>Sort the Calendars</legend>
		<ul class="plan">
			<li class="autoWidth spaceRight">
				<label for="sel_church_calendar_id">Choose a Calendar</label> [[+manage_cal]]
				[[+caledar_select]]
			</li>
			<li class="autoWidth spaceRight">
				<label for="sel_church_ecategory_id">Choose a Category</label> [[+manage_cat]]
				[[+category_select]]
			</li>
			<li class="small">
				<input name="submit" type="submit" value=" Sort "  class="submit" />
			</li>
		</ul>
	</fieldset>
	<!-- must have this for search to work -->
	[[+hidden_data]]
</form>