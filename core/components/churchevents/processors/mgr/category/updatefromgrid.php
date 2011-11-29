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
if (empty($_DATA['id'])) return $modx->error->failure($modx->lexicon('churchevents.category_err_ns'));
$category = $modx->getObject('ChurchEcategory',$_DATA['id']);
if (empty($category)) return $modx->error->failure($modx->lexicon('churchevents.category_err_nf'));

/* set fields */
unset($_DATA['post_date']);
$category->fromArray($_DATA);

/* save */
if ($category->save() == false) {
    return $modx->error->failure($modx->lexicon('churchevents.category_err_save'));
}


return $modx->error->success('',$category);