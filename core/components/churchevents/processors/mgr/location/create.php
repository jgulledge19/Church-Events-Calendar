<?php
/**
 * @package CMP
 * @subpackage processors
 * This file needs to be customized
 */

if (empty($scriptProperties['name'])) {
    $modx->error->addField('name',$modx->lexicon('churchevents.location_err_ns_name'));
} else {
    $alreadyExists = $modx->getObject('ChurchLocations',
        array(
            'name' => $scriptProperties['name'],
            'church_location_type_id' => $scriptProperties['church_location_type_id']
            )
        );
    if ($alreadyExists) {
        $modx->error->addField('name',$modx->lexicon('churchevents.location_err_ae'));
    }
}


if ($modx->error->hasError()) {
    return $modx->error->failure();
}

$category = $modx->newObject('ChurchLocations');
$category->fromArray($scriptProperties);

/* save */
if ($category->save() == false) {
    return $modx->error->failure($modx->lexicon('churchevents.location_err_save'));
}

return $modx->error->success('',$category);