<?php
/**
 * @package doodle
 * @subpackage processors
 */

/* get obj */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('churchevents.category_err_ns'));
$category = $modx->getObject('ChurchEcategory',$scriptProperties['id']);
if (empty($category)) return $modx->error->failure($modx->lexicon('churchevents.category_err_nf'));

/* set fields */
//unset($scriptProperties['post_date']);
$category->fromArray($scriptProperties);

/* save */
if ($category->save() == false) {
    return $modx->error->failure($modx->lexicon('churchevents.category_err_save'));
}


return $modx->error->success('',$category);