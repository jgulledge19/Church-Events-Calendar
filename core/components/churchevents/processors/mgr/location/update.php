<?php
/**
 * @package doodle
 * @subpackage processors
 */

/* get obj */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('churchevents.location_err_ns'));
$location = $modx->getObject('ChurchLocations',$scriptProperties['id']);
if (empty($location)) return $modx->error->failure($modx->lexicon('churchevents.location_err_nf'));

/* set fields */
//unset($scriptProperties['post_date']);
$location->fromArray($scriptProperties);
//print_r($scriptProperties);
if ( $scriptProperties['published'] || $scriptProperties['published'] == $modx->lexicon('yes') ) {
    $location->set('published', 'Yes');
} else {
    $location->set('published', 'No');
}
if ( $scriptProperties['check_conflict'] || $scriptProperties['check_conflict'] == $modx->lexicon('yes') ) {
    $location->set('check_conflict', 'Yes');
} else {
    $location->set('check_conflict', 'No');
}

/* save */
if ($location->save() == false) {
    return $modx->error->failure($modx->lexicon('churchevents.location_err_save'));
}

return $modx->error->success('',$location);