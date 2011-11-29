<?php
/**
 * @package CMP
 * @subpackage processors
 * This file needs to be customized
 */

if (empty($scriptProperties['title'])) {
    $modx->error->addField('title',$modx->lexicon('churchevents.calendar_err_ns_name'));
} else {
    $alreadyExists = $modx->getObject('ChurchCalendar',
        array(
            'title' => $scriptProperties['title']
            )
        );
    if ($alreadyExists) {
        $modx->error->addField('title',$modx->lexicon('churchevents.calendar_err_ae'));
    }
}


if ($modx->error->hasError()) {
    return $modx->error->failure();
}

$calendar = $modx->newObject('ChurchCalendar');
$calendar->fromArray($scriptProperties);

/* save */
if ($calendar->save() == false) {
    return $modx->error->failure($modx->lexicon('churchevents.calendar_err_save'));
}

return $modx->error->success('',$calendar);