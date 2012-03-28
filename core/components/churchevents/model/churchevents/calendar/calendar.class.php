<?php
/**
 * Calendar Class
 * @description this class will generate a calendar for MODX Revolution
 * 
 * @param modX $modx
 * @param requestHandler a reference to the current request handler instance
 * 
 */
class Calendar {
    
    /**
     * A reference to the request hanlder instance
     * @var myControllerRequest $myControllerRequest
     */
    public $requestHandler;
    /**
     * A reference to the modX instance
     * @var modX $modx
     */
    public $modx;
    
    /**
     * @var array $events this is a complete listing of the events for the current filter
     * @access protected
     */
    protected $events = array();
    /**
     * @var array $eventCount this holds the total number of events for the day
     *      use like eventCount[$date]
     * @access protected
     */
    protected $eventCount = array();
    /**
     * @var array $filters name=>value
     * @access protected
     */
    protected $filters = array();
    
    
    /**
     * @var boolean $add_link
     * @access protected
     */
    protected $add_link = false;
    /**
     * the base url for the calendar links
     * @var string $url
     * @access protected
     */
    protected $url = '';
    /**
     * @var string $params, any parms for generated links
     * @access protected
     */
    protected $params=NULL;
    /**
     * 
     * @access protected
     */
    protected $view = 'add';
    /**
     * @var array $day_array
     * @access protected
     */
    protected $day_array = array();
    /**
     * @var array $month_array
     * @access protected
     */
    protected $month_array = array();
    /**
     * @var array $show_day
     * @access protected
     */
    protected $show_day = array();
    /**
     * Show calendar views boolean
     * @var boolean $show_calendar
     */
    public $show_calendar = true;
    /**
     * @var calendar_array
     * @access protected
     */
    public $calendar_array = NULL;
    /**
     * @var category_array
     * @access protected
     */
    public $category_array = NULL;
    
    function __construct(modX $modx, $requestHandler) {
        $this->modx = &$modx;
        $this->requestHandler =& $requestHandler;
        
        # show the day on the calendar if true
        $this->show_day = array(
                1 => true,
                2 => true,
                3 => true,
                4 => true,
                5 => true,
                6 => true,
                7 => true,
            );
        // set the day names
        $this->day_array = array(
                1 => $this->modx->lexicon('churchevents.monday'),
                2 => $this->modx->lexicon('churchevents.tuesday'),
                3 => $this->modx->lexicon('churchevents.wednesday'),
                4 => $this->modx->lexicon('churchevents.thursday') ,
                5 => $this->modx->lexicon('churchevents.friday'),
                6 => $this->modx->lexicon('churchevents.saturday'),
                7 => $this->modx->lexicon('churchevents.sunday') );
        // set the month names
        $this->month_array = array(
                1 => $this->modx->lexicon('churchevents.january'),
                2 => $this->modx->lexicon('churchevents.february'),
                3 => $this->modx->lexicon('churchevents.march'),
                4 => $this->modx->lexicon('churchevents.april'),
                5 => $this->modx->lexicon('churchevents.may'),
                6 => $this->modx->lexicon('churchevents.june'),
                7 => $this->modx->lexicon('churchevents.july'),
                8 => $this->modx->lexicon('churchevents.august'),
                9 => $this->modx->lexicon('churchevents.september'),
                10 => $this->modx->lexicon('churchevents.october'),
                11 => $this->modx->lexicon('churchevents.november'),
                12 => $this->modx->lexicon('churchevents.december') );
        
        // load the calendars and categories
        $cals = $this->modx->getIterator('ChurchCalendar');
        foreach ( $cals as $chCalendar ) {
            $this->calendar_array[$chCalendar->get('id')] = $chCalendar->get('title');
        }
        $cats = $this->modx->getIterator('ChurchEcategory');
        foreach ( $cats as $chCat ) {
            $this->category_array[$chCat->get('id')] = $chCat->get('name');
        }
        // get basic permissions isAdmin or 
        
    }
    /**
     * Returns true if user is admin and false if they are not.
     * @return boolean 
     */
    public function isAdmin(){
        // log in via the manager or any other context (web)
        if ( $this->modx->user->isAuthenticated('mgr') || $this->modx->user->isAuthenticated($this->modx->context->get('key')) ) {
            return true;
        }
        return false;
    }
    /**
     * Get the controller config option
     * @param string $key
     * @return mixed $value or NULL if not set
     */
    public function getConfig($key) {
        if ( isset($this->requestHandler->cmpController->config[$key]) ) {
            return $this->requestHandler->cmpController->config[$key];
        }
        return NULL;
    }
    /**
     * Get the chunk using the controller get chunk, this allows you to call from file
     * @param string $name the name of the chunk
     * @param array $properties
     * @return mixed $value or NULL if not set
     */
    public function getChunk($name,$properties = array()) {
        return $this->requestHandler->cmpController->getChunk($name, $properties);
    }
    /**
     * make the base URL/link see: http://rtfm.modx.com/display/revolution20/modX.makeUrl
     * @param string $context
     * @param array $args
     * @param mixed $scheme
     * @return void
     */
    public function setUrl(string $context=NULL, array $args=NULL, $scheme= -1) {
        $tmp_base = $this->modx->makeUrl($this->filters['pageID'], $context, $args, $scheme);
        if ( substr_count($tmp_base,'?') == 1 ) {
            $tmp_base .= '&amp;';
        } else {
            $tmp_base .= '?';
        }
        $this->url = $tmp_base;
    }
    /**
     * get the url for an event/view
     * @param $event_id
     * @param $view
     * @param $scheme
     * @return  
     */
    public function getUrl($event_id, $view='edit', $scheme=-1){
        $args = array(
            'view' => $view,
            'event_id'=>$event_id,
        );
        return $this->modx->makeUrl($this->filters['pageID'], NULL, $args, $scheme);
    }
    
    /**
     * Process
     * @param array $scriptProperties
     * @return string $html
     */
    public function process($scriptProperties){
        // get filter input
        $this->getFilters($scriptProperties);
        // what about adding/editing events
        $this->setUrl();
        
        // load CSS/JS for page
        // set filters
        
        // lexicon to placeholders
        $this->modx->lexicon('addMessage');
        
        // make event list based on filters
        $html = '';
        switch ( $this->filters['view'] ) {
            case 'week':
                // make sue the day is on Sunday!
                $time = strtotime($this->filters['year'].'-'.$this->filters['month'].'-'.$this->filters['day']);
                $w = date("w",$time);
                if ( $w > 0 ) {
                    $time -= 24*3600*$w;
                    $this->filters['year'] = date('Y', $time);
                    $this->filters['month'] = date('n', $time);
                    $this->filters['day'] = date('j', $time);
                }
                break;
            case 'location' :
                $html = $this->getLocation($this->filters['location']);
                $this->show_calendar = false;
                break;
            case 'ical':
                $this->getiCal();
                $this->show_calendar = false;
                break;
            case 'rss':
                $html = $this->getRss();
                $this->show_calendar = false;
                break;
            case 'event':
                $this->lexiconToPlaceholders();
                $html = $this->getEvent();
                break;
            case 'add':
            case 'edit':
                if ( !$this->isAdmin()){
                    break;
                }
            case 'request':
                if ( isset( $_POST['cancel'])) {
                    break;
                }
                if ( !$this->isAdmin() && $this->filters['allowRequests'] ) {
                    $this->filters['event_id'] = 0;
                    if ( !empty($_POST) ) {
                        $_POST['status'] = 'submitted';
                    }
                }
                $this->show_calendar = false;
                $className = 'ManageEvent';
                $classPath = $this->getConfig('calendarPath');
                if ($this->modx->loadClass($className,$classPath,true,true)) {
                    $this->lexiconToPlaceholders();
                    //$grid = new $className($this);//,$this->config);
                    $event = new ManageEvent($this->modx, $this);//,$this->config);
                    // need to make the array
                    $myProperties = array(
                        'preHooks' => 'loadDefaults',
                        'hooks' => 'saveChurchEvent',//
                        'validate' => 'public_start:required:isDate=^'.$this->getFilter('dateFormat').'^,repeat_type:required,
                                       calendar_id:required:isNumber,category_id:required:isNumber,status:required,
                                       event_type:required,title:required,public_desc:required:allowTags,notes:allowTags,
                                       contact:required,contact_email:email:required',
                    );
                    $scriptProperties = array_merge($myProperties, $scriptProperties);
                    $event->processForm($scriptProperties);
                    
                } else {
                    $this->modx->log(modX::LOG_LEVEL_ERROR,'['.$this->getConfig('packageName').'] Could not load '.$className.' class.');
                    $event = NULL;
                }
                if ( $this->show_calendar ){
                    // show saved/sent message and then the calendar
                } else {
                    // load JS
                    $properties = array(
                            'assets_url' => MODX_ASSETS_URL,
                            'adminInfo' => ( $this->isAdmin() ? $this->getChunk($this->filters['eventFormAdminTpl']) : ''),
                            'repeatOptions' => ( $this->filters['showRepeatOptions'] ? $this->getChunk($this->filters['eventFormRepeatTpl']) : '' ),
                            'select_calendar' => $this->selectOptions(array_flip($this->calendar_array), $this->filters['church_calendar_id']),
                            'select_category' => $this->selectOptions(array_flip($this->category_array), $this->filters['church_ecategory_id']),
                            'month' => $this->month_array[$this->filters['month']],
                            'year' => $this->filters['year'],
                            'conflictMessage' => $this->modx->lexicon('churchevents.conflictMessage'),
                            'datepickerFormat' => $this->dateformatToJS($this->filters['dateFormat'])
                        );
                    $html .= $this->getChunk($this->filters['eventFormHeadTpl'], $properties);
                    // show form
                    $html .= $this->getChunk($this->filters['eventFormTpl'], $properties);
                } 
                break;
            case 'delete':
                if ( isset( $_POST['cancel'])) {
                    break;
                }
                $this->show_calendar = false;
                $className = 'ManageEvent';
                $classPath = $this->getConfig('calendarPath');
                if ($this->modx->loadClass($className,$classPath,true,true)) {
                    $this->lexiconToPlaceholders();
                    //$grid = new $className($this);//,$this->config);
                    $event = new ManageEvent($this->modx, $this);//,$this->config);
                    // need to make the array
                    unset($scriptProperties['validate']);
                    unset($scriptProperties['preHooks']);
                    unset($scriptProperties['hooks']);
                    $myProperties = array(
                        'preHooks' => 'loadDefaults',
                        'hooks' => 'deleteChurchEvent',//
                        'validate' => 'event_id:required:isNumber',
                    );
                    $scriptProperties = array_merge($myProperties, $scriptProperties);
                    $event->processForm($scriptProperties);
                    
                } else {
                    $this->modx->log(modX::LOG_LEVEL_ERROR,'['.$this->getConfig('packageName').'] Could not load '.$className.' class.');
                    $event = NULL;
                }
                if ( !$this->show_calendar ){
                    // load JS
                    $properties = array(
                            'assets_url' => MODX_ASSETS_URL,
                            'repeatOptions' => ( $this->filters['showRepeatOptions'] ? $this->getChunk($this->filters['deleteFormRepeatTpl']) : '' ),
                            'month' => $this->month_array[$this->filters['month']],
                            'year' => $this->filters['year'],
                        );
                    $html .= $this->getChunk($this->filters['deleteFormHeadTpl'], $properties);
                    // show form
                    $html .= $this->getChunk($this->filters['deleteFormTpl'], $properties);
                } 
                break;
            case 'list':
                 $html .= $this->getList();
                 $this->show_calendar = false;
                 break;
            default:
                break;
        }
        
        if ( $this->show_calendar ){
            $html .= $this->displayFilters();
            $html .= $this->navigation();
            
            switch ( $this->filters['view'] ) {
                case 'day':
                    $html .= $this->getDay();
                    break;
                case 'week':
                    $html .= $this->getWeek();
                    break;
                case 'year':
                    $html .= $this->getYear();
                    break;
                case 'month':
                default:
                    $html .= $this->getMonth();
                    break;
            }
        }
        
        return $html;
    }
    /**
     * get user filters
     * @param array $scriptProperties
     * @return void
     */
    public function getFilters($scriptProperties=array()){
        $saveView = false;
        if ( isset($_REQUEST['view']) && in_array($_REQUEST['view'], array('day', 'week', 'month', 'year') )) {
            $saveView = true;
        }
        $this->filters['view'] = $this->modx->getOption('view', $scriptProperties, $this->getUserValue('view', 'month', false, $saveView));
        /*if ( !in_array($view,$allow_veiws) ){
            $view = '';
        }*/
        /*
         *  @TO DO: create permissions for views
         */
        // 2. do they have permissions? 
        $this->filters['event_id'] = $this->getUserValue('event_id', 0, true);
        $this->filters['a'] = $this->getUserValue('a', 0);
        $this->filters['page_id'] = $this->getUserValue('id', 0);
        
        if ($this->filters['view'] == 'list' ) {
            $this->filters['month'] = $this->modx->getOption('month', $scriptProperties, date("n"));
            $this->filters['day'] = $this->modx->getOption('day', $scriptProperties, date("j") );
            $this->filters['week'] = $this->modx->getOption('week', $scriptProperties, date("W") );
            $this->filters['year'] = $this->modx->getOption('year', $scriptProperties, date("Y") );
            
            $this->filters['church_calendar_id'] = $this->modx->getOption('calendarID', $scriptProperties, 0);
            $this->filters['church_ecategory_id'] = $this->modx->getOption('categoryID', $scriptProperties, 0);
            
            $this->filters['filterSearch'] = $this->modx->getOption('filterSearch', $scriptProperties, NULL, false, true);
            $this->filters['filterLocations'] = $this->modx->getOption('filterLocations', $scriptProperties, 0, true, true);
            $this->filters['locations'] = $this->modx->getOption('loc', $scriptProperties,array(), true, true);
            $this->filters['location'] = $this->modx->getOption('location', $scriptProperties, 0, true, false);
            
        } else {
            $this->filters['month'] = $this->getUserValue('month', date("n"), true, true);
            $this->filters['day'] = $this->getUserValue('day', date("j"), true, true);
            $this->filters['week'] = $this->getUserValue('week', date("W"), true, true);
            $this->filters['year'] = $this->getUserValue('year', date("Y"), true, true);
            
            $this->filters['church_calendar_id'] = $this->getUserValue('church_calendar_id', $this->modx->getOption('calendarID', $scriptProperties, 0), true, true);
            $this->filters['church_ecategory_id'] = $this->getUserValue('church_ecategory_id', $this->modx->getOption('categoryID', $scriptProperties, 0), true, true);
            
            $this->filters['filterSearch'] = $this->getUserValue('filterSearch', NULL, false, true);
            $this->filters['filterLocations'] = $this->getUserValue('filterLocations', 0, true, true);
            if ( !$this->filters['filterLocations'] ) {
                $_REQUEST['loc'] = array();
            }
            $this->filters['locations'] = $this->getUserValues('loc', array(), true, true);
            $this->filters['location'] = $this->getUserValue('location', 0, true, false);
            
        }
        // set church_calendar_id in session
        
        // formats Date & Time
        $this->filters['dateFormat'] = $this->modx->getOption('churchevents.dateFormat',array(),'%m/%d/%Y');//'m/d/Y');
        $this->filters['am'] = $this->modx->getOption('am',array(),'a');
        $this->filters['pm'] = $this->modx->getOption('pm',array(),'p');
        
        // form filters:
        $this->filters['useLocations'] = $this->modx->getOption('churchevents.useLocations',array(), true);
        $this->filters['allowRequests'] = $this->modx->getOption('churchevents.allowRequests',array(), false);
        $this->filters['prominent'] = $this->modx->getOption('prominent', $scriptProperties, NULL);
        // locations - this is from the filter gui
        
        // for the add/edit form
        $this->filters['formLocations'] = $this->getUserValues('formLoc', array(), true);
        $this->filters['showRepeatOptions'] = false;
        // the id of the page to make the event urls
        $default_id = 0;
        if ( is_object($this->modx->resource) ) {
            $default_id = $this->modx->resource->get('id');
        }
        $this->filters['pageID'] = $this->modx->getOption('churchevents.pageID', $scriptProperties, $default_id);
        if ( empty($this->filters['pageID']) ) {
            $this->filters['pageID'] = $default_id;
        } 
        $this->filters['rssPageID'] = $this->modx->getOption('churchevents.rssPageID', $scriptProperties, 0);
        $this->filters['limit'] = $this->modx->getOption('limit', $scriptProperties, 15);
        // chunks
        $skin = $this->modx->getOption('skin', $scriptProperties, 'churchevents');
        $this->filters['emailRequestNoticeTpl'] = $this->modx->getOption('emailRequestNoticeTpl', $scriptProperties, 'emailRequestNoticeTpl');
        $this->filters['emailBasicLocationTpl'] = $this->modx->getOption('emailBasicLocationTpl', $scriptProperties, 'emailBasicLocationTpl');
        $this->filters['emailLocationTypeTpl'] = $this->modx->getOption('emailLocationTypeTpl', $scriptProperties, 'emailLocationTypeTpl');
        $this->filters['emailLocationTpl'] = $this->modx->getOption('emailLocationTpl', $scriptProperties, 'emailLocationTpl');
        
        $this->filters['rssTpl'] = $this->modx->getOption('rssTpl', $scriptProperties, 'eventsRssTpl');
        $this->filters['rssItemTpl'] = $this->modx->getOption('rssItemTpl', $scriptProperties, 'eventsRssItemTpl');
        
        $this->filters['headTpl'] = $this->modx->getOption('headTpl', $scriptProperties, $skin.'_headTpl');
        // Day veiw:
        $this->filters['dayEventTpl'] = $this->modx->getOption('dayEventTpl', $scriptProperties, $skin.'_DayEventTpl');
        $this->filters['dayHolderTpl'] = $this->modx->getOption('dayHolderTpl', $scriptProperties, $skin.'_DayHolderTpl');
        // Week View:  weekColumnHeadTpl, weekColumnTpl, weekRowTpl, weekTableTpl 
        $this->filters['weekTableTpl'] = $this->modx->getOption('weekTableTpl', $scriptProperties, $skin.'_calTableTpl');
        $this->filters['weekRowTpl'] = $this->modx->getOption('weekRowTpl', $scriptProperties, $skin.'_calRowTpl');
        $this->filters['weekEventTpl'] = $this->modx->getOption('weekEventTpl', $scriptProperties, $skin.'_calEventTpl');
        $this->filters['weekDayHolderTpl'] = $this->modx->getOption('weekDayHolderTpl', $scriptProperties, $skin.'_calDayHolderTpl');
        $this->filters['weekColumnHeadTpl'] = $this->modx->getOption('weekColumnHeadTpl', $scriptProperties, $skin.'_calColumnHeadTpl');
        $this->filters['weekColumnTpl'] = $this->modx->getOption('weekColumnTpl', $scriptProperties, $skin.'_calColumnTpl');
        
        $this->filters['categoryHeadTpl'] = $this->modx->getOption('categoryHeadTpl', $scriptProperties, $skin.'_categoryHeadTpl');
        $this->filters['calFilterTpl'] = $this->modx->getOption('calFilterTpl', $scriptProperties, $skin.'_calFilterTpl');
        if ( $this->isAdmin() ) {
            $this->filters['calFilterTpl'] = $this->modx->getOption('calAdminFilterTpl', $scriptProperties, $this->filters['calFilterTpl']);
        }
        // filterLocationTpl
        $this->filters['calFilterLocationTypeTpl'] = $this->modx->getOption('calFilterLocationTypeTpl', $scriptProperties, $skin.'_CalFilterLocationTypeTpl');
        $this->filters['calFilterLocationTpl'] = $this->modx->getOption('calFilterLocationTpl', $scriptProperties, $skin.'_CalFilterLocationTpl');
        $this->filters['calNavTpl'] = $this->modx->getOption('calNavTpl', $scriptProperties, $skin.'_calNavTpl');
        
        // Month view:
        $this->filters['calTableTpl'] = $this->modx->getOption('calTableTpl', $scriptProperties, $skin.'_calTableTpl');
        $this->filters['calRowTpl'] = $this->modx->getOption('calRowTpl', $scriptProperties, $skin.'_calRowTpl');
        $this->filters['calEventTpl'] = $this->modx->getOption('calEventTpl', $scriptProperties, $skin.'_calEventTpl');
        $this->filters['calDayHolderTpl'] = $this->modx->getOption('calDayHolderTpl', $scriptProperties, $skin.'_calDayHolderTpl');
        $this->filters['calColumnHeadTpl'] = $this->modx->getOption('calColumnHeadTpl', $scriptProperties, $skin.'_calColumnHeadTpl');
        $this->filters['calColumnTpl'] = $this->modx->getOption('calColumnTpl', $scriptProperties, $skin.'_calColumnTpl');
        // Year view:
        $this->filters['yearTableTpl'] = $this->modx->getOption('yearTableTpl', $scriptProperties, $skin.'_yearTableTpl');
        $this->filters['yearRowTpl'] = $this->modx->getOption('yearRowTpl', $scriptProperties, $skin.'_calRowTpl');
        $this->filters['yearColumnHeadTpl'] = $this->modx->getOption('yearColumnHeadTpl', $scriptProperties, $skin.'_calColumnHeadTpl');
        $this->filters['yearColumnTpl'] = $this->modx->getOption('yearColumnTpl', $scriptProperties, $skin.'_yearColumnTpl');
        
        //$this->filters[''] = $skin.'_';
        $this->filters['eventDescriptionTpl'] = $this->modx->getOption('eventDescriptionTpl', $scriptProperties, $skin.'_eventDescriptionTpl');
        $this->filters['eventDescriptionBasicLocationTpl'] = $this->modx->getOption('eventDescriptionBasicLocationTpl', $scriptProperties, $skin.'_eventDescriptionBasicLocationTpl');
        $this->filters['eventDescriptionLocationTypeTpl'] = $this->modx->getOption('eventDescriptionLocationTypeTpl', $scriptProperties, $skin.'_eventDescriptionLocationTypeTpl');
        $this->filters['eventDescriptionLocationTpl'] = $this->modx->getOption('eventDescriptionLocationTpl', $scriptProperties, $skin.'_eventDescriptionLocationTpl');
        //$this->filters[''] = $skin.'_';
        $this->filters['eventFormHeadTpl'] = $this->modx->getOption('eventFormHeadTpl', $scriptProperties, $skin.'_eventFormHeadTpl');
        $this->filters['eventFormTpl'] = $this->modx->getOption('eventFormTpl', $scriptProperties, $skin.'_eventFormTpl');
        $this->filters['eventFormConflictTpl'] = $this->modx->getOption('eventFormConflictTpl', $scriptProperties, $skin.'_eventFormConflictTpl');
        $this->filters['eventFormAdminTpl'] = $this->modx->getOption('eventFormAdminTpl', $scriptProperties, $skin.'_eventFormAdminTpl');
        $this->filters['eventFormRepeatTpl'] = $this->modx->getOption('eventFormRepeatTpl', $scriptProperties, $skin.'_eventFormRepeatTpl');
        $this->filters['eventFormBasicLocationTpl'] = $this->modx->getOption('eventFormBasicLocationTpl', $scriptProperties, $skin.'_eventFormBasicLocationTpl');
        $this->filters['eventFormLocationTypeTpl'] = $this->modx->getOption('eventFormLocationTypeTpl', $scriptProperties, $skin.'_eventFormLocationTypeTpl');
        $this->filters['eventFormLocationTpl'] = $this->modx->getOption('eventFormLocationTpl', $scriptProperties, $skin.'_eventFormLocationTpl');
        //$this->filters[''] = $skin.'_';
        $this->filters['deleteFormHeadTpl'] = $this->modx->getOption('deleteFormHeadTpl', $scriptProperties, $skin.'_deleteFormHeadTpl');
        $this->filters['deleteFormTpl'] = $this->modx->getOption('deleteFormTpl', $scriptProperties, $skin.'_deleteFormTpl');
        $this->filters['deleteFormRepeatTpl'] = $this->modx->getOption('deleteFormRepeatTpl', $scriptProperties, $skin.'_deleteFormRepeatTpl');
        // location view
        $this->filters['locationDescriptionTpl'] = $this->modx->getOption('locationDescriptionTpl', $scriptProperties, $skin.'_locationDescriptionTpl');
        
        // list 
        $this->filters['listEventTpl'] = $this->modx->getOption('listEventTpl', $scriptProperties, $skin.'_listEventTpl');
        $this->filters['listDayHolderTpl'] = $this->modx->getOption('listDayHolderTpl', $scriptProperties, $skin.'_listDayHolderTpl');
    }
    /**
     * get a GET, POST, REQUEST, SESSION or COOKIE
     * @param string $key
     * @param mixed $default the default value
     * @param boolean $is_numeric=false
     * @param boolean $saveSession=false
     * @param boolean $saveCookie=false
     * @return mixed $value
     */
    public function getUserValue($key, $default, $is_numeric=false, $saveSession=false, $saveCookie=false) {
        $value = $default;
        if ( isset($_REQUEST[$key]) && !$is_numeric || ( isset($_REQUEST[$key]) && is_numeric($_REQUEST[$key]) ) ){
            $value = $_REQUEST[$key];
        } else if ( isset($_SESSION[$key]) && !$is_numeric || ( isset($_SESSION[$key]) && is_numeric($_SESSION[$key]) ) ){
            $value = $_SESSION[$key];
        } else if ( isset($_REQUEST[$key]) && is_array($_REQUEST[$key]) ) {
            $value = $_SESSION[$key];
        } else if ( isset($_COOKIE[$key]) && !$is_numeric || ( isset($_COOKIE[$key]) && is_numeric($_COOKIE[$key]) ) ){
            $value = $_COOKIE[$key];
        } 
        if ( $saveSession ) {
            $_SESSION[$key] = $value;
        } 
        if ( $saveCookie ) {
            setcookie($key, $value, 30*3600*24/*, '/'*/);
            //$_COOKIE[$key] = $value;
        }
        return $value;
    }
    /**
     * get a GET, POST, REQUEST, SESSION or COOKIE that is an array
     * @param string $key
     * @param array $default the default value
     * @param boolean $is_numeric=false
     * @param boolean $saveSession=false
     * @param boolean $saveCookie=false
     * @return array $values
     */
    public function getUserValues($key, $default, $is_numeric=false, $saveSession=false, $saveCookie=false) {
        $values = $default;
        if ( isset($_REQUEST[$key]) ) {
            $values = $_REQUEST[$key];
        } else if ( isset($_SESSION[$key]) ){
            $values = $_SESSION[$key];
        } if ( isset($_COOKIE[$key]) ){
            $values = $_COOKIE[$key];
        } 
        if ($is_numeric ) {
            $tmp = array();
            foreach($values as $value ) {
                if ( is_numeric($value) ){
                    $tmp[] = $value;
                }
            }
            $values = $tmp;
        }
        if ( $saveSession ) {
            $_SESSION[$key] = $values;
        } 
        if ( $saveCookie ) {
            setcookie($key, $values, 30*3600*24/*, '/'*/);
            //$_COOKIE[$key] = $values;
        }
        return $values;
    }
    
    /**
     * get a filter
     * @param string $key
     * @return $value or NULL
     */
    public function getFilter($filter){
        $value = NULL;
        if (isset($this->filters[$filter])){
            $value = $this->filters[$filter];
        }
        return $value;
    }
    /**
     * set a filter
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function setFilter($filter, $value){
        $this->filters[$filter] = $value;
    }
    /**
     * display the filters
     * @return string $html
     */
    public function displayFilters(){
        if ( empty($this->calendar_array) ) {
            $cals = $this->modx->getIterator('ChurchCalendar');
            foreach ( $cals as $chCalendar ) {
                $this->calendar_array[$chCalendar->get('id')] = $chCalendar->get('title');
            }
        }
        if ( empty($this->category_array) ) {
            $cats = $this->modx->getIterator('ChurchEcategory');
            foreach ( $cats as $chCat ) {
                $this->category_array[$chCat->get('id')] = $chCat->get('name');
            }
        }
        // get locations:
        $locFilters = NULL;
        // location stuff
        if ( $this->getFilter('useLocations') ) {
            $c = $this->modx->newQuery('ChurchLocationType') ;//, array('public' => 'Yes'));
            $c->sortby('name', 'ASC');
            $locationTypes = $this->modx->getIterator('ChurchLocationType', $c);
            
            /* iterate */
            $list = array();
            $locationType_string = '';
                //print_r($this->filters['locations']);
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
                    $locProperties['input_name'] = 'loc[]';
                    $locProperties['is_checked'] = $this->isChecked((in_array($location->get('id'),$this->filters['locations']) ? 1 : 0), 1);
                    $location_string .= $this->getChunk($this->getFilter('calFilterLocationTpl'), $locProperties);
                }
                if (!empty($location_string) ) {
                    $properties['locations'] = $location_string;
                    $locationType_string .= $this->getChunk($this->getFilter('calFilterLocationTypeTpl'), $properties);
                }
                
            }
            $locFilters = $locationType_string;
            
        } 
        $properties = array(
                'assets_url' => MODX_ASSETS_URL,
                'select_calendar' => $this->selectOptions(array_flip($this->calendar_array), $this->filters['church_calendar_id']),
                'select_category' => $this->selectOptions(array_flip($this->category_array), $this->filters['church_ecategory_id']),
                'month' => $this->month_array[$this->filters['month']],
                'year' => $this->filters['year'],
                'day' => $this->filters['day'],
                'cDay' => date("j"),
                'cMonth' => date("n"),
                'cYear' => date("Y"),
                'view' => $this->filters['view'],
                'icalUrl' => $this->url.'view=ical',
                'rssUrl' => $this->modx->makeUrl($this->filters['rssPageID']),
                'locationInfo' => $locFilters,
                'filterLocations_checked' => $this->isChecked($this->getFilter('filterLocations'), 1),
                'filterLocations' => $this->getFilter('filterLocations'),
                'filterLocationsY_label' => $this->modx->lexicon('churchevents.filterLocationsY_label'),
                'filterLocationsN_label' => $this->modx->lexicon('churchevents.filterLocationsN_label'),
                'filterSearch_label' => $this->modx->lexicon('churchevents.filterSearch_label'),
                'filterSearch' => $this->getFilter('filterSearch')
            );
        $html = $this->getChunk($this->filters['calFilterTpl'], $properties);
        return $html;
    }
    
    /**
     * set the month 
     * @param int $month
     */
    public function setMonth($month/*=date("m")*/ ) {
        $this->filters['month'] = $month;
    }
    /**
     * set the year 
     * @param int $year
     */
    public function setYear($year/*=date("m")*/ ) {
        $this->filters['year'] = $year;
    }
    /**
     * load the CSS and JS
     * @return void
     */
    public function loadHead(){
        // this needs to go through a loop to get each category/calendar
        $this->filters['cssTpl'];
        $cats = $this->modx->getIterator('ChurchEcategory');
        $categoryHeadTpl = '';
        foreach ( $cats as $chCat ) {
            $this->category_array[$chCat->get('name')] = $chCat->get('id');
            $properties = array(
                    'category_id' => $chCat->get('id'),
                    'background' => $chCat->get('background'),
                    'color' => $chCat->get('color'),
                    'name' => $chCat->get('name'),
                );
            $categoryHeadTpl .= $this->getChunk($this->filters['categoryHeadTpl'], $properties);
        } 
        
        $properties = array(
                'assets_url' => MODX_ASSETS_URL,
                'categoryHeadTpl' => $categoryHeadTpl,
                'month' => $this->month_array[$this->filters['month']],
                'year' => $this->filters['year']
            );
        $html = $this->getChunk($this->filters['headTpl'], $properties);
        
        //$modx->regClientStartupScript();
        $this->modx->regClientStartupHTMLBlock($html);
    }
    /**
     * gets the current month grid
     * @return string $month;
     */
    public function getMonth(){
        // get events
        $this->loadEvents();
        //print_r($this->events);
        $month_days = date("t", mktime(0, 0, 0, $this->filters['month'], 1, $this->filters['year']));// days in the current month
        //echo '<br />Days of Month: '.$month_days;
            # N - numeric representation of the day of the week (added in PHP 5.1.0) - 1 (for Monday) through 7 (for Sunday)
            # org - l "L" - A full textual representation of the day of the week
        # get the offset
        $offset = date("N", mktime(0, 0, 0, $this->filters['month'], 1, $this->filters['year']));//
        if( $offset == 7){
            $offset = 0;
        }
        // permission to add?
        $link_view = NULL;
        if ( $this->isAdmin() ) { // $this->modx->user->isAuthenticated('mgr') ) {
            $this->add_link = true;
            $link_view = 'add';
        } else if ( $this->filters['allowRequests'] ) {
            $link_view = 'request';
        }
        //echo '<br />Offset: '.$offset;
        # get the pre filler start date
        if( $this->filters['month'] > 1){
            $pre_month = $this->filters['month'] - 1;
            $pre_year = $this->filters['year'];
        }else{
            $pre_month = 12;
            $pre_year = $this->filters['year'] - 1;
        }
        $pre_day = date("t", mktime(0, 0, 0, $pre_month, 1, $pre_year)) - $offset + 1;//day is the previous month
        # get the pre filler start date
        if( $this->filters['month'] < 12 ){
            $after_month = $this->filters['month'] + 1;
            $after_year = $this->filters['year'];
        }else{
            $after_month = 1;
            $after_year = $this->filters['year'] + 1;
        }
        $after_len = 7 - ($month_days + $offset)%7;
        if($after_len == 7){
            $after_len = 0;
        }
        
        # Make the table headers
        $calColumnHeadTpl = '';
        if( $this->show_day[7]){ 
            // this is setting sunday first on the list
            $calColumnHeadTpl .= $this->getChunk($this->filters['calColumnHeadTpl'], array('weekDay' => $this->day_array[7]) );
        }
        for ($count = 1; $count < 7; $count++) {
            if( $this->show_day[$count]){
                $calColumnHeadTpl .= $this->getChunk($this->filters['calColumnHeadTpl'], array('weekDay' => $this->day_array[$count]) );
            }
        }
        $calColumnTpl = '';
        ## pre filler columns
        for ($count = 0; $count < $offset; $count++) {
            $tmp = $count;
            if( $tmp == 0){
                $tmp = 7;
            }
            if( $this->show_day[$tmp]) {
                $tmp_date =  $pre_year.'-'.$pre_month.'-'.$pre_day;//YYYY-MM-DD
                $add_url = ( !empty($link_view) ? $this->url.'view='.$link_view.'&amp;start_date='.$pre_year.'-'.$pre_month.'-'.$pre_day.'&amp;'.$this->params : '' );
                $properties = array(
                        'assets_url' => MODX_ASSETS_URL,
                        'column_class' => 'grey',
                        'month' => $this->month_array[$pre_month],
                        'year' => $pre_year,
                        'day' => $pre_day,
                        'day_url' => $this->url.'view=day&amp;month='.$pre_month.'&amp;day='.$pre_day.'&amp;year='.$pre_year,
                        'allow_add' => $this->add_link,
                        'add_message' => $this->modx->lexicon('addMessage'),
                        'add_url' => $add_url,
                        'add_link' => ( $this->add_link ? '<a class="addEvent" href="'.$add_url.'" title="Add event to '.$tmp_date.'">[ + ]</a>' : '' ),
                        'calDayHolderTpl' => $this->eventList($tmp_date)
                    );
                $calColumnTpl .= $this->getChunk($this->filters['calColumnTpl'], $properties);
            }
            $pre_day++;
        }
        $calRowTpl = '';
        ## the day columns
        for ($count = 1; $count <= $month_days; $count++) {
            $cur_day = ($count + $offset)%7;//0 to 6 - 0 is saturday, sunday, mon, tue, wed, thr, fri
            if( $cur_day == 1) {// Sunday = 1  - start new row
                
                $calRowTpl .= $this->getChunk($this->filters['calRowTpl'], array('calColumnTpl' => $calColumnTpl ));
                // reset the cal column for the new row(week)
                $calColumnTpl = '';
            }
            # reassign to day array
            if($cur_day == 0){
                $cur_day = 6;//Saturday
            }
            elseif($cur_day == 1){
                $cur_day = 7;//Sunday
            }
            else{
                $cur_day -= 1;//Set to proper day
            }
            if( $this->show_day[$cur_day]){
                /*$str .= '
                <td>
                    <div class="dayWrapper">
                        <span class="date">'.$count.'</span>
                        '.( $this->add_link ? '<a class="addEvent" href="'.$this->url.'view='.$this->view.'&amp;start_date='.$this->filters['year'].'-'.$this->filters['month'].'-'.$count.'&amp;'.$this->params.'" title="Add event to '.$this->filters['month'].'/'.$count.'/'.$this->filters['year'].'">[ + ]</a>' : '' ).'
                        '.$this->eventList($this->filters['year'].'-'.$this->filters['month'].'-'.$count).'
                    </div>
                </td>';*/
                //' CUR: '.(($count + $offset)%7).' - Count: '.$count.' - OFF: '.$offset.
                $add_url = ( !empty($link_view) ? $this->url.'view='.$link_view.'&amp;start_date='.$this->filters['year'].'-'.$this->filters['month'].'-'.$count.'&amp;'.$this->params : '' );
                $properties = array(
                        'assets_url' => MODX_ASSETS_URL,
                        'column_class' => '',
                        'month' => $this->month_array[$this->filters['month']],
                        'year' => $this->filters['year'],
                        'day' => $count,
                        'day_url' => $this->url.'view=day&amp;month='.$this->filters['month'].'&amp;day='.$count.'&amp;year='.$this->filters['year'],
                        'allow_add' => $this->add_link,
                        'add_message' => $this->modx->lexicon('addMessage'),
                        'add_url' => $add_url,
                        'add_link' => ( $this->add_link ? '<a class="addEvent" href="'.$add_url.'" title="Add event to '.$tmp_date.'">[ + ]</a>' : '' ),
                        'calDayHolderTpl' => $this->eventList($this->filters['year'].'-'.$this->filters['month'].'-'.$count)
                    );
                $calColumnTpl .= $this->getChunk($this->filters['calColumnTpl'], $properties);
            }
        }
        if( $cur_day == 7 ){
            $cur_day = 0;//set to 0 since the loop advances before use!
        }
        ## after filler columns
        for ($count = 1; $count <= $after_len; $count++) {
            $cur_day++;
            if( $this->show_day[$cur_day]){ // keeping from above
                $tmp_date =  $after_year.'-'.$after_month.'-'.$count;//YYYY-MM-DD
                /* $str .=  '
                <td class="grey">
                    <div class="dayWrapper">
                        <span class="date">'.$count.'</span>
                        '.( $this->add_link ? '<a class="addEvent" href="'.$this->url.'view='.$this->view.'&amp;start_date='.$after_year.'-'.$after_month.'-'.$count.'&amp;'.$this->params.'" title="Add event to '.$tmp_date.'">[ + ]</a>' : '' ).'
                        '.$this->eventList($tmp_date).'
                    </div>
                </td>'; */
                $add_url = ( !empty($link_view) ? $this->url.'view='.$link_view.'&amp;start_date='.$after_year.'-'.$after_month.'-'.$count.'&amp;'.$this->params : '' );
                $properties = array(
                        'assets_url' => MODX_ASSETS_URL,
                        'column_class' => 'grey',
                        'month' => $after_month,
                        'year' => $after_year,
                        'day' => $count,
                        'day_url' => $this->url.'view=day&amp;month='.$after_month.'&amp;day='.$count.'&ampyear='.$after_year,
                        'allow_add' => $this->add_link,
                        'add_message' => $this->modx->lexicon('addMessage'),
                        'add_url' => $add_url,
                        'add_link' => ( $this->add_link ? '<a class="addEvent" href="'.$add_url.'" title="Add event to '.$tmp_date.'">[ + ]</a>' : '' ),
                        'calDayHolderTpl' => $this->eventList($tmp_date)
                    );
                $calColumnTpl .= $this->getChunk($this->filters['calColumnTpl'], $properties);
                
                
            }
        }
        // the last row
        $calRowTpl .= $this->getChunk($this->filters['calRowTpl'], array('calColumnTpl' => $calColumnTpl ));
        
        // now fill the table
        $properties = array(
                'calColumnHeadTpl' => $calColumnHeadTpl,
                'calRowTpl' => $calRowTpl
                );
        $calTableTpl = $this->getChunk($this->filters['calTableTpl'], $properties );
        return $calTableTpl;
    }
    
    
    
    
    /**
     * @return array events
     */
    public function getEvents(){
        return $this->events;
    }
    /**
     * @param string $view
     * @return array $events
     */
    public function loadEvents($view=NULL){
        if ( empty($view) ) {
            $view = $this->filters['view'];
        }
        $query = $this->modx->newQuery('ChurchEvents');
        // now sort by location(s) $this->filters['locations']
        if ( !empty($this->filters['locations']) && $this->filters['useLocations']) {
            $query->innerJoin('ChurchEventLocations','EventLocations');
            $query->where(array( 'EventLocations.church_location_id:IN' => $this->filters['locations']));
        }
        //$c->innerJoin('BoxOwner','Owner'); // arguments are: className, alias
        switch ($view) {
            case 'list':
                $start_where = date("Y-m-d", (strtotime($this->filters['year'].'-'.$this->filters['month'].'-'.$this->filters['day'])) );
                // limit? prominent?
                $query->where(array(
                    'start_date:>=' => $start_where
                ));
                break;
            case 'day':
                $time = strtotime($this->filters['year'].'-'.$this->filters['month'].'-'.$this->filters['day']);
                $start_where = date("Y-m-d", $time);
                $end_where =  date("Y-m-d", $time+24*3600);
                // limit? prominent?
                $query->where(array(
                    'start_date:LIKE' => $start_where.'%'
                ));
                break;
            case 'week' :
                $time = strtotime($this->filters['year'].'-'.$this->filters['month'].'-'.$this->filters['day']);
                // set start date to Sunday
                // w - 0 to 6 for Sunday to Saturday, W - # of the week 
                // addjust to make the start date on Sunday
                if( date("w",$time) > 0 ){
                     $time-= 3600*60*date("w",$time);
                     // now reset the day filter:
                     $_REQUEST['day'] = date("j",$time);
                     $this->filters['day'] = $this->getUserValue('day', date("j",$time), true, true);
                }
                $start_where = date("Y-m-d", $time );// Sunday 
                $end_where = date("Y-m-d", $time+6*3600*24 );// Saturday 
                $query->where(array(
                    'start_date:>=' => $start_where,
                    'start_date:<=' => $end_where
                ));
                break;
            case 'month':
            default:
                $start_where = date("Y-m-d", (strtotime($this->filters['year'].'-'.$this->filters['month'].'-01')-6*3600*24) );
                $end_where = date("Y-m-d", (strtotime($this->filters['year'].'-'.$this->filters['month'].'-31')+6*3600*24) );
                $query->where(array(
                    'start_date:>=' => $start_where,
                    'start_date:<=' => $end_where
                ));
                break;
        }
        if ( !empty($this->filters['prominent'])) {
            $query->andCondition(array( 'prominent' => $this->filters['prominent'] ) );
        }
        /**
         * SELECT * FROM modx_church_events ce
JOIN modx_church_event_locations cel ON cel.church_event_id = ce.id
WHERE
    ce.church_calendar_id = 1 AND
    ce.church_ecategory_id IN(2) AND
    cel.church_location_id IN (1,2,3,4,5)
ORDER BY ce.start_date ASC 
         */
        
        // this is currently only allow 1 calander view - should make this many
        if ( $this->filters['church_calendar_id'] > 0 ) {
            $query->andCondition(array( 'church_calendar_id' => $this->filters['church_calendar_id'] ) );
        }
        // church_ecategory_id
        if ( $this->filters['church_ecategory_id'] > 0 ) {
            $query->andCondition(array( 'church_ecategory_id' => $this->filters['church_ecategory_id'] ) );
        }
        // 
        if ( !empty($this->filters['filterSearch']) ) {
            $query->andCondition(array( 'title:LIKE' => '%'.$this->filters['filterSearch'].'%' ) );
        }
        if (!$this->isAdmin() ) { // !$this->modx->user->isAuthenticated('mgr') ) {
            //echo '<br>test status <br>';
            $query->andCondition(array( 'status' => 'approved' ) );
        }
        $query->sortby('start_date','ASC');
        if ( $view == 'list' ) {
            $query->limit($this->filters['limit']);
        }
        
        //$query->prepare();
        //echo 'SQL:<br>'.$query->toSQL().'<br>';
        //$c->limit(5);
        $ev = $this->modx->getIterator('ChurchEvents',$query);
        //echo 'SQL:<br>'.$query->toSQL();
        $this->events = array();
        foreach ($ev as $e_id => $event ) {
            // $event->toArray();
            //echo '<br>id: '.$e_id;
            list($date) = explode(' ',$event->get('start_date'));
            $this->events[$date][$event->get('id')] = $this->processEvent($event);
        }
    }
    
    /**
     * @param $date YYYY-MM-DD
     * @param $eventTplFiler 
     * @param $dayTplFilter 
     *  
     * @return string $str this is a list of the days events
     */
    public function eventList($date, $eventTplFilter='calEventTpl', $dayTplFilter='calDayHolderTpl'){
        # this gets the events for that day
        $calDayHolderTpl = NULL;
        if( is_array($this->events) ){
            // fix date:
            list($y, $m, $d) = explode('-', $date);
            $date = $y.'-'.( strlen($m)==1 ? '0'.$m : $m ).'-'.( strlen($d)==1 ? '0'.$d : $d );
            $day_array = $this->events[$date];
            //echo '<br>Day: '.$date.' -- '.$day_array;
            //print_r($this->events[$date]);
            if( is_array($day_array) ){
                # go thourgh the events
                $calEventTpl = '';
                foreach($day_array as $e_id => $array){
                    //print_r($array);
                    $calEventTpl .= $this->getChunk($this->filters[$eventTplFilter], $array);
                }
                //echo 'TPL: '.$eventTplFilter. ' --- '. $calEventTpl;
                // $add_url = ( $this->add_link ? $this->url.'view='.$this->view.'&amp;start_date='.$after_year.'-'.$after_month.'-'.$count.'&amp;'.$this->params : '' );
                $properties = array(
                        'month' => $m,
                        'year' => $y,
                        'day' => $d,
                        'calEventTpl' => $calEventTpl
                    );
                $calDayHolderTpl = $this->getChunk($this->filters[$dayTplFilter], $properties);
            } else{
                //$str = $day_array;
            }
        }
        return $calDayHolderTpl;
    }
    /**
     * This method takes a time like 18:30 and make it readable: 6:30 p
     * @param int $hr the hour
     * @param int $min the minute
     * @return string $time
     */
    public function formatTime($hr, $min) {
        $hr = trim($hr);
        $am = $this->filters['am'];
        if( $min > 0 && $min < 10){
            $min = '0'.$min;
        } elseif ( $min >= 60 ) {
            $hr += 1;
            $min -= 60;
        }
        if ( $hr > 24 ){
            if ( $hr > 24){
                $hr = $hr - 24;
            }
        }
        if($hr >= 12 ){
            $am = $this->filters['pm'];
            if($hr > 12){
                $hr = $hr - 12;
            }
        }
        if ( $min == '00' ) {
            $time = $hr.$am;
        } else {
            $time = $hr.':'.$min.$am;
        }
        return $time;
    }
    
    
    /**
     * generate navigation links for the calendar
     * @param chunk $chunk
     * @param string $param
     * @return string str the processed chunk
     */
    public function navigation($chunk='churchevents_calnavtpl', $params=NULL ){
        /**
         * 1. make the prevous URL
         * 2. make the next url
         * this is for the month view
         */
        $timeDiff = 3600*24*7;// 1 week
        $endTime = 3600*24*6;// 6 days
        switch( $this->filters['view'] ) {
            case 'day':
                $timeDiff = 3600*24;// 1 day
                $endTime = 0;
            case 'week':
                $time = strtotime($this->filters['year'].'-'.$this->filters['month'].'-'.$this->filters['day']);
                $endTime += $time;
                $prev_url .= $this->url.'month='.date('n',$time - $timeDiff).'&amp;day='.date('j',$time - $timeDiff).'&amp;year='.date('Y',$time - $timeDiff).$params;
                $next_url .= $this->url.'month='.date('n',$time + $timeDiff).'&amp;day='.date('j',$time + $timeDiff).'&amp;year='.date('Y',$time + $timeDiff).$params;
                break;
            case 'year':
                $prev_url .= $this->url.'year='.($this->filters['year']-1).$params;
                $next_url .= $this->url.'year='.($this->filters['year']+1).$params;
                break;
            case 'month':
                # previous
                if ( $this->filters['month'] > 1) {
                    $pre_month = $this->filters['month'] - 1;
                    $pre_year = $this->filters['year'];
                } else {
                    $pre_month = 12;
                    $pre_year = $this->filters['year'] - 1;
                }
                
                $prev_url .= $this->url.'month='.$pre_month.'&amp;year='.$pre_year.$params;
                // next
                if ( $this->filters['month'] < 12 ) {
                    $next_month = $this->filters['month'] + 1;
                    $next_year = $this->filters['year'];
                } else {
                    $next_month = 1;
                    $next_year = $this->filters['year'] + 1;
                }
                $next_url .= $this->url.'month='.$next_month.'&amp;year='.$next_year.$params; 
                $time = strtotime($this->filters['year'].'-'.$this->filters['month'].'-01');
                $endTime += date('t', $time) - 3600*24;// last day of the month
        }
        $properties = array(
                'month' => $this->month_array[$this->filters['month']],
                'day' => $this->filters['day'],
                'year' => $this->filters['year'],
                'startDate' => $time,
                'endDate' => $endTime,
                'startDateFormated' => strftime($this->filters['dateFormat'], $time),
                'endDateFormated' => strftime($this->filters['dateFormat'], $endTime),
                'previous' => $this->modx->lexicon('churchevents.previous'),
                'next' => $this->modx->lexicon('churchevents.next'),
                'prev_url' => $prev_url,
                'next_url' => $next_url,
                'view' => $this->filters['view']
            );
        return $this->getChunk($chunk,$properties);
    }
    /**
     * get one event and display the data
     * @return string html
     */
    public function getEvent(){
        $event = $this->modx->getObject('ChurchEvents', array('id' => $this->filters['event_id']));
        if ( is_object($event) ) {
            $properties = $event->toArray();
            // format the time to readable!
            $properties = array_merge($properties,$this->processEvent($event));
            if ( $event->get('event_type') == 'private' && !$this->isAdmin() ) {
                // only show that public time and that the event is reserved
                foreach ( $properties as $k => $v ) {
                    if ( $k == 'public_start' || $k == 'public_end' || $k == 'duration'){
                        continue;
                    }
                    $properties[$k] = '';
                }
                $properties['title'] = $this->modx->lexicon('churchevents.reserved');
            } else {
                // do I need to do anything? yes extended to data 
                $tmp = $this->getExtended($properties['extended']);
                $properties = array_merge($properties,(array)$tmp );
            }
            // get location info
            if ( $this->getFilter('useLocations') ) {
                /** SELECT loc.NAME AS location, loc.notes AS locNotes, loc.*, locT.*  FROM modx_church_event_locations eloc
                JOIN modx_church_locations loc ON loc.id = eloc.church_location_id
                JOIN modx_church_location_type locT ON locT.id = loc.church_location_type_id
                WHERE
                    eloc.church_event_id = 44
                 */
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
                        $location_string .= $this->getChunk($this->getFilter('eventDescriptionLocationTpl'), $location);
                    }
                    if (!empty($location_string) ) {
                        $tmp = $locationTypes[$locTypeName];
                        $tmp['locations'] = $location_string;
                        $locationType_string .= $this->getChunk($this->getFilter('eventDescriptionLocationTypeTpl'), $tmp);
                    }
                }
                $properties['locationInfo'] = $locationType_string;
            } else {
                $properties['locationInfo'] = $this->getChunk($this->getFilter('eventDescriptionBasicLocationTpl'));
            }
            
            
            // edit_url and delete_url
            if ( $this->isAdmin() ) {
                $properties['edit_url'] = $this->url.'view=edit&amp;event_id='.$event->get('id');
                $properties['delete_url'] = $this->url.'view=delete&amp;event_id='.$event->get('id');
                
            }
        } else {
            $text .= $this->modx->lexicon('churchevents.missing');
        }
        
        $this->show_calendar = false;
        return $this->getChunk($this->filters['eventDescriptionTpl'],$properties);
    }
    /**
     * Count events for each day over a giving period
     * @return string html
     */
    public function countEvents($start, $end) {
        $query = $this->modx->newQuery('ChurchEvents');
        $query->select('`ChurchEvents`.`id` AS `ChurchEvents_id`, COUNT(*) AS `day_total`, DATE_FORMAT(`ChurchEvents`.`start_date`, \'%Y-%m-%d\' ) AS group_date, `ChurchEvents`.`start_date`' );// http://rtfm.modx.com/display/xPDO20/xPDOQuery.select
        //$query->select($this->modx->getSelectColumns('ChurchEvents','ChurchEvents','',array('id', 'COUNT(*) AS K', 'ChurchEvents.*')) );
        // now sort by location(s) $this->filters['locations']
        if ( !empty($this->filters['locations']) && $this->filters['useLocations']) {
            $query->innerJoin('ChurchEventLocations','EventLocations');
            $query->where(array( 'EventLocations.church_location_id:IN' => $this->filters['locations']));
        }
        $query->where(array(
            'start_date:>=' => $start,
            'start_date:<=' => $end
        ));
        if ( !empty($this->filters['prominent'])) {
            $query->andCondition(array( 'prominent' => $this->filters['prominent'] ) );
        }
        // this is currently only allow 1 calander view - should make this many
        if ( $this->filters['church_calendar_id'] > 0 ) {
            $query->andCondition(array( 'church_calendar_id' => $this->filters['church_calendar_id'] ) );
        }
        // church_ecategory_id
        if ( $this->filters['church_ecategory_id'] > 0 ) {
            $query->andCondition(array( 'church_ecategory_id' => $this->filters['church_ecategory_id'] ) );
        }
        // 
        if ( !empty($this->filters['filterSearch']) ) {
            $query->andCondition(array( 'title:LIKE' => '%'.$this->filters['filterSearch'].'%' ) );
        }
        if ( !$this->isAdmin() ) { // !$this->modx->user->isAuthenticated('mgr') ) {
            //echo '<br>test status <br>';
            $query->andCondition(array( 'status' => 'approved' ) );
        }
        //$query->sortby('start_date','ASC');
        $query->groupby('group_date');
        $query->prepare();
        //echo 'SQL:<br>'.$query->toSQL().'<br>';
        $this->eventCount = array();
        if ($query->prepare() && $query->stmt->execute()) {
            while ($event = $query->stmt->fetch(PDO::FETCH_OBJ)) {
                //list($date) = explode(' ', $event->start_date);
                $date = str_replace('-0', '-', $event->group_date);
                if (isset($this->eventCount[$date])) {
                    $this->eventCount[$date] += (integer) $event->day_total;
                } else {
                    $this->eventCount[$date] = (integer) $event->day_total;
                }
            }
        }
        /*
        $ev = $this->modx->getIterator('ChurchEvents',$query);
        $this->eventCount = array();
        foreach ($ev as $event ) {
            
            $tmp = $event->toArray();
            list($date) = explode(' ',$event->get('start_date'));
            echo '<br>D: '.$date. ' K: '.$event->get('day_total');
            echo ' Date: '.$event->get('start_date').' id: '.$event->get('id').'<br>';
            print_r($tmp);
            if ( isset($this->eventCount[$date]) ) {
                $this->eventCount[$date] += $event->get('day_total');
            } else {
                $this->eventCount[$date] = $event->get('day_total');
            }
        }
        exit();
        */
    }
    /**
     * get a list of events that will go thourgh the listDayHolderTpl and listEventTpl
     * @return string $html
     */
    public function getList(){
        // get events
        $this->loadEvents();
        $listDayHolderTpl = NULL;
        if( is_array($this->events) ){
            foreach( $this->events as $date => $dayEvents) {
                //echo '<br>Date: '. $date;
                # go thourgh the events
                $listEventTpl = '';
                foreach($dayEvents as $id => $event ) {
                    $listEventTpl .= $this->getChunk($this->filters['listEventTpl'], $event);
                }
                if ( empty($listEventTpl) ) {
                    continue;
                }
                list($y, $m, $d) = explode('-', $date);
                $date = $y.'-'.( strlen($m)==1 ? '0'.$m : $m ).'-'.( strlen($d)==1 ? '0'.$d : $d );
                $timestamp =  strtotime($date);
                $properties = array(
                        'date' => strftime($this->filters['dateFormat'], $timestamp),
                        'timestamp' => $timestamp, 
                        'month' => $m,
                        'year' => $y,
                        'day' => $d,
                        'listEventTpl' => $listEventTpl
                    );
                $listDayHolderTpl .= $this->getChunk($this->filters['listDayHolderTpl'], $properties);
            }
        }
        return $listDayHolderTpl;
    }
    /**
     * Display a single week view
     */
    public function getWeek(){
        // get events
        $this->loadEvents();
        // permission to add?
        $link_view = NULL;
        if ( $this->isAdmin() ) { // $this->modx->user->isAuthenticated('mgr') ) {
            $this->add_link = true;
            $link_view = 'add';
        } else if ( $this->filters['allowRequests'] ) {
            $link_view = 'request';
        }
        
        # Make the table headers
        $calColumnHeadTpl = '';
        if( $this->show_day[7]){ 
            // this is setting sunday first on the list
            $calColumnHeadTpl .= $this->getChunk($this->filters['weekColumnHeadTpl'], array('weekDay' => $this->day_array[7]) );
        }
        for ($count = 1; $count < 7; $count++) {
            if( $this->show_day[$count]){
                $calColumnHeadTpl .= $this->getChunk($this->filters['weekColumnHeadTpl'], array('weekDay' => $this->day_array[$count]) );
            }
        }
        $calColumnTpl = '';
        ## the day columns
        $time = strtotime($this->filters['year'].'-'.$this->filters['month'].'-'.$this->filters['day']);
        for ($t = $time; $t < $time+7*3600*24; $t+=3600*24 ) {
            // show_day is 1 to 7 
            $weekDay = date('N', $t);
            if( $this->show_day[$weekDay]){
                //' CUR: '.(($count + $offset)%7).' - Count: '.$count.' - OFF: '.$offset.
                $tmp_date = date('Y-m-d', $t);
                $add_url = ( !empty($link_view) ? $this->url.'view='.$link_view.'&amp;start_date='.$tmp_date.'&amp;'.$this->params : '' );
                $properties = array(
                        'assets_url' => MODX_ASSETS_URL,
                        'column_class' => '',
                        'month' => $this->month_array[date('n',$t)],
                        'year' => date('Y', $t),
                        'day' => date('j', $t),
                        'day_url' => $this->url.'view=day&amp;month='.date('n',$t).'&amp;day='.date('j',$t).'&amp;year='.date('Y',$t),
                        'allow_add' => $this->add_link,
                        'add_message' => $this->modx->lexicon('addMessage'),
                        'add_url' => $add_url,
                        'add_link' => ( $this->add_link ? '<a class="addEvent" href="'.$add_url.'" title="Add event to '.$tmp_date.'">[ + ]</a>' : '' ),
                        'calDayHolderTpl' => $this->eventList($tmp_date, 'weekEventTpl', 'weekDayHolderTpl')
                    );
                $calColumnTpl .= $this->getChunk($this->filters['weekColumnTpl'], $properties);
            } 
        }
        // only one row
        $calRowTpl = $this->getChunk($this->filters['weekRowTpl'], array('calColumnTpl' => $calColumnTpl ));
        
        // now fill the table
        $properties = array(
                'calColumnHeadTpl' => $calColumnHeadTpl,
                'calRowTpl' => $calRowTpl
                );
        $calTableTpl = $this->getChunk($this->filters['weekTableTpl'], $properties );
        return $calTableTpl;
    }
    /**
     * Get a list of a sinble day's events
     */
    public function getDay(){
        // get events
        $this->loadEvents();
        $DayHolderTpl = NULL;
        $properties = array();
        if( is_array($this->events) ){
            foreach( $this->events as $date => $dayEvents) {
                //echo '<br>Date: '. $date;
                # go thourgh the events
                $listEventTpl = '';
                foreach($dayEvents as $id => $event ) {
                    //echo '<br>ID: '.$id;
                    $listEventTpl .= $this->getChunk($this->filters['dayEventTpl'], $event);
                }
                if ( empty($listEventTpl) ) {
                    continue;
                }
                list($y, $m, $d) = explode('-', $date);
                $date = $y.'-'.( strlen($m)==1 ? '0'.$m : $m ).'-'.( strlen($d)==1 ? '0'.$d : $d );
                $timestamp =  strtotime($date);
                $properties = array(
                        'date' => strftime($this->filters['dateFormat'], $timestamp),
                        'timestamp' => $timestamp, 
                        'month' => $m,
                        'year' => $y,
                        'day' => $d,
                        'listEventTpl' => $listEventTpl
                    );
            }
        }
        $DayHolderTpl = $this->getChunk($this->filters['dayHolderTpl'], $properties);
        return $DayHolderTpl;
    }
    
    /**
     * @returns a Year calendar with all 12 mnths
     */
    public function getYear(){
        // count the events
        $this->countEvents($this->filters['year'].'-01-01', $this->filters['year'].'-12-31');
        $calTableTpl = '';
        for ( $month=1; $month<=12; $month++ ) {
            //print_r($this->events);
            $month_days = date("t", mktime(0, 0, 0, $month, 1, $this->filters['year']));// days in the current month
            $offset = date("N", mktime(0, 0, 0, $month, 1, $this->filters['year']));//
            if( $offset == 7){
                $offset = 0;
            }
            # get the pre filler start date
            if( $month > 1){
                $pre_month = $month - 1;
                $pre_year = $this->filters['year'];
            } else{
                $pre_month = 12;
                $pre_year = $this->filters['year'] - 1;
            }
            $pre_day = date("t", mktime(0, 0, 0, $pre_month, 1, $pre_year)) - $offset + 1;//day is the previous month
            # get the pre filler start date
            if( $month < 12 ){
                $after_month = $month + 1;
                $after_year = $this->filters['year'];
            }else{
                $after_month = 1;
                $after_year = $this->filters['year'] + 1;
            }
            $after_len = 7 - ($month_days + $offset)%7;
            if($after_len == 7){
                $after_len = 0;
            }
            
            # Make the table headers
            $calColumnHeadTpl = '';
            if( $this->show_day[7]){ 
                // this is setting sunday first on the list
                $calColumnHeadTpl .= $this->getChunk($this->filters['yearColumnHeadTpl'], array('weekDay' => $this->day_array[7]) );
            }
            for ($count = 1; $count < 7; $count++) {
                if( $this->show_day[$count]){
                    $calColumnHeadTpl .= $this->getChunk($this->filters['yearColumnHeadTpl'], array('weekDay' => $this->day_array[$count]) );
                }
            }
            $calColumnTpl = '';
            ## pre filler columns
            for ($count = 0; $count < $offset; $count++) {
                $tmp = $count;
                if( $tmp == 0){
                    $tmp = 7;
                }
                if( $this->show_day[$tmp]) {
                    $properties = array(
                            'assets_url' => MODX_ASSETS_URL,
                            'column_class' => 'grey',
                            'month' => $this->month_array[$pre_month],
                            'year' => $pre_year,
                            'day' => $pre_day,
                        );
                    $calColumnTpl .= $this->getChunk($this->filters['yearColumnTpl'], $properties);
                }
                $pre_day++;
            }
            $calRowTpl = '';
            ## the day columns
            for ($count = 1; $count <= $month_days; $count++) {
                $cur_day = ($count + $offset)%7;//0 to 6 - 0 is saturday, sunday, mon, tue, wed, thr, fri
                if( $cur_day == 1) {// Sunday = 1  - start new row
                    
                    $calRowTpl .= $this->getChunk($this->filters['yearRowTpl'], array('calColumnTpl' => $calColumnTpl ));
                    // reset the cal column for the new row(week)
                    $calColumnTpl = '';
                }
                # reassign to day array
                if ($cur_day == 0) {
                    $cur_day = 6;//Saturday
                } else if ($cur_day == 1) {
                    $cur_day = 7;//Sunday
                } else {
                    $cur_day -= 1;//Set to proper day
                }
                if( $this->show_day[$cur_day]){
                    //' CUR: '.(($count + $offset)%7).' - Count: '.$count.' - OFF: '.$offset.
                    $add_url = ( !empty($link_view) ? $this->url.'view='.$link_view.'&amp;start_date='.$this->filters['year'].'-'.$month.'-'.$count.'&amp;'.$this->params : '' );
                    $properties = array(
                            'assets_url' => MODX_ASSETS_URL,
                            'column_class' => ( $this->eventCount[$this->filters['year'].'-'.$month.'-'.$count] > 0 ? 'hasEvents' : ''),// Y,N?,
                            'month' => $this->month_array[$month],
                            'year' => $this->filters['year'],
                            'day' => $count,
                            'day_url' => $this->url.'view=day&amp;month='.$month.'&amp;day='.$count.'&amp;year='.$this->filters['year'],
                            'allow_add' => $this->add_link,
                            'add_message' => $this->modx->lexicon('addMessage'),
                            'add_url' => $add_url,
                            'hasEvents' => ( isset($this->eventCount[$this->filters['year'].'-'.$month.'-'.$count]) ? true : false),// Y,N?
                            'eventClass' => ( isset($this->eventCount[$this->filters['year'].'-'.$month.'-'.$count]) ? 'hasEvents' : 'noEvents'),// Y,N?
                            'eventTotal' => ( isset($this->eventCount[$this->filters['year'].'-'.$month.'-'.$count]) ? $this->eventCount[$this->filters['year'].'-'.$month.'-'.$count] : 0 )
                        );
                    $calColumnTpl .= $this->getChunk($this->filters['yearColumnTpl'], $properties);
                }
            }
            if( $cur_day == 7 ){
                $cur_day = 0;//set to 0 since the loop advances before use!
            }
            ## after filler columns
            for ($count = 1; $count <= $after_len; $count++) {
                $cur_day++;
                if( $this->show_day[$cur_day]){ // keeping from above
                    $tmp_date =  $after_year.'-'.$after_month.'-'.$count;//YYYY-MM-DD
                    $add_url = ( !empty($link_view) ? $this->url.'view='.$link_view.'&amp;start_date='.$after_year.'-'.$after_month.'-'.$count.'&amp;'.$this->params : '' );
                    $properties = array(
                            'assets_url' => MODX_ASSETS_URL,
                            'column_class' => 'grey',
                            'month' => $after_month,
                            'year' => $after_year,
                            'day' => $count
                        );
                    $calColumnTpl .= $this->getChunk($this->filters['yearColumnTpl'], $properties);
                }
            }
            // the last row
            $calRowTpl .= $this->getChunk($this->filters['yearRowTpl'], array('calColumnTpl' => $calColumnTpl ));
            
            // now fill the table
            $properties = array(
                    'month' => $this->month_array[$month],
                    'month_url' => $this->url.'view=month&amp;month='.$month,
                    'year' => $this->filters['year'],
                    'calColumnHeadTpl' => $calColumnHeadTpl,
                    'calRowTpl' => $calRowTpl
                    );
            $calTableTpl .= $this->getChunk($this->filters['yearTableTpl'], $properties );
        }
        return $calTableTpl;
    }
    /**
     * Get all info about a given location and return the process chunk
     * all locationType is prefixed with type_
     * 
     * @param integer location_id
     * @return string chunk($this->filters['locationDescriptionTpl'])
     */
    public function getLocation($loc_id){
        // get location info
        if ( $this->filters['useLocations'] ) {
            
            $location = $this->modx->getObject('ChurchLocations', array('id' => $loc_id, 'published'=>'Yes'));
            if ( !is_object($location)) {
                return 'Location not Found ('.$loc_id.') ';
            }
            $properties = $location->toArray();
            $type = $location->getOne('LocationType');
            $tmp = $type->toArray();
            foreach ( $tmp as $name => $value ) {
                $properties['type_'.$name] = $value;
            }
            return $this->getChunk($this->filters['locationDescriptionTpl'], $properties);
        }
        return 'Location not Found';
    }
    
    /**
     * 
     */
    public function getRss(){
        // get events
        $this->loadEvents();
        $rssItemTpl = NULL;
        if( is_array($this->events) ){
            foreach( $this->events as $date => $dayEvents) {
                //echo '<br>Date: '. $date;
                list($y, $m, $d) = explode('-', $date);
                $date = $y.'-'.( strlen($m)==1 ? '0'.$m : $m ).'-'.( strlen($d)==1 ? '0'.$d : $d );
                $timestamp =  strtotime($date);
                $properties = array(
                        'date' => strftime($this->filters['dateFormat'], $timestamp),
                        'timestamp' => $timestamp, 
                        'month' => $m,
                        'year' => $y,
                        'day' => $d,
                        'listEventTpl' => $listEventTpl
                    );
                # go thourgh the events
                foreach($dayEvents as $id => $event ) {
                    $rssItemTpl .=  $this->getChunk($this->filters['rssItemTpl'], $event);
                }
            }
        }
        
        return $this->getChunk($this->filters['rssTpl'], array('rssItems'=> $rssItemTpl) );
    }
    /**
     * 
     */
    public function getCsv(){
        
    }
    /**
     * Radius: http://www.mavetju.org/programming/php.php
     */
    public function getiCal() {
        // http://sprain.ch/blog/downloads/php-class-easypeasyics-create-ical-files-with-php/
        // http://www.julian-young.com/2009/07/07/php-ical-email/ & http://psoug.org/snippet/Send-iCal-Email-Meeting-Requests-using-PHP_934.htm
        // http://code.google.com/p/flaimo-php/source/browse/trunk/iCalendar_v2/?r=48
        // http://www.phpkode.com/scripts/item/ical-maker/
        require_once $this->getConfig('icalPath').'iCalcreator.class.php';
        $config = array( "unique_id" => $this->modx->getOption('site_name') ); // set Your unique id 
        $v = new vcalendar( $config ); // create a new calendar instance 
        $v->setProperty( "method", "PUBLISH" ); // required of some calendar software 
        $v->setProperty( "x-wr-calname", $this->modx->resource->get('title') ); // required of some calendar software 
        $v->setProperty( "X-WR-CALDESC", $this->modx->resource->get('title') ); // required of some calendar software 
        $tz = date_default_timezone_get(); 
        $v->setProperty( "X-WR-TIMEZONE", $tz ); // required of some calendar software 
        //.. . 
        // iCalUtilityFunctions::createTimezone( $v, $tz ) // creates (very simple) timezone component(-s) .. .
        
        // get events
        $this->loadEvents();
        foreach ($ev as $e_id => $event ) {
            // $event->toArray();
            list($date) = explode(' ',$event->get('start_date'));
            $this->events[$date][$event->get('id')] = $this->processEvent($event);
        }
        foreach( $this->events as $date => $dayEvents) {
            list($y, $m, $d) = explode('-', $date);
            $date = $y.'-'.( strlen($m)==1 ? '0'.$m : $m ).'-'.( strlen($d)==1 ? '0'.$d : $d );
            $timestamp =  strtotime($date);
            foreach($dayEvents as $id => $event ) {
                
                $vevent = & $v->newComponent( "vevent" ); // create an event calendar component
                switch ($event['event_timed']) {
                    case 'Y':
                        $vevent->setProperty( "dtstart", array( "year"=>$y, "month"=>$m, "day"=>$d, 
                                "hour"=>$event['start_hour'], "min"=>$event['start_minute'], "sec"=>0 )); 
                        $vevent->setProperty( "dtend", array( "year"=>$y, "month"=>$m, "day"=>$d, 
                                "hour"=>$event['end_hour'], "min"=>$event['end_minute'], "sec"=>0 ));
                        break;
                    case 'allday':
                    case 'N':
                    default:
                        $vevent->setProperty( "dtstart", $y.$m.$d, array("VALUE" => "DATE"));// alt. date format, now for an all-day event
                        break;
                }
                //$vevent->setProperty( "LOCATION", "Central Placa" ); // property name - case independent 
                $vevent->setProperty( "summary", $event['event_title'] ); 
                $vevent->setProperty( "description", strip_tags($event['public_desc']) ); 
                //$vevent->setProperty( "comment", "This is a comment" ); 
                //$vevent->setProperty( "attendee", "attendee1@icaldomain.net" );
                $vevent->setProperty( "organizer" , $event['contact_email'] );
                $vevent->setProperty( "contact", $event['contact'].' tel '.$event['contact_phone'] );
                $vevent->setProperty( "sequence", $event['version'] );// version number
                
            } 
            //$vevent->setProperty( "resources", "COMPUTER PROJECTOR" ); 
            //$vevent->setProperty( "rrule", array( "FREQ" => "WEEKLY", "count" => 4));// weekly, four occasions 
            //$vevent->parse( "LOCATION:1CP Conference Room 4350" ); // supporting parse of strict rfc5545 formatted text .. . .. .
            // all calendar components are described in rfc5545 .. .
            // a complete iCalcreator function list (ex. setProperty) in iCalcreator manual .. .
            //$v->setComponent( $vevent );
        }
        $v->returnCalendar(); // redirect calendar file to browser
        // now kill modx
        
    }
    /**
     * This will process the event details to make it ready to send to HTML
     * @param $event this is an instance of a ChurchEvents object
     * @return array() $properties
     */
    public function processEvent($event){
        list($date) = explode(' ',$event->get('start_date'));
        $time_str = '';
        $end_time = $start_time = $setup_time = NULL;
        $hr = $min = $dhr = $dmin;
        switch ( $event->get('event_timed')  ){
            case 'Y':
                list($d, $time) = explode(' ',$event->get('public_start'));
                list($hr,$min) = explode(":", $time);
                $hr = (int)$hr;
                $min = (int)$min;
                list($dhr,$dmin) = explode(":", $event->get('duration'));
                $dhr = (int)$dhr;
                $dmin = (int)$dmin;
                $start_time = $this->formatTime($hr, $min);
                $end_time = $this->formatTime(($hr+$dhr), ($min+$dmin));
                if ( $event->get('start_date') != $event->get('public_start') ) {
                    list($d, $time) = explode(' ',$event->get('start_date'));
                    list($hr,$min) = explode(":", $time);
                    $hr = (int)$hr;
                    $min = (int)$min;
                    $setup_time = $this->formatTime($hr, $min);
                }
                $time_str = '<span class="eTime">'.$start_time.' &ndash; '.$end_time.'</span> ';
                break;
            case 'allday':
                $start_time = $this->modx->lexicon('churchevents.allday_text');
                $time_str = '<span class="allDay">'.$this->modx->lexicon('churchevents.allday').'</span>';
                break;
            case 'N':
                $start_time = $this->modx->lexicon('churchevents.notice_text');
            default:
                break;
        }
        // build the event info - this is also the URL:
        $notice = '';
        if ( $event->get('status') != 'approved' ) {
            $notice = '<img src="'.MODX_ASSETS_URL.'components/churchevents/images/'.$event->get('status').'.png" title="This event is marked as '.$event->get('status').'" /> ';
        }
        $event_title = $event->get('title');
        if ( $event->get('event_type') == 'private' && !$this->isAdmin() ) {
            $event_title = $this->modx->lexicon('churchevents.reserved');
            
        }
        $locData = array();
        if ( $this->filters['useLocations'] ) {
            // get locations
            $locations = $event->getMany('EventLocations');
            //print_r($locations);
            $locationInfo = $locationInfoUrls = '';
            foreach( $locations as $location ) {
                //print_r($location);
                //$Tmp = $location->toArray();
                $loc = $location->getOne('Location');
                //$Tmp = $loc->toArray();
                //print_r($Tmp);
                //echo ' - L: '.$location->get('name');
                if ( !empty($locationInfo) ) {
                    $locationInfo .= ', ';
                    $locationInfoUrls .= ', ';
                }
                $locationInfo .= $loc->get('name');
                $locationInfoUrls .= '<a href="'.$this->url.'view=location&amp;location='.$loc->get('id').'">'.$loc->get('name').'</a>';
            }
        } else {
            $locationInfo = $event->get('location_name');
            $locationInfoUrls = '';
            // location info for basic location
            $locData = array(
                'location_name' => $event->get('location_name'), 
                'address' => $event->get('address'), 
                'city' => $event->get('city'), 
                'state' => $event->get('state'), 
                'zip' => $event->get('zip'), 
                'country' => $event->get('country')
            );
        }
        // $event->toArray();
        $properties = array(
            'event' => $notice.$time_str.
                '<a href="'.$this->url.'view=event&amp;event_id='.$event->get('id').'">'.$event_title.'</a>',
            'class' => 'chCat_'.$event->get('church_ecategory_id'),// the CSS class(es) 
            'calendar_id' => $event->get('church_calendar_id'),// the CSS class(es)
            'calendar' => $this->calendar_array[$event->get('church_calendar_id')],
            'category_id' => $event->get('church_ecategory_id'),
            'category' => $this->category_array[$event->get('church_ecategory_id')],
            'event_id' => $event->get('id'),
            'event_date' =>  strftime($this->filters['dateFormat'], strtotime($event->get('start_date'))),
            'timestamp' => strtotime($event->get('public_start')),
            'event_title' => $event_title,
            'setup_time' => $setup_time,
            'start_time' => $start_time,
            'status' => $event->get('status'),
            'end_time' => $end_time,
            'event_time' => $time_str,
            'event_url' => $this->url.'view=event&amp;event_id='.$event->get('id'),//&amp;a='.$a.'
            'notice' => $notice,
            'locationInfo' => $locationInfo,
            'locationInfoUrls' => $locationInfoUrls,
            
            'version' => $event->get('version'),
            'public_desc' => $event->get('public_desc'),
            'public_start' => $event->get('public_start'),
            'duration' => $event->get('duration'),
            'end_date' => $event->get('end_date'),
            'contact' => $event->get('contact'),
            'contact_email' => $event->get('contact_email'),
            'contact_phone' => $event->get('contact_phone'),
            'start_hour' => $hr,
            'start_minute' => $min,
            'end_hour' => $hr+$dhr,
            'end_minute' => $min+$dmin,
            'event_timed' => $event->get('event_timed')
        );
        // extended fields
        $tmp = $this->getExtended($event->get('extended'));
        $properties = array_merge($tmp, $properties, $locData);
        
        return $properties;
    }
    /**
     * Utility function put exceptions string data into an array
     * @param string $exceptions
     * @return array() $array
     */
    public function getExceptions($dates){
        $data = explode(',',$dates);
        $array = array();
        if ( !empty($data) ) {
            foreach( $data as $date ) {
                $array[] = date('Y-m-d', strtotime($date));
            }
            return $array;
        }
        return array();
    }
    /**
     * Utility function take an array and make the exceptions string
     * @param array $dates
     * @return string $exceptions
     */
    public function makeExceptions($dates){
        $expections = NULL;
        foreach( $dates as $date ) {
            if ( !empty( $expections)) {
                $expections .= ',';
            }
            $expections .= $date;//date('Y-m-d', strtotime($date));
        }
        return $expections;
    }
    /**
     * Utility function put extend string data into an array
     * @param string $extended
     * @return array() $array
     */
    public function getExtended($extend){
        $data = json_decode($extend);
        if ( !empty($data) ) {
            return (array)$data;
        }
        return array();
    }
    /**
     * Utility function take an array and make the extend string
     * @param array $data
     * @return string $extended
     */
    public function makeExtended($data){
        $extend_array = array();
        foreach( $data as $name => $value ) {
            if (strpos($name, 'extend_') === false ) {
                continue; // do nothing
            } else {
                $extend_array[$name] = $value;
            }
        }
        return json_encode($extend_array);
    }
    /**
     * Utility function to check for conflicts to the current event locations
     * @param $event this is an instance of a ChurchEvents object
     * @param array() $eventLocations a numeric array of the location ids
     * @return mixed on fallure it will return chunk with the conflicting events 
     *     on success it will return void
     */
    public function checkLocationConflicts(&$event, $eventLocations){
        if ( empty($eventLocations) ) {
            return NULL;
        }
        $start = $event->get('start_date');
        $end = $event->get('end_date');
        $query = $this->modx->newQuery('ChurchEvents');
        //$this->modx->getSelectColumns('ChurchEvents','ChurchEvents','',array('id','name'));
        //$query->select($this->modx->getSelectColumns('ChurchEvents','ChurchEvents','',array('DISTINCT `ChurchEvents`.`id` AS `ChurchEvents_id`', 'ChurchEvents.*')));// http://rtfm.modx.com/display/xPDO20/xPDOQuery.select
        //$query->select('DISTINCT `ChurchEvents`.`id` AS `ChurchEvents_id`,'.$this->modx->getSelectColumns('ChurchEvents','ChurchEvents') );// http://rtfm.modx.com/display/xPDO20/xPDOQuery.select
        $query->innerJoin('ChurchEventLocations','EventLocations');
        $exclude = array();
        $exclude[] = $event->get('id');
        $parent_id = $event->get('parent_id'); 
        if ( !empty($parent_id) ) {
            $exclude[] = $parent_id;
        }
        $query->where(array(
                array(
                    // all day
                    'ChurchEvents.event_type' => 'allday',
                        
                    array(    // overlaping, ex: event is 2-4 but look for events that are 1-5
                        'OR:ChurchEvents.start_date:<=' => $start,
                        'AND:ChurchEvents.end_date:>=' => $end,
                    ),
                    array (// end time is between start and end
                        'OR:ChurchEvents.start_date:>=' => $end,
                        'AND:ChurchEvents.end_date:<=' => $end,
                    ),
                    array(    // start time is between start and end
                        'OR:ChurchEvents.start_date:>=' => $start,
                        'AND:ChurchEvents.end_date:<=' => $start,
                    ),
                ),
            // status = approved?
            'EventLocations.church_location_id:IN' => $eventLocations,
            array(
                'ChurchEvents.id:!=' => $event->get('id'),
                'AND:ChurchEvents.parent_id:NOT IN' => $exclude
            )
            
                
        ));
        $query->prepare();
        //echo 'SQL:<br>'.$query->toSQL().'<br>';
        $conflicts = $this->modx->getIterator('ChurchEvents',$query);
        $html = NULL;
        foreach($conflicts as $conflict){
            $array = $conflict->toArray();
            //print_r($array);
            //echo '<br>';
            //exit();
            $html .= $this->getChunk($this->filters['eventFormConflictTpl'], $this->processEvent($conflict));
        }
        //exit();
        return $html;
    }
    /**
     * Utility function to make all locations for an event
     * @param $event this is an instance of a ChurchEvents object
     * @param array() $eventLocations a numeric array of the location ids
     * @return void
     */
    public function makeLocations($event, $eventLocations){
        // get existing ones
        $locations = $event->getMany('EventLocations');
        $existing = array();
        foreach($locations as $location ) {
            if ( in_array($location->get('church_location_id'), $eventLocations ) ){
                $existing[] = $location->get('church_location_id');
            } else {
                $location->remove();
            }
        }
        $locations = array();
        foreach( $eventLocations as $l_id) {
           if ( in_array($l_id, $existing) ) {
               continue;
           }
           $loc = $this->modx->newObject('ChurchEventLocations');
           $loc->set('church_location_id', $l_id);
           $loc->set('church_event_id', $event->get('id'));
           //$loc->set('approved', 'Yes');
           $locations[] = $loc;
        }
        $event->addMany($locations);
        return;
    }
    /**
     * Utility function, make the select options
     * @param array $options (name=>value)
     * @param mixed $current this is the current selected value
     * @param string $first this the first option or a filler like Select Please.. default is All
     * @return string $html
     */
    public function selectOptions($options, $current, $first='All'){
        $html = '';
        if ( !empty($first) ) {
            $html .= '
            <option value="0">'.$first.'</option>';
        }
        foreach ( $options as $name => $value ) {
            if ( $value == $current) {
                $html .= '
                <option value="'.$value.'" selected="selected">'.$name.'</option>';
            } else {
                $html .= '
                <option value="'.$value.'">'.$name.'</option>';
            }
        }
        return $html;
    }
    /**
     * Utility function load Lexicon values
     * 
     */
    public function lexiconToPlaceholders(){
        $lexicons = array(
            /**
             * Manager
             * /
            'churchevents', 'churchevents.desc', 'churchevents.description',
            // calendars
            'churchevents.calendar_tab', 'churchevents.calendar', 'churchevents.calendar_desc', 'churchevents.calendar_title',
            'churchevents.calendar_description', 'churchevents.calendar_request_notify', 'churchevents.calendar_create',
            'churchevents.calendar_update', 'churchevents.calendar_remove', 'churchevents.calendar_remove_confirm',
            //cal erros
            'churchevents.calendar_err_nf', 'churchevents.calendar_err_ae', 'churchevents.calendar_err_ns', 'churchevents.calendar_err_ns_name',
            'churchevents.calendar_err_remove', 'churchevents.calendar_err_save',
            // categories
            'churchevents.category_tab', 'churchevents.category','churchevents.category_desc','churchevents.category_name',
            'churchevents.category_background','churchevents.category_color', 'churchevents.category_create','churchevents.category_update',
            'churchevents.category_remove','churchevents.category_remove_confirm',
            //cal erros
            'churchevents.category_err_nf','churchevents.category_err_ae','churchevents.category_err_ns','churchevents.category_err_ns_name',
            'churchevents.category_err_remove','churchevents.category_err_save',
            // location
            'churchevents.location_tab','churchevents.location','churchevents.location_desc',
            //'churchevents.location_status',
            'churchevents.location_basic_info','churchevents.location_address_info','churchevents.location_name','churchevents.location_church_location_type_id',
            'churchevents.location_check_conflict','churchevents.location_floor','churchevents.location_address','churchevents.location_address2',
            'churchevents.location_city','churchevents.location_state','churchevents.location_zip','churchevents.location_phone',
            'churchevents.location_toll_free','churchevents.location_fax','churchevents.location_website','churchevents.location_contact_name',
            'churchevents.location_contact_phone','churchevents.location_contact_email','churchevents.location_notes','churchevents.location_published',
            'churchevents.location_map_url','churchevents.location_owner','churchevents.location_owner_group',
            //'churchevents.location_'] = '';
            'churchevents.location_create','churchevents.location_update','churchevents.location_remove','churchevents.location_remove_confirm',
            //cal erros
            'churchevents.location_err_nf','churchevents.location_err_ae','churchevents.location_err_ns','churchevents.location_err_ns_name',
            'churchevents.location_err_remove','churchevents.location_err_save',
            // location type
            'churchevents.location_type_tab','churchevents.location_type','churchevents.location_type_desc','churchevents.location_type_name',
            'churchevents.location_type_notes','churchevents.location_type_public','churchevents.location_type_owner',
            'churchevents.location_type_create','churchevents.location_type_update','churchevents.location_type_remove','churchevents.location_type_remove_confirm',
            //cal erros
            'churchevents.location_type_err_nf','churchevents.location_type_err_ae','churchevents.location_type_err_ns','churchevents.location_type_err_ns_name',
            'churchevents.location_type_err_remove','churchevents.location_type_err_save',
            */
            /**
             * web
             */
            // description
            'churchevents.descEdit_link','churchevents.descDelete_link', 'churchevents.descBack',
            'churchevents.descDate_heading','churchevents.descNextDate_heading','churchevents.descDescription_heading',
            'churchevents.descContact_heading','churchevents.descLocation_heading','churchevents.descSetupNotes_heading',
            'churchevents.descOfficeNotes_heading','churchevents.descSetupTime_heading',
            // filters
            'churchevents.previous','churchevents.next','churchevents.filterLocations_label',
            // Days
            'churchevents.sunday','churchevents.monday','churchevents.tuesday','churchevents.wednesday','churchevents.thursday',
            'churchevents.friday','churchevents.saturday',
            // Months
            'churchevents.january','churchevents.february','churchevents.march','churchevents.april','churchevents.may','churchevents.june',
            'churchevents.july','churchevents.august','churchevents.september','churchevents.october','churchevents.november','churchevents.december',
            'churchevents.reserved','churchevents.allday','churchevents.addMessage','churchevents.missing',
            // error messages
            'churchevents.conflictMessage',
            // web form labels
            'churchevents.admin_heading','churchevents.status_label','churchevents.statusApproved_option','churchevents.statusPending_option',
            'churchevents.statusSubmitted_option','churchevents.statusRejected_option','churchevents.statusDeleted_option',
            'churchevents.eventType_heading','churchevents.eventTypePublic_label','churchevents.eventTypePrivate_label',
            'churchevents.officeNotes_label','churchevents.repeat_heading','churchevents.editRepeat_heading','churchevents.editRepeatAll_label',
            'churchevents.editRepeatSingle_label',
            'churchevents.event_heading','churchevents.title_label','churchevents.publicDesc_label',
            'churchevents.notes_label','churchevents.contact_label','churchevents.contactEmail_label','churchevents.contactPhone_label',
            'churchevents.calendar_label','churchevents.category_label','churchevents.prominent_label',
            //'churchevents._label'] = '';
            'churchevents.dateTime_heading','churchevents.repeatType_heading','churchevents.repeatTypeNo_label','churchevents.repeatTypeDaily_label',
            'churchevents.repeatTypeWeekly_label','churchevents.repeatTypeMonthly_label','churchevents.interval_label',
            'churchevents.repeatTypeDaily_option','churchevents.repeatTypeDailyOther_option', 'churchevents.repeatTypeDaily3_option', 'churchevents.repeatTypeDaily4_option',
            'churchevents.repeatTypeDaily5_option','churchevents.repeatTypeDaily6_option','churchevents.repeatTypeDaily7_option',
            'churchevents.repeatTypeWeekly_option','churchevents.repeatTypeWeeklyOther_option','churchevents.repeatTypeWeekly3_option',
            'churchevents.repeatTypeWeekly4_option','churchevents.repeatTypeWeekly5_option','churchevents.repeatTypeWeekly6_option',
            'churchevents.repeatTypeWeekly7_option','churchevents.repeatTypeMonthly_option','churchevents.repeatTypeMonthlyOther_option',
            'churchevents.repeatTypeMonthly3_option','churchevents.repeatTypeMonthly4_option','churchevents.repeatTypeMonthly5_option',
            'churchevents.repeatTypeMonthly6_option','churchevents.repeatTypeMonthly7_option',
            
            'churchevents.whichDays_heading','churchevents.whichMonthDays_heading','churchevents.firstWeek_heading','churchevents.secondWeek_heading',
            'churchevents.thirdWeek_heading','churchevents.forthWeek_heading','churchevents.fifthWeek_heading','churchevents.publicStart_label',
            'churchevents.publicEnd_label','churchevents.eventTimed_heading','churchevents.eventTimedYes_label','churchevents.publicTime_label',
            'churchevents.duration_label','churchevents.setupTime_label','churchevents.eventTimedAllday_label',
            'churchevents.eventTimedNote_label','churchevents.exceptions_label',
            // locations
            'churchevents.location_heading',
            
            'churchevents.submit_button','churchevents.cancel_button',
            // delete form
            'churchevents.delete_heading','churchevents.deleteRepeat_heading', 'churchevents.deleteRepeat_heading',
            'churchevents.deleteRepeatAll_label','churchevents.deleteRepeatSingle_label','churchevents.delete_button'
        );
        $properties = array();
        foreach( $lexicons as $key){
            $properties[str_replace('churchevents.', '', $key)] = $this->modx->lexicon($key);
            
        }
        $this->modx->toPlaceholders($properties);
    }
    /**
     * Utility function, will take a time and return the select options for them
     * @param string $time like 09:30:00
     * @param int $time_type - 12 (9:00pm) or 24 (21:00)  
     * @return array $time_array - has hour_select=>options, minute_select=>options, hour=>value, minute=>value and am=>value
     */
    public function makeTime($time, $time_type = 12){
        if( !empty($time) ){
            list($hr,$min) = explode(":", $time);
            $hr = (int) trim($hr);
            $am = 'am';
            if($hr >= 12 && $time_type == 12){
                $am = 'pm';
                if($hr > 12){
                    $hr = $hr - 12;
                }
            }
        } else {
            $hr = '-';//date("g");
            $min = '--';//date("i");
            $am =  '';//date("a");
        }
        if ( $time_type == 24 ) {
            $start = 0;
            $stop = 23;
        } else {
            $start = 1;
            $stop = 12;
        }
        $hr_array = array();
        for ($a = $start; $a <= $stop; $a++){
            $hr_array[$a] = $a; 
        }
        $min_array = array();
        for ($a = 0; $a < 60; $a+=5){
            $tmp = $a;
            if($a < 10 ){
                $tmp = "0".$a;
            }
            $min_array[$tmp] = $a; 
        }
        
        $time_array = array(
                'hour_select' => $this->selectOptions($hr_array, $hr, '-'),
                'minute_select' => $this->selectOptions($min_array, $min, '--'),
                'hour' => $hr,
                'minute' => $min,
                'am' => $am
            );
        return $time_array;
    }
    /**
     * Utility function this will return the time in a MySQL format or Unix from hr, minutes, am/pm
     * @param (Int) $hour
     * @param (Int) $minute
     * @param (Int) $ampm
     * @param (String) $format = MySQL, UNIX
     * @return (mixed) $result - date(hh:mm:ss) or timestamp
     * 
     */
    public function formatTimeInput($hour, $minute, $ampm, $format='MySQL') {
        $result = '';
        $tmpHr = 0;
        // am
        if ( $ampm=='am'  ) {
            // || $ampm=='pm' && $hour == 12
            $tmpHr = $hour;
            if ( $hour == 12 ) {
                // 0
                $tmpHr = 0;
            }
        } else {
            $tmpHr = $hour + 12;
            if ( $hour == 12 ) {
                // 0
                $tmpHr = 12;
            }
        }
        if ( $format == 'MySQL' ) {
            $result = $tmpHr.':'.$minute.':00';
        } else {
            $result = 3600*$tmpHr + 60*$minute;
        }
        return $result;
    }
    /**
     * Utility function, will return a checked="checked" for radio and checkboxes
     * @param mixed $input
     * @param mixed $value
     * @return string $output
     */
    public function isChecked($input, $option){
        $output = ' ';
        if ($input == $option) {
            $output = ' checked="checked"';
        }
        return $output;
    }
    /**
     * Utility function, will return the JavaScript dateformat from a php date format (case senstive)
     * @param (String) $php_format ( %m/%d/%Y)
     * @return string $output
     */
    public function dateformatToJS($php_format){
        $output = ' ';
        $php = array(
            // days
            '%e', '%d','%a','%A',// ns - %j, %u, %w
            '%m','%m','%b','%B',
            '%y', '%Y',
            '%D', '%s');
        $js = array(
            // days
            'd', 'dd', 'D', 'DD',
            'mm', 'mm', 'M', 'MM',
            'y', 'yy',
            'mm/dd/y', '@'
            );// http://docs.jquery.com/UI/Datepicker/%24.datepicker.formatDate
        $output = str_replace($php,$js, $php_format);
        return $output;
    }
} 