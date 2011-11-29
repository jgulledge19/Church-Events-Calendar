<?php

/**
 * the class will just extend the FormIt class(package) so I can set the path for the Hooks class
 */
class eventFormIt extends FormIt{
    
    
    /**
     * A reference to the Calendar instance
     * @var Calendar $Calendar
     */
    public $Calendar;
    /**
     * Load an instance of the Calendar
     * @param Calendar $Calendar
     */
    public function loadCalendar(Calendar $Calendar){
        $this->Calendar = &$Calendar;
    }
    /**
     * Loads the Hooks class.
     *
     * @access public
     * @param $type string The type of hook to load.
     * @param $config array An array of configuration parameters for the
     * hooks class
     * @return fiHooks An instance of the fiHooks class.
     */
    public function loadHooks($type = 'post',$config = array()) {
        /*if (!$this->modx->loadClass('formit.fiHooks',$this->config['modelPath'],true,true)) {
            $this->modx->log(modX::LOG_LEVEL_ERROR,'[FormIt] Could not load Hooks class.');
            return false;
        }*/
        $type = $type.'Hooks';
        $this->$type = new eventHooks($this,$config);
        $this->$type->loadCalendar($this->Calendar);
        return $this->$type;
    }
}