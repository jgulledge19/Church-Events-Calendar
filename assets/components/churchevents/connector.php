<?php
/**
 * CMP Connector
 *
 * @package doodles
 */
// set the correct package name
$package_name = 'churchevents';

// No need to edit below here
require_once dirname(dirname(dirname(dirname(__FILE__)))).'/config.core.php';
require_once MODX_CORE_PATH.'config/'.MODX_CONFIG_KEY.'.inc.php';
require_once MODX_CONNECTORS_PATH.'index.php';

// load the controller class - mycontroller.class.php
$corePath = $modx->getOption($package_name.'.core_path',null,$modx->getOption('core_path').'components/'.$package_name.'/');
require_once $corePath.'model/'.$package_name.'/mycontroller.class.php';
$config = array( 
        'packageName' => $package_name
    );
$modx->cmpController = new myController($modx, $config );
// load package and lexicon
$modx->cmpController->initialize($modx->context->get('key'));
//$modx->lexicon->load($package_name.':default');

// handle the Ajax request
$path = $modx->getOption('processorsPath',$modx->cmpController->config,$corePath.'processors/');
$modx->request->handleRequest(array(
    'processors_path' => $path,
    'location' => '',
));