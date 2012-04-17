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
if (empty($_DATA['id'])) return $modx->error->failure($modx->lexicon('churchevents.location_type_err_ns'));
$locationType = $modx->getObject('ChurchLocationType',$_DATA['id']);
if (empty($locationType)) return $modx->error->failure($modx->lexicon('churchevents.location_type_err_nf'));

/* set fields */
$locationType->fromArray($_DATA);

if ( $_DATA['public'] || $_DATA['public'] == $modx->lexicon('yes') ) {
    $locationType->set('public', 'Yes');
} else {
    $locationType->set('public', 'No');
}

/* save */
if ($locationType->save() == false) {
    return $modx->error->failure($modx->lexicon('churchevents.location_type_err_save'));
}

return $modx->error->success('',$locationType);