<?php
/*
require_once $modx->getOption('formit.core_path',null,$modx->getOption('core_path',null,MODX_CORE_PATH).'components/formit/').'model/formit/formit.class.php';
$fi = new FormIt($modx,$scriptProperties);
$fi->initialize($modx->context->get('key'));
$fi->loadRequest();

$fields = $fi->request->prepare();
return $fi->request->handle($fields);
*/


$package_name = 'churchevents';
$config = array( 
        'packageName' => $package_name
    );
// load the controller class - mycontroller.class.php
define('CMP_MODEL_DIR', $modx->getOption('core_path',null,MODX_CORE_PATH).'components/');//dirname(dirname(__FILE__)).'/model/' );
require_once CMP_MODEL_DIR.$package_name.'/model/'.$package_name.'/mycontroller.class.php';

$cmpController = new myController($modx, $config );
$cmpController->initialize($modx->context->get('key'));
$request = $cmpController->loadRequest();
$calendar = $request->loadCalendar();

$output = $calendar->process($scriptProperties);
$calendar->loadHead();

return $output;