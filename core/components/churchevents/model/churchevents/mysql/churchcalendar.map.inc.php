<?php
$xpdo_meta_map['ChurchCalendar']= array (
  'package' => 'churchevents',
  'version' => '1.1',
  'table' => 'church_calendar',
  'composites' => 
  array (
    'Event' => 
    array (
      'class' => 'ChurchEvents',
      'local' => 'id',
      'foreign' => 'church_calendar_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
  'fields' => 
  array (
    'title' => '',
    'description' => NULL,
    'request_notify' => NULL,
  ),
  'fieldMeta' => 
  array (
    'title' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '128',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'index',
    ),
    'description' => 
    array (
      'dbtype' => 'mediumtext',
      'phptype' => 'string',
      'null' => false,
    ),
    'request_notify' => 
    array (
      'dbtype' => 'mediumtext',
      'phptype' => 'string',
      'null' => false,
    ),
  ),
  'indexes' => 
  array (
    'title' => 
    array (
      'alias' => 'title',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'title' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
);
