<?php
/**
 * @package cmp
 * @subpackage processors
 */

/* get obj */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('churchevents.location_type_err_ns'));
$locationType = $modx->getObject('ChurchLocationType',$scriptProperties['id']);
if (empty($locationType)) return $modx->error->failure($modx->lexicon('churchevents.location_type_err_nf'));

/* remove */
if ($locationType->remove() == false) {
    return $modx->error->failure($modx->lexicon('churchevents.location_type_err_remove'));
}

return $modx->error->success('',$locationType);