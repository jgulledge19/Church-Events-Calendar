<?php
/**
 * @package cmp
 * @subpackage processors
 */

/* get obj */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('churchevents.category_err_ns'));
$category = $modx->getObject('ChurchEcategory',$scriptProperties['id']);
if (empty($category)) return $modx->error->failure($modx->lexicon('churchevents.category_err_nf'));

/* remove */
if ($category->remove() == false) {
    return $modx->error->failure($modx->lexicon('churchevents.category_err_remove'));
}


return $modx->error->success('',$category);