<?php
$xpdo_meta_map['ChurchLocationPermissions']= array (
  'package' => 'churchevents',
  'version' => '1.1',
  'table' => 'church_location_permissions',
  'composites' => 
  array (
    'Location' => 
    array (
      'class' => 'ChurchLocations',
      'local' => 'church_location_id',
      'foreign' => 'id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
  'fields' => 
  array (
    'membergroup_names_id' => 0,
    'user_id' => 0,
    'church_location_id' => NULL,
    'access' => 0,
  ),
  'fieldMeta' => 
  array (
    'membergroup_names_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => true,
      'default' => 0,
    ),
    'user_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => true,
      'default' => 0,
    ),
    'church_location_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => true,
    ),
    'access' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => true,
      'default' => 0,
    ),
  ),
);
