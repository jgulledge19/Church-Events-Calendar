<?php
/**
 * This class extend the fiHooks so that I can  use the Calendar instance and I don't have to recreate it.
 */
class eventHooks extends fiHooks{
    /**
     * A reference to the Calendar instance
     * @var calendar
     */
    public $Calendar;
    /**
     * @var array $default_data
     */
    protected $default_data = array();
    /**
     * load the Calendar object reference
     */
    public function loadCalendar($Calendar){
        $this->Calendar = &$Calendar;
    }
    /**
     * load default values and set requirements - PreHook
     */
    public function loadDefaults(){
        $event_id = $this->Calendar->getFilter('event_id');
        if ( empty($event_id) ) {
            // load default calendar and ecategory
            //return;
        }
        if ( !empty($event_id)) {
            $event = $this->Calendar->modx->getObject('ChurchEvents', array('id' => $event_id));
        }
        $show_repeat_options = false;
        $eventLocations = array();
        $this->default_data = array();
        
        if ( $this->formit->request->hasSubmission() ){
            
            if ( is_object($event) ) {
                $this->default_data['event_id'] = $this->default_data['id'];
                // repeating event stuff:
                $days = array();
                switch($event->get('repeat_type')) {
                    case 'daily':
                    case 'weekly':
                    case 'monthly':
                        $show_repeat_options = true;
                        break;
                }
            }
            // get the submitted values for created elements: selects and location checkboxes
            // status, calendar_id, category_id, 
            $this->default_data['status'] = $_POST['status'];
            $this->default_data['church_calendar_id'] = $_POST['calendar_id'];
            $this->default_data['church_ecategory_id'] = $_POST['category_id'];
            
            $this->default_data['public_time'] = ( $_POST['public_time_am']=='pm' ? $_POST['public_time_hr']*1 + 12 : $_POST['public_time_hr'] ).':'.$_POST['public_time_min'];
            $this->default_data['duration'] = $_POST['duration_hr'].':'.$_POST['duration_min'].':00';
            $this->default_data['setup_time'] = ( $_POST['setup_time_am']=='pm' ? ($_POST['setup_time_hr']*1 + 12) : $_POST['setup_time_hr'] ).':'.$_POST['setup_time_min'];
            if ( isset($_POST['formLoc']) && is_array($_POST['formLoc']) ){
                $eventLocations = $_POST['formLoc'];
            }
        } else if ( is_object($event) ) {
            $this->default_data = $event->toArray();
            $this->default_data['event_id'] = $this->default_data['id'];
            // get the start time:
            list($public_start, $this->default_data['public_time']) = explode(' ', $this->default_data['public_start']);
            
            $this->default_data['setup_time'] = NULL;
            if ( $this->default_data['public_start'] != $this->default_data['start_date'] ) {
                list($tmp, $this->default_data['setup_time']) = explode(' ', $this->default_data['start_date']);
            }
            $this->default_data['public_start'] = $public_start;
            // end date:
            if ( $this->default_data['public_end'] == '0000-00-00 00:00:00' || $this->default_data['public_end'] ==  '-1-11-30 00:00:00') {
                $this->default_data['public_end'] = '';
            } else {
                list($this->default_data['public_end'], $end_time) = explode(' ', $this->default_data['public_end']);
            }
            // echo '<br>public_end: '.$default_data['public_end'];
            // repeating event stuff:
            $days = array();
            switch($this->default_data['repeat_type']) {
                case 'daily':
                    $this->default_data['day_interval'] = $this->default_data['interval'];
                    $show_repeat_options = true;
                    break;
                case 'weekly':
                    $this->default_data['week_interval'] = $this->default_data['interval'];
                    $days = explode(',',$this->default_data['days']);
                    foreach( $days as $day ) {
                        $this->default_data['days_'.$day] = 'Y';
                    }
                    $show_repeat_options = true;
                    break;
                case 'monthly':
                    $this->default_data['month_interval'] = $this->default_data['day_interval'];
                    $days = explode(',',$this->default_data['days']);
                    foreach( $days as $day ) {
                        $this->default_data['month_days_'.$day] = 'Y';
                    }
                    $show_repeat_options = true;
                    break;
            }
            // get the locations -> http://rtfm.modx.com/display/xPDO20/Defining+Relationships
            $locations = $event->getMany('EventLocations');
            foreach($locations as $location ) {
                $eventLocations[] = $location->get('church_location_id');
            }
            // extended fields
            $tmp = $this->Calendar->getExtended($this->default_data['extended']);
            $this->default_data = array_merge((array)$this->default_data, (array)$tmp);
        } else {
            // set the default calendar and category of the current filter
            $this->default_data['church_calendar_id'] = $this->Calendar->getFilter('church_calendar_id');
            $this->default_data['church_ecategory_id'] = $this->Calendar->getFilter('church_ecategory_id');
            $this->default_data['event_id'] = 0;
            
            $this->default_data['public_time'] = NULL;
            if ( isset($_GET['start_date']) ) {
                $this->default_data['public_start'] = $_GET['start_date']; 
            }
        }
        // Admin stuff
        
        $this->Calendar->getFilter('event_id');
        // calendar_select
        if ( empty($this->Calendar->calendar_array) ) {
            $cals = $this->modx->getCollection('ChurchCalendar');
            //echo 'MAKE array';
            foreach ( $cals as $chCalendar ) {
                $this->Calendar->calendar_array[$chCalendar->get('id')] = $chCalendar->get('title');
            }
        }
        //print_r($this->Calendar->calendar_array);
        if ( empty($this->Calendar->category_array) ) {
            $cats = $this->modx->getCollection('ChurchEcategory');
            foreach ( $cats as $chCat ) {
                $this->Calendar->category_array[$chCat->get('id')] = $chCat->get('name');
            }
        }
        $this->default_data['calendar_select'] = $this->Calendar->selectOptions(array_flip($this->Calendar->calendar_array), $this->default_data['church_calendar_id'], NULL);
        $this->default_data['category_select'] = $this->Calendar->selectOptions(array_flip($this->Calendar->category_array), $this->default_data['church_ecategory_id'], NULL);
        $this->default_data['calendar_id'] = $this->default_data['church_calendar_id'];
        $this->default_data['category_id'] = $this->default_data['church_ecategory_id'];
                
        // load time selects
        $time = $this->Calendar->makeTime($this->default_data['public_time']);
        $this->default_data['public_hour_select'] = $time['hour_select'];
        $this->default_data['public_minute_select'] = $time['minute_select'];
        $this->default_data['public_hour'] = $time['hour'];
        $this->default_data['public_minute'] = $time['minute'];
        $this->default_data['public_am'] = $time['am'];
        
        // duration - should this be end time?
        $time = $this->Calendar->makeTime($this->default_data['duration'], 24);
        $this->default_data['duration_hour_select'] = $time['hour_select'];
        $this->default_data['duration_minute_select'] = $time['minute_select'];
        $this->default_data['duration_hour'] = $time['hour'];
        $this->default_data['duration_minute'] = $time['minute'];
        $this->default_data['duration_am'] = $time['am'];
        
        // set up times
        $time = $this->Calendar->makeTime($this->default_data['setup_time']);
        $this->default_data['setup_hour_select'] = $time['hour_select'];
        $this->default_data['setup_minute_select'] = $time['minute_select'];
        $this->default_data['setup_hour'] = $time['hour'];
        $this->default_data['setup_minute'] = $time['minute'];
        $this->default_data['setup_am'] = $time['am'];
        
        // format public_time:  manager_date_format
        if (!empty($this->default_data['public_start']) ) {
            $this->default_data['public_start'] = strftime($this->Calendar->getFilter('dateFormat'), strtotime($this->default_data['public_start']));
        }
        if (!empty($this->default_data['public_end']) ) {
            $this->default_data['public_end'] = strftime($this->Calendar->getFilter('dateFormat'), strtotime($this->default_data['public_end']));
        }
        // $this->default_data['title'] = 'Test';
        
        // location stuff
        if ( $this->Calendar->getFilter('useLocations') ) {
            $c = $this->modx->newQuery('ChurchLocationType') ;//, array('public' => 'Yes'));
            $c->sortby('name', 'ASC');
            $locationTypes = $this->modx->getIterator('ChurchLocationType', $c);
            
            /* iterate */
            $list = array();
            $locationType_string = '';
            foreach ($locationTypes as $locationType) {
                $properties = $locationType->toArray();
                
                $c = $this->modx->newQuery('ChurchLocations', array('church_location_type_id' => $properties['id'], 'published'=>'Yes'));
                $c->sortby('name', 'ASC');
                $locations = $this->modx->getIterator('ChurchLocations', $c);
                
                /* iterate */
                $list = array();
                $location_string = '';
                foreach ($locations as $location) {
                    $locProperties = $location->toArray();
                    $locProperties['input_name'] = 'loc_arr[]';
                    $locProperties['is_checked'] = $this->Calendar->isChecked((in_array($location->get('id'),$eventLocations) ? 1 : 0), 1);
                    
                    $location_string .= $this->Calendar->getChunk($this->Calendar->getFilter('eventFormLocationTpl'), $locProperties);
                }
                if (!empty($location_string) ) {
                    $properties['locations'] = $location_string;
                    $locationType_string .= $this->Calendar->getChunk($this->Calendar->getFilter('eventFormLocationTypeTpl'), $properties);
                }
                
            }
            $this->default_data['locationInfo'] = $locationType_string;
            
        } else {
            $this->default_data['locationInfo'] = $this->Calendar->getChunk($this->Calendar->getFilter('eventFormBasicLocationTpl'));
        }
        
        $options = array(
                'Approved' => 'approved', 
                'Pending' => 'pending', 
                'Submitted' => 'submitted', 
                'Rejected' => 'rejected', 
                'Deleted' => 'deleted'
                );
        $this->default_data['status_select'] = $this->Calendar->selectOptions($options, $this->default_data['status'], NULL);
        
        $this->default_data['view'] = $this->Calendar->getFilter('view');
        $this->Calendar->setFilter('showRepeatOptions', $show_repeat_options);
        // this will load them into the form
        $this->setValues($this->default_data);
        
        // set an extras that need to validate
        if ( $show_repeat_options ) {
            $this->formit->request->config['validate'] .= ',edit_repeat:required';
        }
        if ( $this->formit->request->hasSubmission() ){
            $this->gatherFields();
        }
    
    }
    // now just add the methond I want to use which is
    /**
     * this will save a church event to the data base
     * PostHook
     * @return boolean - true on seccess and false on falure
     */ 
    public function saveChurchEvent() {
        $event_id = $this->Calendar->getFilter('event_id');
        if ( !empty($event_id)){
            $event = $this->modx->getObject('ChurchEvents', array('id'=>$event_id));
        } else {
            //echo 'No ID: '.$event_id;
        }
        $eventLocations = array(); 
        if ( !isset($this->fields['prominent'])){
            $this->fields['prominent'] = 'No';
        }
        $this->fields['church_calendar_id'] = $_POST['calendar_id'];
        $this->fields['church_ecategory_id'] = $_POST['category_id'];
        if ( !isset($this->fields['public_time']) ) {
            $this->fields['public_time'] = ( $this->fields['public_time_am']=='am' ? $this->fields['public_time_hr'] : $this->fields['public_time_hr']*1 + 12).':'.$this->fields['public_time_min'].':00';
            $public_seconds = 3600*( $this->fields['public_time_am']=='am' ? $this->fields['public_time_hr'] : $this->fields['public_time_hr']*1 + 12) + 60*$this->fields['public_time_min']; 
        }
        if ( !isset($this->fields['duration']) ) {
            $this->fields['duration'] = $this->fields['duration_hr'].':'.$this->fields['duration_min'].':00';
            $dur_seconds = 3600*$this->fields['duration_hr'] + 60*$this->fields['duration_min'];
        } 
        //echo '<br>Setup: '.$this->fields['setup_time_hr'];
        if ( !isset($this->fields['setup_time']) && !empty($this->fields['setup_time_hr']) ) {
            $this->fields['setup_time'] = ( $_POST['setup_time_am']=='am' ? $this->fields['setup_time_hr'] : ($this->fields['setup_time_hr']*1 + 12)).':'.$this->fields['setup_time_min'].':00';
            //echo '<br>Setup--:'.$this->fields['setup_time'];
        }
        if ( isset($this->fields['formLoc']) && is_array($this->fields['formLoc']) ){
            $eventLocations = $this->fields['formLoc'];
        }
    //return false;
        // get the input data:
        $input_data = $this->fields;
        // fix times:
        if ( $input_data['public_time'] == ':0:00' || $input_data['event_timed'] != 'Y' ) {
            $input_data['public_time'] = '00:00:00';
            $public_seconds = 0;
        }
        if ( !isset($input_data['setup_time']) || (isset($input_data['setup_time']) && $input_data['setup_time'] == ':0:00' ) || $input_data['event_timed'] != 'Y' ) {
            $input_data['setup_time'] = '00:00:00';
        }
        if ( $input_data['event_timed'] != 'Y' ) {
            $dur_seconds = 0;
        }
        $start_seconds = strtotime($input_data['public_start']);
        $start_date = date('Y-m-d', $start_seconds);
        $input_data['public_start'] .= ' '.$input_data['public_time'];
        
        if ( isset($input_data['setup_time']) && $input_data['setup_time'] != '00:00:00' ) {// setup_time
            $input_data['start_date'] = $start_date.' '.$input_data['setup_time'];
        } else {
            $input_data['start_date'] = $input_data['public_start'];
        }
        if ( empty($input_data['public_end']) ) {
            $input_data['public_end'] = $input_data['public_start'];
        } else {
            $input_data['public_end'] = date('Y-m-d', strtotime($input_data['public_end']));
        }
        // end date needs to also have the end time so public_start + duration
        $input_data['end_date'] = date('Y-m-d H:i:s', $start_seconds + $public_seconds + $dur_seconds);
        
        if ( !empty($event_id) && is_object($event) ) {
            $input_data['verson'] = $default_data['version'] + 1;
            //$input_data['id'] = $event_id;
        } else {
            $event = $this->modx->newObject('ChurchEvents');
        }
        // extended data
        $input_data['extended'] = $this->Calendar->makeExtended($this->fields);
        $event->fromArray($input_data,'',true);
        
        // debug:
        //$modx->setDebug(true);
        
        // if repeating:
        if ( $input_data['repeat_type'] == 'none' || empty($input_data['repeat_type']) ) {
            // check for conflicts
            $html = $this->Calendar->checkLocationConflicts($event, $eventLocations);
            if ( !empty($html) ){
                // set the error message
                $this->setValue('locationConflicts', $html);
                $this->modx->toPlaceholder('locationConflicts', $html, $this->config['placeholderPrefix'],'');
                //echo 'FAILED';
                return false;
            }
            $this->Calendar->makeLocations($event, $eventLocations);
        } else if ( $input_data['repeat_type'] != 'none' && !empty($input_data['repeat_type']) ) {
            // edit_repeat
            $interval = 1;
            $days = array();
            switch($input_data['repeat_type']) {
                case 'daily':
                    $interval = $input_data['day_interval'];
                    break;
                case 'weekly':
                    $interval = $input_data['week_interval'];
                    $days = get_days('days',7);
                    break;
                case 'monthly':
                    $interval = $input_data['month_interval'];
                    $days = get_days('month_days',35);
                    break;
            }
            $event->set('days',implode(',', $days));
            //$event->save();
            //echo '<br>INT: '.$interval.'<br>Days:'.implode(',', $days);
            //echo 'date_default_timezone_set: ' . date_default_timezone_get() . '<br />';
            
            // Populate Repeating events: && 
            if ( empty($input_data['edit_repeat']) || ( isset($input_data['edit_repeat']) && $input_data['edit_repeat'] == 'all' ) ) {
                
                // get all repeating events:
                // remove all repeating events:
                //$event = $modx->getObject('chEvents', array('id' => $event_id));
                $existing_events = array();
                if ( $event->get('parent_id') > 0 ) {
                    $sib_events = $this->modx->getCollection('ChurchEvents',array(
                       'parent_id' => $event->get('parent_id'),
                    ));
                    foreach( $sib_events as $sib ) {
                        list($s_date, $tmp ) =  explode(' ',$sib->get('start_date'));
                        //echo '<br>Date: '.$s_date.' - ID: '.$sib->get('id');
                        $existing_events[$s_date] =  $sib->get('id');
                    }
                    // get parent:
                    $pr = $this->modx->getObject('ChurchEvents',$event->get('parent_id'));
                    // $pr->remove();
                    if ( is_object($pr) ) {
                        list($s_date, $tmp ) =  explode(' ',$pr->get('start_date'));
                        $existing_events[$s_date] =  $pr->get('id');
                    }
                    // $input_data['parent_id'] = 0;
                } else {
                    $child_events = $this->modx->getCollection('ChurchEvents',array(
                       'parent_id' => $event->get('id'),
                    ));
                    foreach( $child_events as $child ) {
                        list($s_date, $tmp ) =  explode(' ',$child->get('start_date'));
                        //echo '<br>Date: '.$s_date.' - ID: '.$child->get('id');
                        $existing_events[$s_date] =  $child->get('id');
                    }
                }
                require_once 'calendarpopulate.class.php';
                $cal = new calendarPopulate;
                //echo 'date_default_timezone_set: ' . date_default_timezone_get() . '<br />';
                
                $repeats = $cal->populate($start_date, $input_data['public_end'], $input_data['repeat_type'], $interval, $days );
                //$cal->timeZone();
                $input_data['parent_id'] = $event->get('id');
                unset($input_data['id']);
                $input_data['interval'] = $interval;
                $input_data['days'] = implode(',', $days);
                //print_r($repeats);
                $count = 0;
                $eventList = array();
                $conflictHtml = NULL;
                $exceptions = $this->Calendar->getExceptions($this->fields['exceptions']);
                foreach( $repeats as $n => $date ){
                    //echo '<br>Date: '.$date;
                    if ( $date == $start_date ) {
                        //continue;// this is the parent time already saved
                    }
                    if ( in_array($date,$exceptions) ) {
                        continue;
                    }
                    $input_data['public_start'] = $date.' '.$input_data['public_time'];
                    if ( isset($input_data['setup_time']) && $input_data['setup_time'] != '00:00:00'  ) {// setup_time
                        //echo '<br>Setup Time: '.$input_data['setup_time'];
                        $input_data['start_date'] = $date.' '.$input_data['setup_time'];
                    } else {
                        $input_data['start_date'] = $input_data['public_start'];
                    }
                    $input_data['end_date'] = date('Y-m-d H:i:s', strtotime($date) + $public_seconds + $dur_seconds);
                    $count++;
                    if ( $count == 1 ) {
                        // set the first date the a repeated date if they picked an invalid date: (the parent)
                        $event->set('public_start', $input_data['public_start']);
                        $event->set('start_date', $input_data['start_date']);
                        //$event->save();
                        $conflictHtml .= $this->Calendar->checkLocationConflicts($event, $eventLocations);
                    } else {
                        //echo '<br>Save Date: '.$date;
                        if ( is_array($existing_events) && isset($existing_events[$date]) ) {
                            //echo '<br>Old '.$date;
                            $e = $this->modx->getObject('ChurchEvents', array('id' => $existing_events[$date] ));
                            unset($existing_events[$date]);
                        } else {
                            // new object
                            $e = $this->modx->newObject('ChurchEvents');
                        }
                        // load it:
                        $e->fromArray($input_data,'',true);
                        $eventList[$date] = $e;
                        // save it:
                        // $e->save();
                        // check for conflicts
                        $conflictHtml .= $this->Calendar->checkLocationConflicts($e, $eventLocations);
                        
                    }
                }
                if ( !empty($conflictHtml) ){
                    // set the error message
                    $this->modx->toPlaceholder('locationConflicts', $conflictHtml, $this->config['placeholderPrefix'],'');
                    return false;
                }
                $this->Calendar->makeLocations($event, $eventLocations);
                // now save event to get ID if new event
                if ( !$event->save() ){
                    echo 'DID NOT SAVE: '.$this->modx->errorCode();
                    //echo mysql_error();
                    return false;
                }
                // now save events
                foreach($eventList as $date => $e ){
                    //echo '<br>E Date: '.$date;
                    //print_r($e);
                    $e->set('parent_id', $event->get('id'));
                    $this->Calendar->makeLocations($e, $eventLocations);
                    $e->save();
                }
                // now remove any that where not in the list:
                if ( is_array($existing_events) ) {
                    $remove_ids = array();
                    $remove_me = false;
                    foreach ( $existing_events as $date => $id ) {
                        $remove_ids[] = $id;
                        $remove_me = true;
                    }
                    // http://rtfm.modx.com/display/xPDO20/xPDOQuery
                    // select:
                    if ( $remove_me ) {
                        $remove_events = $this->modx->getCollection('ChurchEvents',array(
                           'id:IN' => $remove_ids,
                        ));
                        // remove: 
                        foreach( $remove_events as $re ) {
                            //echo '<br>Remove ID: '.$re->get('id');
                            $re->remove();
                        }
                    }
                }
            }
        }
        $this->Calendar->show_calendar = true;
            
        
        if ( $event->save() ) {
            
            if ( $this->Calendar->isAdmin() ) {
                $message = $this->modx->lexicon('churchevents.eventSaveMessage');
            } else {
                $message = $this->modx->lexicon('churchevents.requestSubmittedMessage');
                if ( empty($this->formit->config['emailTpl']) ) {
                    $this->formit->config['emailTpl'] = $this->Calendar->getFilter('emailRequestNoticeTpl');
                }
                $this->formit->config['emailFromName'] = $input_data['contact'];
                $this->formit->config['emailFrom'] = $input_data['contact_email'];
                $ecal = $this->modx->getObject('ChurchCalendar', array('id' => $event->get('church_calendar_id')));
                
                $this->formit->config['emailTo'] = $ecal->get('request_notify');
                // set CC for 
                
                $fields = $event->toArray();
                $fields['subject'] = $this->modx->lexicon('churchevents.emailRequestSubject');
                $fields['eventUrl'] = $this->Calendar->getUrl($event->get('id'), 'edit', 'full');
                $processed = $this->Calendar->processEvent($event);
                //print_r($processed);
                $fields = array_merge($fields, $processed);
                
                // get the locations
                // get location info
                if ( $this->Calendar->getFilter('useLocations') ) {
                    $locations = $event->getMany('EventLocations');
                    $locationTypes = array();
                    $locationOrder = array();
                    foreach($locations as $loc ) {
                        // 1 get the location
                        $location = $this->modx->getObject('ChurchLocations', array('id' => $loc->get('church_location_id'), 'published'=>'Yes'));
                        if ( !is_object($location)) {
                            continue;
                        }
                        if ( !isset($locationTypes[$location->get('church_location_type_id')])) {
                            // get the new location type
                            $locType = $this->modx->getObject('ChurchLocationType', array('id'=>$location->get('church_location_type_id')));
                            $locationTypes[$locType->get('name')] = $locType->toArray();
                            $locationOrder[] = $locType->get('name');
                        } 
                        $locationTypes[$locType->get('name')]['location'][$location->get('name')] = $location->toArray();
                    }
                    // set the loctaion type to natural order
                    natsort($locationOrder);
                    $locationType_string = '';
                    foreach( $locationOrder as $locTypeName ){
                        // get locations
                        $locations = $locationTypes[$locTypeName]['location'];
                        natsort($locations);
                        $location_string = '';
                        foreach($locations as $location){
                            // location is an array not an Object
                            $location_string .= $this->Calendar->getChunk($this->Calendar->getFilter('emailLocationTpl'), $location);
                        }
                        if (!empty($location_string) ) {
                            $tmp = $locationTypes[$locTypeName];
                            $tmp['locations'] = $location_string;
                            $locationType_string .= $this->Calendar->getChunk($this->Calendar->getFilter('emailLocationTypeTpl'), $tmp);
                        }
                    }
                    $fields['locationInfo'] = $locationType_string;
                } else {
                    $fields['locationInfo'] = $this->Calendar->getChunk($this->Calendar->getFilter('emailLocationTpl'));
                }
                
                /*
                // email person:
                // $id = $modx->documentIdentifier;
                $email = '<p>You have a new calendar request.</p>
                    <a href="'.$modx->makeUrl($modx->documentIdentifier,'',array('view' => 'event', 'event_id' => $event->get('id') ), 'full').'">Go to your site</a>';//$modx->getChunk('myEmailTemplate');
                $modx->getService('mail', 'mail.modPHPMailer');
                $ecal = $modx->getObject('chCalendar', array('id' => $event->get('church_calendar_id')));
                $modx->mail->address('to', $ecal->get('request_notify'));
                $modx->mail->set(modMail::MAIL_BODY, $email );
                $modx->mail->set(modMail::MAIL_FROM, $modx->getObject('modSystemSetting',array('key' => 'emailsender')) );
                $modx->mail->set(modMail::MAIL_FROM_NAME, 'Event Request');
                // $modx->mail->set(modMail::MAIL_SENDER,'Johnny Tester');
                $modx->mail->set(modMail::MAIL_SUBJECT,'You have a new calendar request');
                //$modx->mail->address('reply-to','me@xpdo.org');
                $modx->mail->setHTML(true);
                if (!$modx->mail->send()) {
                    $modx->log(modX::LOG_LEVEL_ERROR,'An error occurred while trying to send the email: '.$err);
                }
                $modx->mail->reset();
                */
                $this->email($fields);
            }
            // show saved message
            $this->modx->toPlaceholder('returnMessage', $message, $this->config['placeholderPrefix'],'');
            
            
        } else{
            $this->modx->toPlaceholder('returnMessage', '<h3 class="error">Error adding data line: '.__LINE__.' in file: '.__FILE__.'</h3>'.
            $this->modx->errorCode(), $this->config['placeholderPrefix'],'');
            // echo 'DID NOT SAVE: '.$this->modx->errorCode();
            //$modx->getErrors();
            //print_r($modx->errorInfo());
            //print_r($input_data);
            return false;
        }
        return true;
    }
    /**
     * this will delete a church event
     * PostHook
     * @return boolean - true on seccess and false on falure
     */ 
    public function deleteChurchEvent() {
        $event_id = $this->Calendar->getFilter('event_id');
        if ( !empty($event_id)){
            $event = $this->modx->getObject('ChurchEvents', array('id'=>$event_id));
        } else {
            echo 'No ID: '.$event_id;
            return false;
        }
        if ( isset($this->fields['delete_type']) && $this->fields['delete_type'] == 'all' ) {
            // find all siblings, children and or parents
            $existing_events = array();
            if ( $event->get('parent_id') > 0 ) {
                $sib_events = $this->modx->getCollection('ChurchEvents',array(
                   'parent_id' => $event->get('parent_id'),
                ));
                foreach( $sib_events as $sib ) {
                    $sib->remove();
                }
                // get parent:
                $pr = $this->modx->getObject('ChurchEvents',$event->get('parent_id'));
                $pr->remove();
            } else {
                $child_events = $this->modx->getCollection('ChurchEvents',array(
                   'parent_id' => $event->get('id'),
                ));
                foreach( $child_events as $child ) {
                    $child->remove();
                }
            }
        }
        if ( $event->remove() ) {
            $this->Calendar->show_calendar = true;
            $this->modx->toPlaceholder('returnMessage', 'Event has been deleted.', $this->config['placeholderPrefix'],'');
            return true;
        } else {
            return false;
        }
    }
}


/**
 * Old :
 */
function make_week($form, $pre, $st=0, $label=false){
    //global $form;
    $wk_days = array(
            0=>'Sun',
            1=>'Mon',
            2=>'Tue',
            3=>'Wed',
            4=>'Thu' ,
            5=>'Fri' ,
            6=>'Sat');
    $str = '';
    //for( $x = $st; $x <= $ed; $x++ ){
    foreach( $wk_days as $x => $day ) { 
        $d = $x;
        if ( $pre == 'days' && $x == 0 ){
            $d = 7;
            $st++;
        } else {
            $d = $st++;
        }
        $str .= '
        <td>
            '.$form->checkbox($pre.'_'.$d, 'Y', '', ' id="ck_'.$pre.'_'.$d.'" class="radio" ');
        if ( $label ) {
            $str .= '
            <label for="ck_'.$pre.'_'.$d.'">'.$day.'</label>';
        }
        $str .= '
        </td>'; 
    }
    return $str;
}
function get_days($pre, $end) {
    $days = array();
    for( $x=0; $x<=$end; $x++ ) {
        if ( isset($_REQUEST[$pre.'_'.$x]) && $_REQUEST[$pre.'_'.$x] == 'Y' ) {
            $days[] = $x;
        }
    }
    return $days;
}
  