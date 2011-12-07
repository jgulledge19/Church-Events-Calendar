<?php
/** Array of system settings for Mycomponent package
 * @package mycomponent
 * @subpackage build
 */


/* This section is ONLY for new System Settings to be added to
 * The System Settings grid. If you include existing settings,
 * they will be removed on uninstall. Existing setting can be
 * set in a script resolver (see install.script.php).
 */
$settings = array();

/* The first three are new settings */
$settings['churchevents.allowRequests']= $modx->newObject('modSystemSetting');
$settings['churchevents.allowRequests']->fromArray(array (
    'key' => 'churchevents.allowRequests',
    'value' => '1',
    'xtype' => 'combo-boolean',
    'namespace' => 'churchevents',
    'area' => 'ChurchEvents',
), '', true, true);

$settings['churchevents.dateFormat']= $modx->newObject('modSystemSetting');
$settings['churchevents.dateFormat']->fromArray(array (
    'key' => 'churchevents.dateFormat',
    'value' => '%m/%d/%Y',
    'xtype' => 'textfield',
    'namespace' => 'churchevents',
    'area' => 'Date & Time',
), '', true, true);

$settings['churchevents.useLocations']= $modx->newObject('modSystemSetting');
$settings['churchevents.useLocations']->fromArray(array (
    'key' => 'churchevents.useLocations',
    'value' => '1',
    'xtype' => 'combo-boolean',
    'namespace' => 'churchevents',
    'area' => 'Location Management',
), '', true, true);

$settings['churchevents.extended']= $modx->newObject('modSystemSetting');
$settings['churchevents.extended']->fromArray(array (
    'key' => 'churchevents.extended',
    'value' => '',
    'xtype' => 'textarea',
    'namespace' => 'churchevents',
    'area' => 'ChurchEvents',
), '', true, true);

$settings['churchevents.pageID']= $modx->newObject('modSystemSetting');
$settings['churchevents.pageID']->fromArray(array (
    'key' => 'churchevents.pageID',
    'value' => '',
    'xtype' => 'textfield',
    'namespace' => 'churchevents',
    'area' => 'ChurchEvents',
), '', true, true);

return $settings;