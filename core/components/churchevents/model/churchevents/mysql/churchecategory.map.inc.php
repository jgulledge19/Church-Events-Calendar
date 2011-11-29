<?php
$xpdo_meta_map['ChurchEcategory']= array (
  'package' => 'churchevents',
  'version' => '1.1',
  'table' => 'church_ecategory',
  'composites' => 
  array (
    'Event' => 
    array (
      'class' => 'ChurchEvents',
      'local' => 'id',
      'foreign' => 'church_ecategory_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
  'fields' => 
  array (
    'name' => '',
    'background' => '',
    'color' => '',
  ),
  'fieldMeta' => 
  array (
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '32',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'index',
    ),
    'background' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '6',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'color' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '6',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
  ),
  'indexes' => 
  array (
    'name' => 
    array (
      'alias' => 'name',
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
