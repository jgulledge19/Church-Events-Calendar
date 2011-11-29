<div>
    <p>An event has been submitted.</p>
    
    <h2>[[+event_title]]</h2>

    <p><a href="[[+eventUrl]]">[[+descEdit_link]]</a></p>
    
    <ul>
        <li><strong>[[+descDate_heading]]</strong> 
            [[+start_time]] [[+end_time:notempty=`&ndash; [[+end_time]], `]] [[+event_date]] </li>
        <!-- <li><strong>Repeats</strong> </li> 
        <li><strong>[[+descNextDate_heading]]</strong> [[+nextDate]] [[+nextTime]]</li>
            -->
       
        <li><strong>[[+descDescription_heading]]</strong> [[+public_desc]]</li>

        <li><strong>[[+descContact_heading]]</strong>
            <ul>
                <li>[[+contact]]</li>
                <li>[[+contact_email:notempty=`<a href="mailto:[[+contact_email]]">[[+contact_email]]</a>`]]</li>
            </ul>
        </li>
        <!-- <li><strong>Calendar</strong> </li>
        <li><strong>Category</strong> </li> -->

        <li><strong>[[+descLocation_heading]]</strong>
            <!-- this is processing another chunk -->  
            [[+locationInfo]]
        </li>
        <li><strong>[[+descSetupNotes_heading]]</strong> 
            <ul>
                <li><strong>[[+descSetupTime_heading]]</strong> [[+setup_time]]</li>
                <li>[[+notes]]</li>
            </ul>

        </li>
        <!-- 
        <li><strong>Administrative Information</strong>
            <ul >
                <li>Event status</li>
                <li>What type of event is this? event_type</li>
                <li>[[+descOfficeNotes_heading]]</li>
            </ul>
        </li>
        -->
    </ul>
</div>