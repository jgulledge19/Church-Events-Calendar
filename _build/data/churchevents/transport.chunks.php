<?php
/**
 * MyComponent transport chunks
 * Copyright 2011 Your Name <you@yourdomain.com>
 * @author Your Name <you@yourdomain.com>
 * 1/1/11
 *
 * MyComponent is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * MyComponent is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * MyComponent; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package mycomponent
 */
/**
 * Description: Array of chunk objects for MyComponent package
 * @package mycomponent
 * @subpackage build
 */

$chunks = array();

$chunks[1]= $modx->newObject('modChunk');
$chunks[1]->fromArray(array(
    'id' => 1,
    'name' => 'ChurchEvents_CalColumnHeadTpl',
    'description' => 'Calendar column header',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/churchevents_calcolumnheadtpl.chunk.tpl'),
    'properties' => '',
),'',true,true);
            
$chunks[2]= $modx->newObject('modChunk');
$chunks[2]->fromArray(array(
    'id' => 2,
    'name' => 'ChurchEvents_CalColumnTpl',
    'description' => 'Calendar column',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/churchevents_calcolumntpl.chunk.tpl'),
    'properties' => '',
),'',true,true);
            
$chunks[3]= $modx->newObject('modChunk');
$chunks[3]->fromArray(array(
    'id' => 3,
    'name' => 'ChurchEvents_CalDayHolderTpl',
    'description' => 'Calendar day holder',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/churchevents_caldayholdertpl.chunk.tpl'),
    'properties' => '',
),'',true,true);
            
$chunks[4]= $modx->newObject('modChunk');
$chunks[4]->fromArray(array(
    'id' => 4,
    'name' => 'ChurchEvents_CalEventTpl',
    'description' => 'Calendar Event',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/churchevents_caleventtpl.chunk.tpl'),
    'properties' => '',
),'',true,true);
            
$chunks[5]= $modx->newObject('modChunk');
$chunks[5]->fromArray(array(
    'id' => 5,
    'name' => 'ChurchEvents_CalFilterTpl',
    'description' => 'Calendar Filter',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/churchevents_calfiltertpl.chunk.tpl'),
    'properties' => '',
),'',true,true);
            
$chunks[6]= $modx->newObject('modChunk');
$chunks[6]->fromArray(array(
    'id' => 6,
    'name' => 'ChurchEvents_CalNavTpl',
    'description' => 'Calendar navigation, next and previous months',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/churchevents_calnavtpl.chunk.tpl'),
    'properties' => '',
),'',true,true);
            
$chunks[7]= $modx->newObject('modChunk');
$chunks[7]->fromArray(array(
    'id' => 7,
    'name' => 'ChurchEvents_CalRowTpl',
    'description' => 'Calendar row',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/churchevents_calrowtpl.chunk.tpl'),
    'properties' => '',
),'',true,true);
            
$chunks[8]= $modx->newObject('modChunk');
$chunks[8]->fromArray(array(
    'id' => 8,
    'name' => 'ChurchEvents_CalSearchTpl',
    'description' => 'Calendar search',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/churchevents_calsearchtpl.chunk.tpl'),
    'properties' => '',
),'',true,true);
            
$chunks[9]= $modx->newObject('modChunk');
$chunks[9]->fromArray(array(
    'id' => 9,
    'name' => 'ChurchEvents_CalTableTpl',
    'description' => 'Calendar table',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/churchevents_caltabletpl.chunk.tpl'),
    'properties' => '',
),'',true,true);
            
$chunks[10]= $modx->newObject('modChunk');
$chunks[10]->fromArray(array(
    'id' => 10,
    'name' => 'ChurchEvents_CategoryHeadTpl',
    'description' => 'Category CSS or JS that will go thourgh loop and be placed in <head>',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/churchevents_categoryheadtpl.chunk.tpl'),
    'properties' => '',
),'',true,true);
            
$chunks[11]= $modx->newObject('modChunk');
$chunks[11]->fromArray(array(
    'id' => 11,
    'name' => 'ChurchEvents_DeleteFormHeadTpl',
    'description' => 'Any JS/CSS for the delete form',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/churchevents_deleteformheadtpl.chunk.tpl'),
    'properties' => '',
),'',true,true);
            
$chunks[12]= $modx->newObject('modChunk');
$chunks[12]->fromArray(array(
    'id' => 12,
    'name' => 'ChurchEvents_DeleteFormRepeatTpl',
    'description' => 'Option for repeating events on the delete form, all or single instance',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/churchevents_deleteformrepeattpl.chunk.tpl'),
    'properties' => '',
),'',true,true);
            
$chunks[13]= $modx->newObject('modChunk');
$chunks[13]->fromArray(array(
    'id' => 13,
    'name' => 'ChurchEvents_DeleteFormTpl',
    'description' => 'The delete event form, uses FormIt',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/churchevents_deleteformtpl.chunk.tpl'),
    'properties' => '',
),'',true,true);
            
$chunks[14]= $modx->newObject('modChunk');
$chunks[14]->fromArray(array(
    'id' => 14,
    'name' => 'ChurchEvents_EventDescriptionBasicLocationTpl',
    'description' => 'Basic location information on the event description page.  Only used if the Use Locations System Setting is No.',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/churchevents_eventdescriptionbasiclocationtpl.chunk.tpl'),
    'properties' => '',
),'',true,true);
            
$chunks[15]= $modx->newObject('modChunk');
$chunks[15]->fromArray(array(
    'id' => 15,
    'name' => 'ChurchEvents_EventDescriptionLocationTpl',
    'description' => 'Loops through each location(room) and shows information on the event description page.  Only used if the Use Locations System Setting is Yes.',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/churchevents_eventdescriptionlocationtpl.chunk.tpl'),
    'properties' => '',
),'',true,true);
            
$chunks[16]= $modx->newObject('modChunk');
$chunks[16]->fromArray(array(
    'id' => 16,
    'name' => 'ChurchEvents_EventDescriptionLocationTypeTpl',
    'description' => 'Loops through each location type(building) and shows all locations(rooms) in it on the event description page.  Only used if the Use Locations System Setting is Yes.',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/churchevents_eventdescriptionlocationtypetpl.chunk.tpl'),
    'properties' => '',
),'',true,true);
            
$chunks[17]= $modx->newObject('modChunk');
$chunks[17]->fromArray(array(
    'id' => 17,
    'name' => 'ChurchEvents_EventDescriptionTpl',
    'description' => 'Shows event description information of a single event (event description page).',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/churchevents_eventdescriptiontpl.chunk.tpl'),
    'properties' => '',
),'',true,true);
            
$chunks[18]= $modx->newObject('modChunk');
$chunks[18]->fromArray(array(
    'id' => 18,
    'name' => 'ChurchEvents_EventFormAdminTpl',
    'description' => 'Event form admin section, only shows if user has permission to be admin',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/churchevents_eventformadmintpl.chunk.tpl'),
    'properties' => '',
),'',true,true);
            
$chunks[19]= $modx->newObject('modChunk');
$chunks[19]->fromArray(array(
    'id' => 19,
    'name' => 'ChurchEvents_EventFormBasicLocationTpl',
    'description' => 'Basic location information, only used if the Use Locations System Setting is No.',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/churchevents_eventformbasiclocationtpl.chunk.tpl'),
    'properties' => '',
),'',true,true);
            
$chunks[20]= $modx->newObject('modChunk');
$chunks[20]->fromArray(array(
    'id' => 20,
    'name' => 'ChurchEvents_EventFormConflictTpl',
    'description' => 'Shows error message list of events that are conflicting, only used if the Use Locations System Setting is Yes.',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/churchevents_eventformconflicttpl.chunk.tpl'),
    'properties' => '',
),'',true,true);
            
$chunks[21]= $modx->newObject('modChunk');
$chunks[21]->fromArray(array(
    'id' => 21,
    'name' => 'ChurchEvents_EventFormHeadTpl',
    'description' => 'The <head> JS/CSS for the add/edit/request event form.',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/churchevents_eventformheadtpl.chunk.tpl'),
    'properties' => '',
),'',true,true);
            
$chunks[22]= $modx->newObject('modChunk');
$chunks[22]->fromArray(array(
    'id' => 22,
    'name' => 'ChurchEvents_EventFormLocationTpl',
    'description' => 'Loops through each location(room) and shows information on the event form page.  Only used if the Use Locations System Setting is Yes.',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/churchevents_eventformlocationtpl.chunk.tpl'),
    'properties' => '',
),'',true,true);
            
$chunks[23]= $modx->newObject('modChunk');
$chunks[23]->fromArray(array(
    'id' => 23,
    'name' => 'ChurchEvents_EventFormLocationTypeTpl',
    'description' => 'Loops through each location type(building) and shows all locations(rooms) in it on the event form page.  Only used if the Use Locations System Setting is Yes.',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/churchevents_eventformlocationtypetpl.chunk.tpl'),
    'properties' => '',
),'',true,true);
            
$chunks[24]= $modx->newObject('modChunk');
$chunks[24]->fromArray(array(
    'id' => 24,
    'name' => 'ChurchEvents_EventFormRepeatTpl',
    'description' => 'Option for repeating events on the edit form, all or single instance',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/churchevents_eventformrepeattpl.chunk.tpl'),
    'properties' => '',
),'',true,true);
            
$chunks[25]= $modx->newObject('modChunk');
$chunks[25]->fromArray(array(
    'id' => 25,
    'name' => 'ChurchEvents_EventFormTpl',
    'description' => 'The delete event form, uses FormIt',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/churchevents_eventformtpl.chunk.tpl'),
    'properties' => '',
),'',true,true);
            
$chunks[26]= $modx->newObject('modChunk');
$chunks[26]->fromArray(array(
    'id' => 26,
    'name' => 'ChurchEvents_HeadTpl',
    'description' => 'This is the JS/CSS for the calendar goes in the <head> and can use the results from looping categoryHeadTpl like [[+categoryHeadTpl]]',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/churchevents_headtpl.chunk.tpl'),
    'properties' => '',
),'',true,true);
            
$chunks[27]= $modx->newObject('modChunk');
$chunks[27]->fromArray(array(
    'id' => 27,
    'name' => 'ChurchEvents_ListDayHolderTpl',
    'description' => 'The holder for a list of events.',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/churchevents_listdayholdertpl.chunk.tpl'),
    'properties' => '',
),'',true,true);
            
$chunks[28]= $modx->newObject('modChunk');
$chunks[28]->fromArray(array(
    'id' => 28,
    'name' => 'ChurchEvents_ListEventTpl',
    'description' => 'The event details for the list, ListDayHolderTpl',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/churchevents_listeventtpl.chunk.tpl'),
    'properties' => '',
),'',true,true);
            
$chunks[29]= $modx->newObject('modChunk');
$chunks[29]->fromArray(array(
    'id' => 29,
    'name' => 'EmailBasicLocationTpl',
    'description' => '',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/emailbasiclocationtpl.chunk.tpl'),
    'properties' => '',
),'',true,true);
            
$chunks[30]= $modx->newObject('modChunk');
$chunks[30]->fromArray(array(
    'id' => 30,
    'name' => 'EmailLocationTpl',
    'description' => 'Loops through each location(room) and shows information on the event request email.  Only used if the Use Locations System Setting is Yes.',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/emaillocationtpl.chunk.tpl'),
    'properties' => '',
),'',true,true);
            
$chunks[31]= $modx->newObject('modChunk');
$chunks[31]->fromArray(array(
    'id' => 31,
    'name' => 'EmailLocationTypeTpl',
    'description' => 'Loops through each location type(building) and shows all locations(rooms) in it on the event request email.  Only used if the Use Locations System Setting is Yes.',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/emaillocationtypetpl.chunk.tpl'),
    'properties' => '',
),'',true,true);
            
$chunks[32]= $modx->newObject('modChunk');
$chunks[32]->fromArray(array(
    'id' => 32,
    'name' => 'EmailRequestNoticeTpl',
    'description' => 'The is the email that will be send if a user request an event.',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/emailrequestnoticetpl.chunk.tpl'),
    'properties' => '',
),'',true,true);


return $chunks;