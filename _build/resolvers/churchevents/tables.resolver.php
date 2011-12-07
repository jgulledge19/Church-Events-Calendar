<?php

/**
 * MyComponent resolver script - runs on install.
 *
 * Copyright 2011 Your Name <you@yourdomain.com>
 * @author Your Name <you@yourdomain.com>
 * 1/1/11
 *
 * MyComponent is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * MyComponent is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * MyComponent; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package mycomponent
 */
/**
 * Description: Resolver script for MyComponent package
 * @package mycomponent
 * @subpackage build
 */

/* Example Resolver script */

/* The $modx object is not available here. In its place we
 * use $object->xpdo
 */

if ($object->xpdo) {
    $modx =& $object->xpdo;
    // add package
    $s_path = $modx->getOption('core_path').'components/churchevents/model/';
    $modx->addPackage('churchevents', $s_path);
    
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            
            //$modx->log(xPDO::LOG_LEVEL_INFO,'Package Path: '.$s_path);
            $m = $modx->getManager();

            $manager = $modx->getManager();
            $modx->setLogLevel(modX::LOG_LEVEL_ERROR);
            $m->createObjectContainer('ChurchCalendar');
            $m->createObjectContainer('ChurchEcategory');
            $m->createObjectContainer('ChurchEventLocations');
            $m->createObjectContainer('ChurchEvents');
            $m->createObjectContainer('ChurchLocationPermissions');
            $m->createObjectContainer('ChurchLocationType');
            $m->createObjectContainer('ChurchLocations');
            $modx->setLogLevel(modX::LOG_LEVEL_INFO);
            break;
    }
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
            // create default data for calendar, ecategory, locationtype and locations:
            $calendar = $modx->newObject('ChurchCalendar');
            $calendar->fromArray(array('title' => 'Events', 
                'description' => 'This calendar displays all events that the community is welcome to attend.',
                'request_notify' => 'email@email.com' 
                ));
            if ($calendar->save() == false) {
                $modx->log(xPDO::LOG_LEVEL_ERROR,'ERROR adding default data to table: churchcalendar ');
            } else {
                $modx->log(xPDO::LOG_LEVEL_INFO,'Added default data to table: churchcalendar ');
            }
            $calendar = $modx->newObject('ChurchCalendar');
            $calendar->fromArray(array('title' => 'Services', 
                'description' => 'This calendar contains all of the service times and information',
                'request_notify' => 'email@email.com' 
                ));
            if ($calendar->save() == false) {
                // LOG_LEVEL_ERROR
                $modx->log(xPDO::LOG_LEVEL_ERROR,'ERROR adding default data to table: churchcalendar ');
            } else {
                $modx->log(xPDO::LOG_LEVEL_INFO,'Added default data to table: churchcalendar ');
            }
            $calendar = $modx->newObject('ChurchCalendar');
            $calendar->fromArray(array('title' => 'Meetings & Rehearsal', 
                'description' => 'Current meetings and rehearsals.',
                'request_notify' => 'email@email.com' 
                ));
            if ($calendar->save() == false) {
                // LOG_LEVEL_ERROR
                $modx->log(xPDO::LOG_LEVEL_ERROR,'ERROR adding default data to table: churchcalendar ');
            } else {
                $modx->log(xPDO::LOG_LEVEL_INFO,'Added default data to table: churchcalendar ');
            }
            // create default data for ecategory:
            $category = $modx->newObject('ChurchEcategory');
            $category->fromArray(array('name' => 'Adults', 
                'background' => '84BD98',
                'color' => '000000' 
                ));
            if ($category->save() == false) {
                // LOG_LEVEL_ERROR
                $modx->log(xPDO::LOG_LEVEL_ERROR,'ERROR adding default data to table: churchecategory ');
            } else {
                $modx->log(xPDO::LOG_LEVEL_INFO,'Added default data to table: churchecategory ');
            }
            $category = $modx->newObject('ChurchEcategory');
            $category->fromArray(array('name' => 'General', 
                'background' => 'FFFFFF',
                'color' => '000000' 
                ));
            if ($category->save() == false) {
                // LOG_LEVEL_ERROR
                $modx->log(xPDO::LOG_LEVEL_ERROR,'ERROR adding default data to table: churchecategory ');
            } else {
                $modx->log(xPDO::LOG_LEVEL_INFO,'Added default data to table: churchecategory ');
            }
            
            $category = $modx->newObject('ChurchEcategory');
            $category->fromArray(array('name' => 'Youth', 
                'background' => '50E75A',
                'color' => '000000' 
                ));
            if ($category->save() == false) {
                // LOG_LEVEL_ERROR
                $modx->log(xPDO::LOG_LEVEL_ERROR,'ERROR adding default data to table: churchecategory ');
            } else {
                $modx->log(xPDO::LOG_LEVEL_INFO,'Added default data to table: churchecategory ');
            }
            $category = $modx->newObject('ChurchEcategory');
            $category->fromArray(array('name' => 'Children', 
                'background' => 'E79750',
                'color' => '000000' 
                ));
            if ($category->save() == false) {
                // LOG_LEVEL_ERROR
                $modx->log(xPDO::LOG_LEVEL_ERROR,'ERROR adding default data to table: churchecategory ');
            } else {
                $modx->log(xPDO::LOG_LEVEL_INFO,'Added default data to table: churchecategory ');
            }
            // create default data for locationtype:
            $locationType = $modx->newObject('ChurchLocationType');
            $locationType->fromArray(array('name' => 'Our Church Building', 
                'notes' => 'This can be a building, type or just a way to group locations.',
                'public' => 'Yes' 
                ));
            if ($locationType->save() == false) {
                // LOG_LEVEL_ERROR
                $modx->log(xPDO::LOG_LEVEL_ERROR,'ERROR adding default data to table: churchlocationtype ');
            } else {
                $modx->log(xPDO::LOG_LEVEL_INFO,'Added default data to table: churchlocationtype ');
            }
            // create default data for locations:
            $location = $modx->newObject('ChurchLocations');
            $location->fromArray(array('name' => 'Sanctuary', 
                'floor' => 'Lower Level',
                'address' => '100 Street',
                'notes' => 'This can be a room or it could be a place.',
                ));
            if ($location->save() == false) {
                // LOG_LEVEL_ERROR
                $modx->log(xPDO::LOG_LEVEL_ERROR,'ERROR adding default data to table: churchlocation ');
            } else {
                $modx->log(xPDO::LOG_LEVEL_INFO,'Added default data to table: churchlocation ');
            }
            
            break;
        case xPDOTransport::ACTION_UPGRADE:
            
            $modx->exec("ALTER TABLE {$modx->getTableName('ChurchEvents')} 
                DROP COLUMN `locations`,
                ADD COLUMN `prominent` SET('Yes','No') DEFAULT 'No' NULL AFTER `web_user_id`,
                ADD COLUMN `personal_subscribers` TEXT NULL AFTER `contact_phone`,
                ADD COLUMN `extended` TEXT NULL AFTER `country`,
                CHANGE `public_desc` `public_desc` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
                CHANGE `notes` `notes` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
                CHANGE `office_notes` `office_notes` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
                ADD COLUMN `exceptions` TEXT NULL AFTER `days`
                ");
            break;
    }
}
$modx->log(xPDO::LOG_LEVEL_INFO,'Tables resolver actions completed');

return true;
