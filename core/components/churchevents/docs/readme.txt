--------------------
Snippet: ChurchEvents
--------------------
Version: 1.0 alpha
Rewritten: November 16, 2011
Author: Joshua Gulledge <jgulledge19@hotmail.com>
License: GNU GPLv2 (or later at your option)

ChurchEvents is a calendar extra for MODx Revolution was initially designed specifically for churches but 
would be useful in many other contexts.  Churchevents now supports templates and translations. 

Usage:
    To use the calendar grid view call on the snippet like: [[!churchEventsCalendar? ]] 
    in a chunk or resource/page.  Or just create a new resource and select the ChurchEvents template.
    You must be logged into the manager to manage events, calendars and categories.
    Requires FormIt Package
    Calendars, Categories and Locations are now managed via the backend manager.  
    Add/Edit/Delete/Request Events is still done via the calendar grid.

Features:
    - Multiple calendar views
    - Global categories, you can choose colors to identify each category
    - Repeating events, daily, weekly, monthly and about any combination of those.
    - Allow events to be requested and can edit/delete a single instance of an event
    - Private/Public events
    - Grid & List views
    - Manage locations
    - If you use the locations option events will check for conflict on entry/update.
    - Several more before a 1.0 pl
    
Developers Info:
    The default theme/skin of ChurchEvents uses the mootools JavaScript Library: http://mootools.net and 
    the mootools date picker: http://mootools.net/forge/p/mootools_datepicker.  I will be switching to jQuery
    as the default before 1.0pl release. See old documentation at: 
    http://www.joshua19media.com/modx-development/church-events-docs.html much if it is still relevant, I 
    will be updating docs before a release canidate.  


How to Install:
1. Install via the MODX Revolution package managment
2. Manualy install the CMP:
    See http://rtfm.modx.com/display/revolution20/Custom+Manager+Pages+Tutorial for more help
    a. Create Namespace:  System->Namespace
        Click Create New and then fill exactly for Name: churchevents 
        and for Path: {core_path}components/events/
    b. Create the Action
        System->Actions
        Right-click churchevents from the list of namespaces and select "Create Action Here".
        Controller: controllers/index
        Namespace: yes, use the same namespace: churchevents
        Check Load Headers
        Language Topics: churchevents:default
        Now click save
    c. Create the Menu Object
        Right-Click "Components" and choose "Place Action Here"
        Lexicon Key: churchevnts
        Description: churchevents.desc
        Action: churchevents - controllers/index
        Save (you can ignore the Icon, Parameters, Handler, and Permissions fields for now)
3. Refresh your browser and you should see Church Events under the Components menu.
4. Add in System Settings:
    See System Settings for more info: http://rtfm.modx.com/display/revolution20/System+Settings
      A. Key: churchevents.allowRequests
         Name: Allow Requests
         Field Type: Yes/No
         Namespace: churchevents
         Area Lexicon: ChurchEvents
         Value: Yes
         Description: Allow guests to request events.
      B. Key: churchevents.dateFormat
         Name: Date Format
         Field Type: Textfield
         Namespace: churchevents
         Area Lexicon: ChurchEvents
         Value: %m/%d/%Y
         Description: This is the format that will appear on forms and when a date is presented. Default is %m/%d/%Y see php.net/strftime for all options.
      C. Key: churchevents.extended
         Name: Extended Fields
         Field Type: Textarea
         Namespace: churchevents
         Area Lexicon: ChurchEvents
         Value: 
         Description: A comma separated list of fields you want on the event form.  Example: extend_numberOfPeople,extend_needCatering.
      D. Key: churchevents.pageID
         Name: Page/Resource ID
         Field Type: Textfield
         Namespace: churchevents
         Area Lexicon: ChurchEvents
         Value: 
         Description: This is the Page/Resource ID where the calendar will be located.  This is what all generated URLs are based on. 
      E. Key: churchevents.useLocations
         Name: Use Locations
         Field Type: Yes/No
         Namespace: churchevents
         Area Lexicon: ChurchEvents
         Value: Yes
         Description: Use the location manager.  If yes events will choose from a list of locations and events can check for conflicts.  If no then each event can have a typed in a location and no event is checked for conflict.
5. Then run the install snippet: [[installChurchEvents]]
            
Extending - adding more fields to the event
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