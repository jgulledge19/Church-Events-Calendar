<td class="[[+column_class]]">
	<div class="dayWrapper">
		<span class="date">[[+day]]</span>
		<!-- Only if perms allow other avable: +allow_add, +add_url +month +day +year -->
		[[+add_url:notempty=`<a class="addEvent" href="[[+add_url]]" title="[[+add_message]]">[ + ]</a>`]]
		<!-- Now actual event list(day holder) -->
		[[+calDayHolderTpl]]
	</div>
</td>