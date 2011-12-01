<?php
$xpdo_meta_map['ChurchLocations']= array (
  'package' => 'churchevents',
  'version' => '1.1',
  'table' => 'church_locations',
  'aggregates' => 
  array (
    'EventLocations' => 
    array (
      'class' => 'ChurchEventLocations',
      'local' => 'id',
      'foreign' => 'church_location_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'LocationPermissions' => 
    array (
      'class' => 'ChurchLocationPermissions',
      'local' => 'id',
      'foreign' => 'church_location_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
  'composites' => 
  array (
    'LocationType' => 
    array (
      'class' => 'ChurchLocationType',
      'local' => 'church_location_type_id',
      'foreign' => 'id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
  'fields' => 
  array (
    'church_location_type_id' => NULL,
    'check_conflict' => 'Yes',
    'floor' => NULL,
    'name' => '',
    'address' => '',
    'address2' => '',
    'city' => '',
    'state' => 'IN',
    'zip' => '',
    'phone' => '',
    'toll_free' => NULL,
    'fax' => NULL,
    'website' => NULL,
    'contact_name' => NULL,
    'contact_phone' => NULL,
    'contact_email' => NULL,
    'notes' => NULL,
    'published' => 'Yes',
    'map_url' => NULL,
    'owner' => NULL,
    'owner_group' => NULL,
  ),
  'fieldMeta' => 
  array (
    'church_location_type_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => true,
    ),
    'check_conflict' => 
    array (
      'dbtype' => 'set',
      'precision' => '\'Yes\',\'No\'',
      'phptype' => 'string',
      'null' => false,
      'default' => 'Yes',
    ),
    'floor' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '32',
      'phptype' => 'string',
      'null' => true,
    ),
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '128',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'index',
    ),
    'address' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '128',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'address2' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '128',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
    ),
    'city' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '128',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'state' => 
    array (
      'dbtype' => 'char',
      'precision' => '2',
      'phptype' => 'string',
      'null' => false,
      'default' => 'IN',
    ),
    'zip' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '32',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'phone' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '64',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'toll_free' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '64',
      'phptype' => 'string',
      'null' => true,
    ),
    'fax' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '64',
      'phptype' => 'string',
      'null' => true,
    ),
    'website' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '128',
      'phptype' => 'string',
      'null' => true,
    ),
    'contact_name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '128',
      'phptype' => 'string',
      'null' => true,
    ),
    'contact_phone' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '64',
      'phptype' => 'string',
      'null' => true,
    ),
    'contact_email' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '128',
      'phptype' => 'string',
      'null' => true,
    ),
    'notes' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
    ),
    'published' => 
    array (
      'dbtype' => 'set',
      'precision' => '\'Yes\',\'No\'',
      'phptype' => 'string',
      'null' => true,
      'default' => 'Yes',
    ),
    'map_url' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
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
    'owner_group' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '60',
      'phptype' => 'string',
      'null' => true,
    ),
  ),
  'indexes' => 
  array (
    'location_name' => 
    array (
      'alias' => 'location_name',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'name' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
);
