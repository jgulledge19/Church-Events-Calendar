<?php
/**
 * @package doodle
 * @subpackage processors
 */

/* get obj */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('churchevents.calendar_err_ns'));
$calendar = $modx->getObject('ChurchCalendar',$scriptProperties['id']);
if (empty($calendar)) return $modx->error->failure($modx->lexicon('churchevents.calendar_err_nf'));

/* set fields */
//unset($scriptProperties['post_date']);
$calendar->fromArray($scriptProperties);

/* save */
if ($calendar->save() == false) {
    return $modx->error->failure($modx->lexicon('churchevents.calendar_err_save'));
}


return $modx->error->success('',$calendar);