<?php
/**
* Adds modActions and modMenus into package
*
* @package mycomponent
* @subpackage build
*/
$action= $modx->newObject('modAction');
$action->fromArray(array(
    'id' => 1,
    'namespace' => 'churchevents',
    'parent' => 0,
    'controller' => 'controllers/index',
    'haslayout' => true,
    'lang_topics' => 'churchevents:default',
    'assets' => '',
),'',true,true);

/* load action into menu */
$menu= $modx->newObject('modMenu');
$menu->fromArray(array(
    'text' => 'churchevents',
    'parent' => 'components',
    'description' => 'churchevents.desc',
    'icon' => '',
    'menuindex' => 0,
    'params' => '',
    'handler' => '',
),'',true,true);
$menu->addOne($action);

return $menu;