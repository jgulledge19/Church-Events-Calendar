[[!+fi.validation_error_message]]
[[+fi.errorMessage]]
[[+returnMessage]]
[[+fi.locationConflicts:notempty=`<div class="errors message"><h2>[[+conflictMessage]]</h2><ul>[[+fi.locationConflicts]]</ul></div>`]]
<form name="event_form" action="" method="post"  class="standard" >
    [[+adminInfo]]
<!--
    <input name="status" type="hidden" value="submitted" />
    <input name="event_type" type="hidden" value="public" />
-->

    [[+repeatOptions]]

[[!tinymce]]
    <fieldset>
        <legend>[[+event_heading]]</legend>
        <ul class="plan">
            <li class="spaceLeft">
                <p class="small_font [[!+fi.error.event_type:notempty=`formError`]]" style="margin:0;padding:0;">[[+eventType_heading]]</p>
                <ul>
                    <li class="autoWidth spaceRight">
                        <input name="event_type" type="radio" value="public" [[!+fi.event_type:FormItIsChecked=`public`]] id="rd_event_type_public" class="radio" /> 
                        <label for="rd_event_type_public">[[+eventTypePublic_label]]</label></li>
                    <li class="autoWidth">
                        <input name="event_type" type="radio" value="private" [[!+fi.event_type:FormItIsChecked=`private`]] id="rd_event_type_private" class="radio" /> 
                        <label for="rd_event_type_private">[[+eventTypePrivate_label]]</label>
                    </li>
                </ul>
            </li>
            <li class="full">
                <label for="txt_title" class="[[!+fi.error.title:notempty=`formError`]]">[[+title_label]]</label> 
                <input name="title" type="text" value="[[!+fi.title]]" id="txt_title" class="full" />
            </li>
            <li class="clear full">
                <input name="prominent" type="checkbox" value="Yes" id="ck_prominent" [[!+fi.prominent:FormItIsChecked=`Yes`]] class="radio"  />
                <label for="ck_prominent" class="[[!+fi.error.title:notempty=`formError`]]">[[+prominent_label]]</label>
            </li>
            <li class="full">
                <label for="txt_public_desc" class="[[!+fi.error.public_desc:notempty=`formError`]]">[[+publicDesc_label]]</label>
                <textarea name="public_desc" id="txt_public_desc" class="mceEditor" >[[!+fi.public_desc]]</textarea>
            </li>
            <li class="full">
                <label for="txt_notes" class="[[!+fi.error.notes:notempty=`formError`]]">[[+notes_label]]</label> 
                <textarea name="notes" id="txt_notes" >[[!+fi.notes]]</textarea>

            </li>
            <li class="spaceRight">
                <label for="txt_contact" class="[[!+fi.error.contact:notempty=`formError`]]">[[+contact_label]]</label> 
                <input name="contact" type="text" value="[[!+fi.contact]]" id="txt_contact" />
            </li>
            <li class="spaceRight">
                <label for="txt_contact_email" class="[[!+fi.error.contact_email:notempty=`formError`]]">[[+contactEmail_label]]</label> 
                <input name="contact_email" type="text" value="[[!+fi.contact_email]]" id="txt_contact_email" />
            </li>
            <li>
                <label for="txt_contact_phone" class="[[!+fi.error.contact_phone:notempty=`formError`]]">[[+contactPhone_label]]</label> 
                <input name="contact_phone" type="text" value="[[!+fi.contact_phone]]" id="txt_contact_phone" />
            </li>
            
            
            <li class="autoWidth spaceRight">
                <label for="sel_calendar_id" class="[[!+fi.error.calendar_id:notempty=`formError`]]">[[+calendar_label]]</label> 
                <br>
                <select name="calendar_id" id="sel_calendar_id" >
                    [[+fi.calendar_select]]
                </select>
            </li>
            <li class="autoWidth">
                <label for="sel_category_id" class="[[!+fi.error.category_id:notempty=`formError`]]">[[+category_label]]</label> 
                <br>
                <select name="category_id" id="sel_category_id" >
                    [[+fi.category_select]]
                </select>
            </li>
        </ul>
    </fieldset>
    
    
    <fieldset>
        <legend>[[+dateTime_heading]]</legend>
        
        <ul>
            <li class="full">
                <p class="small_font [[!+fi.error.repeat_type:notempty=`formError`]]" style="margin-top:0;padding-top:0;">[[+repeatType_heading]]</p>
                <ul>
                    <li class="autoWidth spaceRight">
                        <input name="repeat_type" type="radio" value="none" [[!+fi.repeat_type:FormItIsChecked=`none`]]  id="rd_repeat_type_none" class="radio changeToggle" /> 
                        <label for="rd_repeat_type_none">[[+repeatTypeNo_label]]</label>
                    </li>
                    <li class="autoWidth spaceRight">
                        <input name="repeat_type" type="radio" value="daily" [[!+fi.repeat_type:FormItIsChecked=`daily`]] id="rd_repeat_type_daily" class="radio changeToggle" /> 
                        <label for="rd_repeat_type_daily">[[+repeatTypeDaily_label]]</label>
                    </li>
                    <li class="autoWidth spaceRight">
                        <input name="repeat_type" type="radio" value="weekly" [[!+fi.repeat_type:FormItIsChecked=`weekly`]] id="rd_repeat_type_weekly" class="radio changeToggle" /> 
                        <label for="rd_repeat_type_weekly">[[+repeatTypeWeekly_label]]</label>
                    </li>

                    <li class="autoWidth spaceRight">
                        <input name="repeat_type" type="radio" value="monthly" [[!+fi.repeat_type:FormItIsChecked=`monthly`]] id="rd_repeat_type_monthly" class="radio changeToggle" /> 
                        <label for="rd_repeat_type_monthly">[[+repeatTypeMonthly_label]]</label>
                    </li>
                    
                </ul>
            </li>
            
            <!-- Daily repeat -->
            <li class="full" >
                <div id="daily_repeat" style="margin-left: 25px;">
                    <label for="sel_day_interval" class="[[!+fi.error.day_interval:notempty=`formError`]]">[[+interval_label]]</label> 
                    <select name="day_interval" id="sel_day_interval" >
                        <option value="1" >[[+repeatTypeDaily_option]]</option>
                        <option value="2" >[[+repeatTypeDailyOther_option]]</option>
                        <option value="3" >[[+repeatTypeDaily3_option]]</option>
                        <option value="4" >[[+repeatTypeDaily4_option]]</option>
                        <option value="5" >[[+repeatTypeDaily5_option]]</option>
                        <option value="6" >[[+repeatTypeDaily6_option]]</option>
                        <option value="7" >[[+repeatTypeDaily7_option]]</option>
                        
                    </select>
                </div>
            </li>
            
            <!-- Weekly repeat -->
            <li class="full">
                <div id="weekly_repeat" style="margin-left: 25px;">
                    <label for="sel_interval" class="[[!+fi.error.week_interval:notempty=`formError`]]">[[+interval_label]]</label> 
                            
                    <select name="week_interval" id="sel_interval" >
                        <option value="1">[[+repeatTypeWeekly_option]]</option>
                        <option value="2">[[+repeatTypeWeeklyOther_option]]</option>
                        <option value="3">[[+repeatTypeWeekly3_option]]</option>
                        <option value="4">[[+repeatTypeWeekly4_option]]</option>
                        <option value="5">[[+repeatTypeWeekly5_option]]</option>
                        <option value="6">[[+repeatTypeWeekly6_option]]</option>
                    </select>

                    <p class="small_font" class="[[!+fi.error.ch_days:notempty=`formError`]]">[[+whichDays_heading]]</p>
                    <table class="table_select">
                        <tr>
                        <td>
                            <input name="days_7" type="checkbox" value="Y" [[!+fi.days_7:FormItIsChecked=`Y`]] id="ck_days_7" class="radio"  />
                            <label for="ck_days_7">[[+sunday]]</label>
                        </td>
                        <td>
                            <input name="days_1" type="checkbox" value="Y" [[!+fi.days_1:FormItIsChecked=`Y`]] id="ck_days_1" class="radio"  />
                            <label for="ck_days_1">[[+monday]]</label>
                        </td>
                        <td>
                            <input name="days_2" type="checkbox" value="Y" [[!+fi.days_2:FormItIsChecked=`Y`]] id="ck_days_2" class="radio"  />
                            <label for="ck_days_2">[[+tuesday]]</label>
                        </td>
                        <td>
                            <input name="days_3" type="checkbox" value="Y" [[!+fi.days_3:FormItIsChecked=`Y`]] id="ck_days_3" class="radio"  />
                            <label for="ck_days_3">[[+wednesday]]</label>
                        </td>
                        <td>
                            <input name="days_4" type="checkbox" value="Y" [[!+fi.days_4:FormItIsChecked=`Y`]] id="ck_days_4" class="radio"  />
                            <label for="ck_days_4">[[+thursday]]</label>
                        </td>
                        <td>
                            <input name="days_5" type="checkbox" value="Y" [[!+fi.days_5:FormItIsChecked=`Y`]] id="ck_days_5" class="radio"  />
                            <label for="ck_days_5">[[+friday]]</label>
                        </td>
                        <td>
                            <input name="days_6" type="checkbox" value="Y" [[!+fi.days_6:FormItIsChecked=`Y`]] id="ck_days_6" class="radio"  />
                            <label for="ck_days_6">[[+saturday]]</label>
                        </td>
                    </tr>

                    </table>
                </div>
            </li>
            
            <!-- Monthly repeat -->
            <li class="full">
                <div id="monthly_repeat" style="margin-left: 25px;">
                    <label for="sel_mon_interval">[[+interval_label]]</label> 
                            
                <select name="month_interval" id="sel_mon_interval" > 
                    <option value="1">[[+repeatTypeMonthly_option]]</option>
                    <option value="2">[[+repeatTypeMonthlyOther_option]]</option>
                    <option value="3">[[+repeatTypeMonthly3_option]]</option>
                    <option value="4">[[+repeatTypeMonthly4_option]]</option>
                    <option value="5">[[+repeatTypeMonthly5_option]]</option>
                    <option value="6">[[+repeatTypeMonthly6_option]]</option>
                </select>
                    <p class="small_font">[[+whichMonthDays_heading]]</p>
                    <table class="table_select tGrid">
                        <tr>
                            <th>&nbsp;</th>
                            <th>[[+sunday]]</th>
                            <th>[[+monday]]</th>
                            <th>[[+tuesday]]</th>
                            <th>[[+wednesday]]</th>
                            <th>[[+thursday]]</th>
                            <th>[[+friday]]</th>
                            <th>[[+saturday]]</th>
                        </tr>
                        <tr>
                            <th>[[+firstWeek_heading]]</th>
                            <td>
                                <input name="month_days_0" type="checkbox" value="Y" [[!+fi.month_days_0:FormItIsChecked=`Y`]]  id="ck_month_days_0" class="radio"  />
                            </td>
                            <td>
                                <input name="month_days_1" type="checkbox" value="Y" [[!+fi.month_days_1:FormItIsChecked=`Y`]] id="ck_month_days_1" class="radio"  />
                            </td>
                            <td>
                                <input name="month_days_2" type="checkbox" value="Y" [[!+fi.month_days_2:FormItIsChecked=`Y`]] id="ck_month_days_2" class="radio"  />
                            </td>
                            <td>
                                <input name="month_days_3" type="checkbox" value="Y" [[!+fi.month_days_3:FormItIsChecked=`Y`]] id="ck_month_days_3" class="radio"  />
                            </td>
                            <td>
                                <input name="month_days_4" type="checkbox" value="Y" [[!+fi.month_days_4:FormItIsChecked=`Y`]] id="ck_month_days_4" class="radio"  />
                            </td>
                            <td>
                                <input name="month_days_5" type="checkbox" value="Y" [[!+fi.month_days_5:FormItIsChecked=`Y`]] id="ck_month_days_5" class="radio"  />
                            </td>
                            <td>
                                <input name="month_days_6" type="checkbox" value="Y" [[!+fi.month_days_6:FormItIsChecked=`Y`]] id="ck_month_days_6" class="radio"  />
                            </td>
                        </tr>
                        <tr>
                            <th>[[+secondWeek_heading]]</th>
                            <td>
                                <input name="month_days_7" type="checkbox" value="Y" [[!+fi.month_days_7:FormItIsChecked=`Y`]] id="ck_month_days_7" class="radio"  />
                            </td>
                            <td>
                                <input name="month_days_8" type="checkbox" value="Y" [[!+fi.month_days_8:FormItIsChecked=`Y`]] id="ck_month_days_8" class="radio"  />
                            </td>
                            <td>
                                <input name="month_days_9" type="checkbox" value="Y" [[!+fi.month_days_9:FormItIsChecked=`Y`]] id="ck_month_days_9" class="radio"  />
                            </td>
                            <td>
                                <input name="month_days_10" type="checkbox" value="Y" [[!+fi.month_days_10:FormItIsChecked=`Y`]] id="ck_month_days_10" class="radio"  />
                            </td>
                            <td>
                                <input name="month_days_11" type="checkbox" value="Y" [[!+fi.month_days_11:FormItIsChecked=`Y`]] id="ck_month_days_11" class="radio"  />
                            </td>
                            <td>
                                <input name="month_days_12" type="checkbox" value="Y" [[!+fi.month_days_12:FormItIsChecked=`Y`]] id="ck_month_days_12" class="radio"  />
                            </td>
                            <td>
                                <input name="month_days_13" type="checkbox" value="Y" [[!+fi.month_days_13:FormItIsChecked=`Y`]] id="ck_month_days_13" class="radio"  />
                            </td>
                        </tr>
                        <tr>
                            <th>[[+thirdWeek_heading]]</th>
                            <td>
                                <input name="month_days_14" type="checkbox" value="Y" [[!+fi.month_days_14:FormItIsChecked=`Y`]] id="ck_month_days_14" class="radio"  />
                            </td>
                            <td>
                                <input name="month_days_15" type="checkbox" value="Y" [[!+fi.month_days_15:FormItIsChecked=`Y`]] id="ck_month_days_15" class="radio"  />
                            </td>
                            <td>
                                <input name="month_days_16" type="checkbox" value="Y" [[!+fi.month_days_16:FormItIsChecked=`Y`]] id="ck_month_days_16" class="radio"  />
                            </td>
                            <td>
                                <input name="month_days_17" type="checkbox" value="Y" [[!+fi.month_days_17:FormItIsChecked=`Y`]] id="ck_month_days_17" class="radio"  />
                            </td>
                            <td>
                                <input name="month_days_18" type="checkbox" value="Y" [[!+fi.month_days_18:FormItIsChecked=`Y`]] id="ck_month_days_18" class="radio"  />
                            </td>
                            <td>
                                <input name="month_days_19" type="checkbox" value="Y" [[!+fi.month_days_19:FormItIsChecked=`Y`]] id="ck_month_days_19" class="radio"  />
                            </td>
                            <td>
                                <input name="month_days_20" type="checkbox" value="Y" [[!+fi.month_days_20:FormItIsChecked=`Y`]] id="ck_month_days_20" class="radio"  />
                            </td>
                        </tr>
                        <tr>
                            <th>[[+forthWeek_heading]]</th>
                            <td>
                                <input name="month_days_21" type="checkbox" value="Y" [[!+fi.month_days_21:FormItIsChecked=`Y`]] id="ck_month_days_21" class="radio"  />
                            </td>
                            <td>
                                <input name="month_days_22" type="checkbox" value="Y" [[!+fi.month_days_22:FormItIsChecked=`Y`]] id="ck_month_days_22" class="radio"  />
                            </td>
                    
                            <td>
                                <input name="month_days_23" type="checkbox" value="Y" [[!+fi.month_days_23:FormItIsChecked=`Y`]] id="ck_month_days_23" class="radio"  />
                            </td>
                            <td>
                                <input name="month_days_24" type="checkbox" value="Y" [[!+fi.month_days_24:FormItIsChecked=`Y`]] id="ck_month_days_24" class="radio"  />
                            </td>
                            <td>
                                <input name="month_days_25" type="checkbox" value="Y" [[!+fi.month_days_25:FormItIsChecked=`Y`]] id="ck_month_days_25" class="radio"  />
                            </td>
                    
                            <td>
                                <input name="month_days_26" type="checkbox" value="Y" [[!+fi.month_days_26:FormItIsChecked=`Y`]] id="ck_month_days_26" class="radio"  />
                            </td>
                            <td>
                                <input name="month_days_27" type="checkbox" value="Y" [[!+fi.month_days_27:FormItIsChecked=`Y`]] id="ck_month_days_27" class="radio"  />
                            </td>
                        </tr>
                        <tr>
                            <th>[[+fifthWeek_heading]]</th>
                            <td>
                                <input name="month_days_28" type="checkbox" value="Y" [[!+fi.month_days_28:FormItIsChecked=`Y`]] id="ck_month_days_28" class="radio"  />
                            </td>
                            <td>
                                <input name="month_days_29" type="checkbox" value="Y" [[!+fi.month_days_29:FormItIsChecked=`Y`]] id="ck_month_days_29" class="radio"  />
                            </td>
                            <td>
                                <input name="month_days_30" type="checkbox" value="Y" [[!+fi.month_days_30:FormItIsChecked=`Y`]] id="ck_month_days_30" class="radio"  />
                            </td>
                            <td>
                                <input name="month_days_31" type="checkbox" value="Y" [[!+fi.month_days_31:FormItIsChecked=`Y`]] id="ck_month_days_31" class="radio"  />
                            </td>
                            <td>
                                <input name="month_days_32" type="checkbox" value="Y" [[!+fi.month_days_32:FormItIsChecked=`Y`]] id="ck_month_days_32" class="radio"  />
                            </td>
                            <td>
                                <input name="month_days_33" type="checkbox" value="Y" [[!+fi.month_days_33:FormItIsChecked=`Y`]] id="ck_month_days_33" class="radio"  />
                            </td>
                            <td>
                                <input name="month_days_34" type="checkbox" value="Y" [[!+fi.month_days_34:FormItIsChecked=`Y`]] id="ck_month_days_34" class="radio"  />
                            </td>
                        </tr>
                    </table>
                </div>
            </li>
            <li>
                <label for="txt_public_start" class="displayBlock [[!+fi.error.public_start:notempty=`formError`]]">[[+publicStart_label]]</label>
                <input name="public_start" type="text" value="[[+fi.public_start]]" title="ex: 10/21/2011" class="date"  id="txt_public_start" />
            </li>
            <li>
                <div class="repeat_info">
                    <label for="txt_public_end"  class="displayBlock">[[+publicEnd_label]]</label>
                    <input name="public_end" type="text" value="[[+fi.public_end]]" title="ex: 10/21/2011" class="date"  id="txt_public_end"  />
                </div>
            </li>
            <li class="full">
                <div class="repeat_info">
                    <label for="txt_exceptions"  class="displayBlock">[[+exceptions_label]]</label>
                    <input type="text" name="exceptions" title="ex: 10/21/2011,10/222011"  class="full" id="txt_exceptions" value="[[+fi.exceptions]]"/>
                </div>
            </li>
            <li class="full clear">

                <p>[[+eventTimed_heading]]</p>
                <ul>
                    <li class="full">
                        <input name="event_timed" [[!+fi.event_timed:FormItIsChecked=`Y`]]  type="radio" value="Y" id="rd_event_timed_Y" class="radio changeToggle" /> 
                        <label for="rd_event_timed_Y">[[+eventTimedYes_label]]</label>
                        <div id="ul_time" style="height: 60px; margin-left: 25px;">
                            <ul>
                                <li class="autoWidth spaceRight">

                                    <label for="hour_1" class="displayBlock">[[+publicTime_label]]</label> 
                                            
                                    <select name="public_time_hr" title="Select the hour" class="hour" id="hour_1" >
                                        [[!+fi.public_hour_select]]
                                    </select> : 
                                    <select name="public_time_min" title="Select the minute" class="hour" id="min_1" >
                                        [[!+fi.public_minute_select]]
                                    </select> 
                                    <input name="public_time_am" type="radio" [[!+fi.public_am:FormItIsChecked=`am`]] value="am" class="am" id="am_1"  /> 
                                    <label for="am_1">am</label> 
                                    
                                    <input name="public_time_am" type="radio" [[!+fi.public_am:FormItIsChecked=`pm`]] value="pm" class="pm" id="pm_1"  /> 
                                    <label for="pm_1">pm</label> 
                                </li>

                                <li class="autoWidth spaceRight spaceLeft">
                                    <label for="hour_2" class="displayBlock">[[+duration_label]]</label>
                                            
                                        <select name="duration_hr" title="Select the hour" class="hour" id="hour_2" > 
                                            [[!+fi.duration_hour_select]]
                                        </select> : 
                                        <select name="duration_min" title="Select the minute" class="hour" id="min_2" > 
                                            [[!+fi.duration_minute_select]]
                                        </select>

                                </li>
                                <li class="autoWidth spaceRight spaceLeft">
                                    <label for="hour_3" class="displayBlock">[[+setupTime_label]]</label>
                                            
                                        <select name="setup_time_hr" title="Select the hour" class="hour" id="hour_3" > 
                                            [[!+fi.setup_hour_select]]
                                        </select> : 
                                        <select name="setup_time_min" title="Select the minute" class="hour" id="min_3" > 
                                            [[!+fi.setup_minute_select]]
                                        </select> 
                                        <input name="setup_time_am" type="radio" [[!+fi.setup_time_am:FormItIsChecked=`am`]] value="am" class="am" id="am_3"  /> 
                                        <label for="am_3">am</label> 
                                        
                                        <input name="setup_time_am" type="radio" [[!+fi.setup_time_am:FormItIsChecked=`pm`]] value="pm" class="pm" id="pm_3"  /> 
                                        <label for="pm_3">pm</label> 
                                </li>
                            </ul>
                        </div>
                    </li>
                    <!-- 
                    Future:
                    <li class="full">
                        <input name="event_timed" type="radio" value="N" id="rd_event_timed_N" class="radio changeToggle" /> 
                        <label for="rd_event_timed_N">No, this is just a note (will not check for conflicts)</label>
                    </li>
                    -->

                    <li class="full">
                        <input name="event_timed" type="radio" value="allday" [[!+fi.event_timed:FormItIsChecked=`allday`]] id="rd_event_timed_allday" class="radio changeToggle"  /> 
                        <label for="rd_event_timed_allday">[[+eventTimedAllday_label]]</label>
                    </li>
                    
                </ul>
            </li>
            
            <!-- 
            Future - Execptions:
            <li class="full">
                Are there days that this event will not take place of the repeated times?
            </li>
            -->
        </ul>
        
    </fieldset>
    <fieldset>
        <legend>[[+location_heading]]</legend>
        
        [[!+fi.locationInfo]]
    </fieldset>

    
    <p>
        <input name="a" type="hidden" value="0"/>
        <input name="view" type="hidden" value="[[+fi.view]]"  />
        <input name="event_id" type="hidden" value="[[+fi.event_id]]"  />
        
        <input name="submit" type="submit" value="[[+submit_button]]" class="submit" /> 
        <input name="cancel" type="submit" value="[[+cancel_button]]" class="submit" />

    </p>
</form>