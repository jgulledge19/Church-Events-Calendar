<?php
/**
 * @package CMP
 * @subpackage processors
 * This file needs to be customized
 */

if (empty($scriptProperties['name'])) {
    $modx->error->addField('name',$modx->lexicon('churchevents.category_err_ns_name'));
} else {
    $alreadyExists = $modx->getObject('ChurchEcategory',
        array(
            'name' => $scriptProperties['name']
            )
        );
    if ($alreadyExists) {
        $modx->error->addField('name',$modx->lexicon('churchevents.category_err_ae'));
    }
}


if ($modx->error->hasError()) {
    return $modx->error->failure();
}

$category = $modx->newObject('ChurchEcategory');
$category->fromArray($scriptProperties);

/* save */
if ($category->save() == false) {
    return $modx->error->failure($modx->lexicon('churchevents.category_err_save'));
}

return $modx->error->success('',$category);