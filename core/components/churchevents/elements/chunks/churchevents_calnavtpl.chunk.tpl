<ul id="cal_nav">
    <li class="previous">
    	&lt; <a href="[[+prev_url]]">[[+previous]]</a>
    </li> 
    <li class="title">
        [[+view:isequalto=`day`:then=`[[+month]] [[+day]], [[+year]]`:else=``]]
        [[+view:isequalto=`week`:then=`[[+startDateFormated]] &ndash; [[+endDateFormated]]`:else=``]]
        [[+view:isequalto=`month`:then=`[[+month]] [[+year]]`:else=``]]
        [[+view:isequalto=`year`:then=`[[+year]]`:else=``]]
    </li>
    <li class="next">
    	<a href="[[+next_url]]">[[+next]]</a> &gt;
	</li>
</ul>