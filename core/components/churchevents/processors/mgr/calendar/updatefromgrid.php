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
if (empty($_DATA['id'])) return $modx->error->failure($modx->lexicon('churchevents.calendar_err_ns'));
$calendar = $modx->getObject('ChurchCalendar',$_DATA['id']);
if (empty($calendar)) return $modx->error->failure($modx->lexicon('churchevents.calendar_err_nf'));

/* set fields */
unset($_DATA['post_date']);
$calendar->fromArray($_DATA);

/* save */
if ($calendar->save() == false) {
    return $modx->error->failure($modx->lexicon('churchevents.calendar_err_save'));
}


return $modx->error->success('',$calendar);