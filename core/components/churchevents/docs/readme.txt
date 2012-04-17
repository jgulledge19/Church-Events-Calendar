--------------------
Snippet: ChurchEvents
--------------------
Version: 1.1.2 pl
Release Date: April 17, 2012
Rewritten: December 23, 2011
Author: Joshua Gulledge <jgulledge19@hotmail.com>
License: GNU GPLv2 (or later at your option)

ChurchEvents is a calendar extra for MODx Revolution was initially designed specifically for churches but 
would be useful in many other contexts.  Churchevents now supports templates and translations. 

Usage:
    To use the calendar grid view call on the snippet like: [[!churchEventsCalendar? ]] 
    in a chunk or resource/page.  Or just create a new resource and select the ChurchEvents template.
    You must be logged into the manager to manage events, calendars and categories.
    Requires FormIt Package and ColorPicker Package: http://modx.com/extras/package/colorpicker 
    Calendars, Categories and Locations are now managed via the backend manager.  
    Add/Edit/Delete/Request Events is still done via the calendar grid.

Features:
    - Multiple calendar views
    - Global categories, you can choose colors to identify each category
    - Repeating events, daily, weekly, monthly and about any combination of those and you can select exceptions.
    - Allow events to be requested and can edit/delete a single instance of an event
    - Private/Public events
    - Day/week/month/year views and list view with the 
    - Manage locations
    - If you use the locations option events will check for conflict on entry/update.
    - RSS
    - iCal export
    
Documentation: http://rtfm.modx.com/display/ADDON/Church+Events+Calendar

Developers Info:
    1.0 Alpha4 and newer use jQuery older versions used the mootools JavaScript Library: http://mootools.net and 
    the mootools date picker: http://mootools.net/forge/p/mootools_datepicker.  See old documentation at: 
    http://www.joshua19media.com/modx-development/church-events-docs.html much of it is still relevant, I 
    will be updating docs before a release canidate.  

How to Install:
1. Install via the MODX Revolution package managment, FormIt and ColorPicker packages are required before install.
2. Add TinyMCE editor to Public Description on add/edit form.  
    Note you will have to add in a snippet for this feature to work.
    See: http://forums.modx.com/thread/72206/tinymce-addon-and-using-it-with-forms#dis-post-401943
3. Go to System -> System Settings
    A. Where you see core in a drop down select churchevents
    B. Now you will see some options, you will need to put in a value for Page/Resource ID

Extending - adding more fields to the add/edit event form
1. Simply add another field on the form and the name has to start with extend_.  So an example is you want to 
    have audio & video checkbox.  Just add the form element like so: <input type="checkbox" name="extend_audio_video" value="Yes">
    Now you will be able to call on that as a property [[+extend_audio_video]] in the event description.
2. Validation - TBD

Example put this on the _eventformtpl chunk
    <li class="full">
        <input type="checkbox" name="extend_audio" value="Yes" [[!+fi.extend_audio:FormItIsChecked=`Yes`]] id="rd_extend_audio" class="radio" /> 
        <label for="rd_extend_audio">Audio/Video</label>
    </li>
    <li class="full">
        <input type="checkbox" name="extend_food" value="Yes" [[!+fi.extend_food:FormItIsChecked=`Yes`]] id="rd_extend_food" class="radio" /> 
        <label for="rd_extend_food">Food</label>
    </li>