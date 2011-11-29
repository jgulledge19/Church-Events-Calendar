<?php

/**
 * ChurchEvents
 *
 * Copyright 2010 by Joshua Gulledge <jgulledge19@hotmail.com>
 *
 * This file is part of ChurchEvents, a simple archive navigation system for MODx
 * Revolution.
 *
 * ChurchEvents is under the terms of the GNU General Public License
 *
 * ChurchEvents is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * @package churchevents
 */

/**
 * the class will add, edit and delete events by extending the FormIt class(package)
 * @param modX $modx
 * @param Calendar $Calendar
 */
class ManageEvent{
    /**
     * A reference to the Calendar instance
     * @var Calendar $Calendar
     */
    public $Calendar;
    /**
     * A reference to the modX instance
     * @var modX $modx
     */
    public $modx;
    /**
     * A reference to the FormIt instance
     * @var eventFormIt $eventFormIt
     */
    public $eventFormIt; 
    
    function __construct(modX $modx, $Calendar){
        $this->modx = &$modx;
        $this->Calendar = $Calendar;
        // get the formit class
        require_once $this->modx->getOption('formit.core_path',null,$this->modx->getOption('core_path',null,MODX_CORE_PATH).'components/formit/').'model/formit/formit.class.php';
        // get the hook class
        require_once $this->modx->getOption('formit.core_path',null,$this->modx->getOption('core_path',null,MODX_CORE_PATH).'components/formit/').'model/formit/fihooks.class.php';
        
        require_once 'eventformit.class.php';
        require_once 'eventhooks.class.php';
        
    }
    /**
     * Start the eventFormIt class and it will do any pre and post processing
     *
     * @access public
     * @param array $scriptProperties
     * @return mixed
     */
    public function processForm($scriptProperties) {
        // $scriptProperties needs all of the options as the array and then I can run it.
        $this->eventFormIt = new eventFormIt($this->modx,$scriptProperties);
        $this->eventFormIt->initialize($this->modx->context->get('key'));
        $this->eventFormIt->loadCalendar($this->Calendar);
        $this->eventFormIt->loadRequest();
        
        $fields = $this->eventFormIt->request->prepare();
        return $this->eventFormIt->request->handle($fields);
        
    }
    /**
     * 
     */
}
