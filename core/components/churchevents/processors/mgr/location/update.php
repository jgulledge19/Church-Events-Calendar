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
/* save */
if ($location->save() == false) {
    return $modx->error->failure($modx->lexicon('churchevents.location_err_save'));
}

return $modx->error->success('',$location);