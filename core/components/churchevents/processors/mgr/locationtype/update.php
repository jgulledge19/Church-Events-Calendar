<?php
/**
 * @package doodle
 * @subpackage processors
 */

/* get obj */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('churchevents.location_type_err_ns'));
$locationType = $modx->getObject('ChurchLocationType',$scriptProperties['id']);
if (empty($locationType)) return $modx->error->failure($modx->lexicon('churchevents.location_type_err_nf'));

/* set fields */
//unset($scriptProperties['post_date']);
$locationType->fromArray($scriptProperties);

/* save */
if ($locationType->save() == false) {
    return $modx->error->failure($modx->lexicon('churchevents.location_type_err_save'));
}

return $modx->error->success('',$locationType);