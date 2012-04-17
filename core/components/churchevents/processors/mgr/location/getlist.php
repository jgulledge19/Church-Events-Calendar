<?php
/**
 * Get a list
 * 
 * @package cmp
 * @subpackage processors
 * This file needs to be customized
 */
/* setup default properties */
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,20);
$sort = $modx->getOption('sort',$scriptProperties,'name');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');
$query = $modx->getOption('query',$scriptProperties,'');
$type_id  = $modx->getOption('church_location_type_id',$scriptProperties, 0);

/* build query */
$c = $modx->newQuery('ChurchLocations');

if (!empty($query)) {
    $c->where(array(
        'name:LIKE' => '%'.$query.'%'
    ));
}
if (!empty($type_id)) {
    $c->where(array(
        'church_location_type_id' => $type_id
    ));
}
$count = $modx->getCount('ChurchLocations',$c);

$c->sortby($sort,$dir);
if ($isLimit) {
    $c->limit($limit,$start);
}

$locations = $modx->getIterator('ChurchLocations', $c);


/* iterate */
$list = array();
foreach ($locations as $location) {
    $array = $location->toArray();
    // make the date readable
    // $array['post_date'] = date('n/j/y g:ia',$feed_array['post_date']);
    $array['check_conflict'] = ($array['check_conflict'] == 'Yes' ? 1 : 0 );
    $array['published'] = ($array['published'] == 'Yes' ? 1 : 0 );
    $list[] = $array; 
}
return $this->outputArray($list,$count);