<?php
/**
 * @package CMP
 * @subpackage processors
 * This file needs to be customized
 */

if (empty($scriptProperties['name'])) {
    $modx->error->addField('name',$modx->lexicon('churchevents.location_type_err_ns_name'));
} else {
    $alreadyExists = $modx->getObject('ChurchLocationType',
        array(
            'name' => $scriptProperties['name']
            )
        );
    if ($alreadyExists) {
        $modx->error->addField('name',$modx->lexicon('churchevents.location_type_err_ae'));
    }
}


if ($modx->error->hasError()) {
    return $modx->error->failure();
}

$locationType = $modx->newObject('ChurchLocationType');
$locationType->fromArray($scriptProperties);

/* save */
if ($locationType->save() == false) {
    return $modx->error->failure($modx->lexicon('churchevents.location_type_err_save'));
}

return $modx->error->success('',$locationType);