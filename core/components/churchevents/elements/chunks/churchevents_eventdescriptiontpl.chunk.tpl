<!-- the event description -->

<!--
   id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `church_calendar_id` int(11) unsigned NOT NULL DEFAULT '0',
  `church_ecategory_id` int(11) unsigned NOT NULL DEFAULT '0',
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0',
  `version` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `web_user_id` int(8) unsigned NOT NULL DEFAULT '0',
  `status` set('approved','deleted','pending','submitted','rejected') NOT NULL DEFAULT 'submitted',
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `event_timed` set('Y','N','allday') NOT NULL DEFAULT 'Y',
  `duration` time NOT NULL,
  `public_start` datetime NOT NULL,
  `public_end` datetime NOT NULL,
  `repeat_type` set('none','daily','weekly','monthly') DEFAULT 'none',
  `interval` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `days` varchar(128) NOT NULL DEFAULT '',
  `event_type` set('public','private') DEFAULT 'public',
  `title` varchar(100) NOT NULL DEFAULT '',
  `public_desc` text NOT NULL,
  `notes` text NOT NULL,
  `office_notes` text NOT NULL,
  `contact` varchar(128) NOT NULL DEFAULT '',
  `contact_email` varchar(128) NOT NULL DEFAULT '',
  `contact_phone` varchar(32) NOT NULL DEFAULT '',
  `personal_subscribers` text NOT NULL,
  `locations` text NOT NULL,
  `location_name` varchar(128) NOT NULL DEFAULT '',
  `address` varchar(128) NOT NULL DEFAULT '',
  `city` varchar(128) NOT NULL DEFAULT '',
  `state` varchar(128) NOT NULL DEFAULT '',
  `zip` varchar(32) NOT NULL DEFAULT '0',
  `country` varchar(128) NOT NULL DEFAULT '',
  `extended`  
     -->
<div id="church_events_wrapper">
    
    <h2>[[+event_title]]</h2>
    [[+edit_url:notempty=`<p><a href="[[+edit_url]]">[[+descEdit_link]]</a> || <a href="[[+delete_url]]">[[+descDelete_link]]</a></p>`]]

    <p><a href="[[~[[*id]]]]">[[+descBack]]</a></p>
    
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