<?php
/**
 * @description myControllerRequest - no need to edit this file
 * 
 */
require_once MODX_CORE_PATH . 'model/modx/modrequest.class.php';
/**
 * Encapsulates the interaction of MODx manager with an HTTP request.
 *
 * {@inheritdoc}
 *
 * @extends modRequest
 */
class myControllerRequest extends modRequest {
    public $cmpController = null;
    public $actionVar = 'action';
    public $defaultAction = 'home';

    // function __construct(cmpController &$cmpController) {
    function __construct(myController &$cmpController) {
        parent :: __construct($cmpController->modx);
        $this->cmpController =& $cmpController; 
    }
    
    /**
     * Extends modRequest::handleRequest and loads the proper error handler and
     * actionVar value.
     *
     * {@inheritdoc}
     */
    public function handleRequest() {
        $this->loadErrorHandler();

        /* save page to manager object. allow custom actionVar choice for extending classes. */
        $this->action = isset($_REQUEST[$this->actionVar]) ? $_REQUEST[$this->actionVar] : $this->defaultAction;

        $modx =& $this->modx;
        $cmpController =& $this->cmpController;
        $viewHeader = include $this->cmpController->config['corePath'].'controllers/mgr/header.php';

        $f = $this->cmpController->config['corePath'].'controllers/mgr/'.$this->action.'.php';
        if (file_exists($f)) {
            $viewOutput = include $f;
        } else {
            $viewOutput = 'Controller not found: '.$f;
        }

        return $viewHeader.$viewOutput;
    }
    /**
     * Load Calendar
     * 
     */
    public function loadCalendar(){
        // views
            // grid - month, week and day
            // list
            // export - csv, ical
        $className = 'Calendar';
        $classPath = $this->cmpController->config['modelPath'].$this->cmpController->config['packageName'].'/calendar/';
        if ($this->modx->loadClass($className,$classPath,true,true)) {
            //$grid = new $className($this);//,$this->config);
            $calendar = new Calendar($this->modx, $this);//,$this->config);
            
        } else {
            $this->modx->log(modX::LOG_LEVEL_ERROR,'['.$this->cmpController->config['packageName'].'] Could not load '.$className.' class.');
            $calendar = NULL;
        }
        return $calendar;
    }
}