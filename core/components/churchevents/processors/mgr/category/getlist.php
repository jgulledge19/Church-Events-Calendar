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
$c = $modx->newQuery('ChurchEcategory');

if (!empty($query)) {
    $c->where(array(
        'name:LIKE' => '%'.$query.'%'
    ));
}

$count = $modx->getCount('ChurchEcategory',$c);

$c->sortby($sort,$dir);
if ($isLimit) {
    $c->limit($limit,$start);
}

$categories = $modx->getIterator('ChurchEcategory', $c);


/* iterate */
$list = array();
foreach ($categories as $category) {
    $array = $category->toArray();
    // make the date readable
    // $array['post_date'] = date('n/j/y g:ia',$feed_array['post_date']);
    $list[] = $array; 
}
return $this->outputArray($list,$count);