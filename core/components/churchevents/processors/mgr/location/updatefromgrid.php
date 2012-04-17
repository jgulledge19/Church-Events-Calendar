<?php
/**
 * @package doodle
 * @subpackage processors
 */
/* parse JSON */
if (empty($scriptProperties['data'])) return $modx->error->failure('Invalid data.');
$_DATA = $modx->fromJSON($scriptProperties['data']);
if (!is_array($_DATA)) return $modx->error->failure('Invalid data.');

/* get obj */
if (empty($_DATA['id'])) return $modx->error->failure($modx->lexicon('churchevents.location_err_ns'));
$location = $modx->getObject('ChurchLocations',$_DATA['id']);
if (empty($location)) return $modx->error->failure($modx->lexicon('churchevents.location_err_nf'));

/* set fields */
$location->fromArray($_DATA);

if ( $_DATA['published'] || $_DATA['published'] == $modx->lexicon('yes') ) {
    $location->set('published', 'Yes');
} else {
    $location->set('published', 'No');
}
if ( $_DATA['check_conflict'] || $_DATA['check_conflict'] == $modx->lexicon('yes') ) {
    $location->set('check_conflict', 'Yes');
} else {
    $location->set('check_conflict', 'No');
}
/* save */
if ($location->save() == false) {
    return $modx->error->failure($modx->lexicon('churchevents.location_err_save'));
}

return $modx->error->success('',$location);