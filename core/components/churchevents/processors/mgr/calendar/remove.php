<?php
/**
 * @package cmp
 * @subpackage processors
 */

/* get obj */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('churchevents.calendar_err_ns'));
$calendar = $modx->getObject('ChurchCalendar',$scriptProperties['id']);
if (empty($calendar)) return $modx->error->failure($modx->lexicon('churchevents.calendar_err_nf'));

/* remove */
if ($calendar->remove() == false) {
    return $modx->error->failure($modx->lexicon('churchevents.calendar_err_remove'));
}


return $modx->error->success('',$calendar);