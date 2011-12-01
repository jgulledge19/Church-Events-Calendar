<?php
$xpdo_meta_map['ChurchEventLocations']= array (
  'package' => 'churchevents',
  'version' => '1.1',
  'table' => 'church_event_locations',
  'aggregates' => 
  array (
    'Event' => 
    array (
      'class' => 'ChurchEvnents',
      'local' => 'church_event_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'Location' => 
    array (
      'class' => 'ChurchLocations',
      'local' => 'church_location_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
  'fields' => 
  array (
    'church_event_id' => NULL,
    'church_location_id' => NULL,
    'approved' => 'Yes',
  ),
  'fieldMeta' => 
  array (
    'church_event_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
      'index' => 'index',
    ),
    'church_location_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
    ),
    'approved' => 
    array (
      'dbtype' => 'set',
      'precision' => '\'Yes\',\'No\'',
      'phptype' => 'string',
      'null' => true,
      'default' => 'Yes',
    ),
  ),
  'indexes' => 
  array (
    'church_event_id' => 
    array (
      'alias' => 'church_event_id',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'church_event_id' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
        'church_location_id' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
        'approved' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => true,
        ),
      ),
    ),
  ),
);
