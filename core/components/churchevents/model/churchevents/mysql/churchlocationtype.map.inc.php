<?php
$xpdo_meta_map['ChurchLocationType']= array (
  'package' => 'churchevents',
  'version' => '1.1',
  'table' => 'church_location_type',
  'aggregates' => 
  array (
    'Locations' => 
    array (
      'class' => 'ChurchLocations',
      'local' => 'id',
      'foreign' => 'church_location_type_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
  'fields' => 
  array (
    'name' => NULL,
    'notes' => NULL,
    'owner' => NULL,
    'public' => 'Yes',
  ),
  'fieldMeta' => 
  array (
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => true,
    ),
    'notes' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
    'owner' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => true,
    ),
    'public' => 
    array (
      'dbtype' => 'set',
      'precision' => '\'Yes\',\'No\'',
      'phptype' => 'string',
      'null' => true,
      'default' => 'Yes',
    ),
  ),
);
