<?php
/**
 * Loads the home page.
 *
 * @package doodles
 * @subpackage controllers
 * 
 * You will need to edit this file
 */

$modx->regClientStartupScript($cmpController->config['jsUrl'].'mgr/widgets/'.$cmpController->config['packageName'].'.grid.js');
//$modx->regClientStartupScript($cmpController->config['jsUrl'].'mgr/widgets/categories.grid.js');
//$modx->regClientStartupScript($cmpController->config['jsUrl'].'mgr/widgets/locations.grid.js');
$modx->regClientStartupScript($cmpController->config['jsUrl'].'mgr/widgets/home.panel.js');
$modx->regClientStartupScript($cmpController->config['jsUrl'].'mgr/sections/index.js');
/**
 * Color Picker
 *  <script type="text/javascript" src="js/colorpicker/colorpickerfield.js"></script>
    <script type="text/javascript" src="js/colorpicker/colorpicker.js"></script>
 *  <link rel="stylesheet" href="/assets//components/colorpicker/css/colorpicker.css" />
 */
if ( file_exists(MODX_ASSETS_PATH.'components/colorpicker/js/colorpickerfield.js') ) {
$modx->regClientCSS(MODX_ASSETS_URL.'components/colorpicker/css/colorpicker.css');
$modx->regClientStartupHTMLBlock(
'<style type="text/css">
.ext-strict .x-form-field-trigger-wrap .noShadow{
    text-shadow: none;
}
</style>');
$modx->regClientStartupScript(MODX_ASSETS_URL.'components/colorpicker/js/colorpickerfield.js');
$modx->regClientStartupScript(MODX_ASSETS_URL.'components/colorpicker/js/colorpicker.js');
$modx->regClientStartupScript($cmpController->config['jsUrl'].'mgr/widgets/color.js');
}
$output = '<div id="'./*$cmpController->config['packageName'].*/'cmp-panel-home-div"></div>';

return $output;

/* ORG example 
$modx->regClientStartupScript($doodles->config['jsUrl'].'mgr/widgets/doodles.grid.js');
$modx->regClientStartupScript($doodles->config['jsUrl'].'mgr/widgets/home.panel.js');
$modx->regClientStartupScript($doodles->config['jsUrl'].'mgr/sections/index.js');

$output = '<div id="doodles-panel-home-div"></div>';

return $output;
*/