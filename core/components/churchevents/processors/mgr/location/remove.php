<?php
/**
 * @package cmp
 * @subpackage processors
 */

/* get obj */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('churchevents.location_err_ns'));
$location = $modx->getObject('ChurchLocations',$scriptProperties['id']);
if (empty($location)) return $modx->error->failure($modx->lexicon('churchevents.location_err_nf'));

/* remove */
if ($location->remove() == false) {
    return $modx->error->failure($modx->lexicon('churchevents.location_err_remove'));
}

return $modx->error->success('',$location);