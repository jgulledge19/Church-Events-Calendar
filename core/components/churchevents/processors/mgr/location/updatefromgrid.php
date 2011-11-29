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

/* save */
if ($location->save() == false) {
    return $modx->error->failure($modx->lexicon('churchevents.location_err_save'));
}

return $modx->error->success('',$location);