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
$showSelect = (boolean) $modx->getOption('showSelect',$scriptProperties,0);

/* build query */
$c = $modx->newQuery('ChurchLocationType');

if (!empty($query)) {
    $c->where(array(
        'name:LIKE' => '%'.$query.'%'
    ));
}
$count = $modx->getCount('ChurchLocationType',$c);

$c->sortby($sort,$dir);
if ($isLimit) {
    $c->limit($limit,$start);
}

$locationTypes = $modx->getIterator('ChurchLocationType', $c);


/* iterate */
$list = array();
if ( !empty($showSelect) && $showSelect ){
    $list[] = array('id'=> 0, 'name' => 'Show all');
    $count++;
}
foreach ($locationTypes as $locationType) {
    $array = $locationType->toArray();
    // make the date readable
    // $array['post_date'] = date('n/j/y g:ia',$feed_array['post_date']);
    $array['public'] = ($array['public'] == 'Yes' ? 1 : 0 );
    $list[] = $array; 
}
return $this->outputArray($list,$count);