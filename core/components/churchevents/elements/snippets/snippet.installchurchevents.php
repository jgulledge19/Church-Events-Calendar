<?php
/**
 * This snippet simply installs the db stuff
 */
$package_name = 'churchevents';
// add package
$s_path = $modx->getOption('core_path').'components/'.$package_name.'/model/';
$modx->addPackage($package_name, $s_path);

$m = $modx->getManager();

// the class table object name(s)
$m->createObjectContainer('ChurchCalendar');
$m->createObjectContainer('ChurchEcategory');
$m->createObjectContainer('ChurchEventLocations');
$m->createObjectContainer('ChurchEvents');
$m->createObjectContainer('ChurchLocationPermissions');
$m->createObjectContainer('ChurchLocationType');
$m->createObjectContainer('ChurchLocations');

return 'Tables created.';