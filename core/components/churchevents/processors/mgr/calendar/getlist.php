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
$sort = $modx->getOption('sort',$scriptProperties,'id');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');
$query = $modx->getOption('query',$scriptProperties,'');

/* build query */
$c = $modx->newQuery('ChurchCalendar');

if (!empty($query)) {
    $c->where(array(
        'title:LIKE' => '%'.$query.'%',
        'OR:description:LIKE' => '%'.$query.'%',
    ));
}

$count = $modx->getCount('ChurchCalendar',$c);

$c->sortby($sort,$dir);
if ($isLimit) {
    $c->limit($limit,$start);
}

$calendars = $modx->getIterator('ChurchCalendar', $c);


/* iterate */
$list = array();
foreach ($calendars as $calendar) {
    $array = $calendar->toArray();
    // make the date readable
    // $array['post_date'] = date('n/j/y g:ia',$feed_array['post_date']);
    $list[] = $array; 
}
return $this->outputArray($list,$count);