<?php
/**
 * Snippet loads a list of events as RSS feed 
 * 
 * Use like: 
 * <?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/elements/1.1/">
<channel>
    <title>[[*pagetitle]]</title>
    <link>[[~[[*id]]? &scheme=`full`]]</link>
    <description>[[*introtext:cdata]]</description>
    <language>[[++cultureKey]]</language>
    <ttl>120</ttl>
    <atom:link href="[[~[[*id]]? &scheme=`full`]]" rel="self" type="application/rss+xml" />
[[!ChurchEventsRSS?]]

</channel>
</rss>
 * 
 */
$scriptProperties['view'] = 'rss';

$namespace = $modx->getObject('modNamespace','churchevents');  

$package_name = 'churchevents';
$config = array( 
        'packageName' => $package_name,
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