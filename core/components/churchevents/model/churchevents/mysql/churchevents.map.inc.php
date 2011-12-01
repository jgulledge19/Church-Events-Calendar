<?php
$xpdo_meta_map['ChurchEvents']= array (
  'package' => 'churchevents',
  'version' => '1.1',
  'table' => 'church_events',
  'composites' => 
  array (
    'EventLocations' => 
    array (
      'class' => 'ChurchEventLocations',
      'local' => 'id',
      'foreign' => 'church_event_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
  'aggregates' => 
  array (
    'Calendar' => 
    array (
      'class' => 'ChurchCalendar',
      'local' => 'church_calendar_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'ECategory' => 
    array (
      'class' => 'ChurchEcategory',
      'local' => 'church_ecategory_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
  'fields' => 
  array (
    'church_calendar_id' => 0,
    'church_ecategory_id' => 0,
    'parent_id' => 0,
    'version' => 1,
    'web_user_id' => 0,
    'prominent' => 'No',
    'status' => 'submitted',
    'start_date' => NULL,
    'end_date' => NULL,
    'event_timed' => 'Y',
    'duration' => NULL,
    'public_start' => NULL,
    'public_end' => NULL,
    'repeat_type' => 'none',
    'interval' => 0,
    'exceptions' => NULL,
    'days' => '',
    'event_type' => 'public',
    'title' => '',
    'public_desc' => NULL,
    'notes' => NULL,
    'office_notes' => NULL,
    'contact' => '',
    'contact_email' => '',
    'contact_phone' => '',
    'personal_subscribers' => NULL,
    'location_name' => '',
    'address' => '',
    'city' => '',
    'state' => '',
    'zip' => '0',
    'country' => '',
    'extended' => NULL,
  ),
  'fieldMeta' => 
  array (
    'church_calendar_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'church_ecategory_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'parent_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'version' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '3',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 1,
    ),
    'web_user_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '8',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'prominent' => 
    array (
      'dbtype' => 'set',
      'precision' => '\'Yes\',\'No\'',
      'phptype' => 'string',
      'null' => true,
      'default' => 'No',
    ),
    'status' => 
    array (
      'dbtype' => 'set',
      'precision' => '\'approved\',\'deleted\',\'pending\',\'submitted\',\'rejected\'',
      'phptype' => 'string',
      'null' => false,
      'default' => 'submitted',
    ),
    'start_date' => 
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
      'null' => false,
      'index' => 'index',
    ),
    'end_date' => 
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
      'null' => false,
      'index' => 'index',
    ),
    'event_timed' => 
    array (
      'dbtype' => 'set',
      'precision' => '\'Y\',\'N\',\'allday\'',
      'phptype' => 'string',
      'null' => false,
      'default' => 'Y',
    ),
    'duration' => 
    array (
      'dbtype' => 'time',
      'phptype' => 'string',
      'null' => false,
      'index' => 'index',
    ),
    'public_start' => 
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
      'null' => false,
      'index' => 'index',
    ),
    'public_end' => 
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
      'null' => false,
      'index' => 'index',
    ),
    'repeat_type' => 
    array (
      'dbtype' => 'set',
      'precision' => '\'none\',\'daily\',\'weekly\',\'monthly\'',
      'phptype' => 'string',
      'null' => true,
      'default' => 'none',
    ),
    'interval' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '3',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'exceptions' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
    'days' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '128',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'index',
    ),
    'event_type' => 
    array (
      'dbtype' => 'set',
      'precision' => '\'public\',\'private\'',
      'phptype' => 'string',
      'null' => true,
      'default' => 'public',
    ),
    'title' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'index',
    ),
    'public_desc' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
    'notes' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
    'office_notes' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
    'contact' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '128',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'contact_email' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '128',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'contact_phone' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '32',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'personal_subscribers' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
    'location_name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '128',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'address' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '128',
      'phptype' => 'string',
      'null' => false,
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
      'dbtype' => 'varchar',
      'precision' => '128',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'zip' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '32',
      'phptype' => 'string',
      'null' => false,
      'default' => '0',
      'index' => 'index',
    ),
    'country' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '128',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'extended' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
  ),
  'indexes' => 
  array (
    'church_calendar_id' => 
    array (
      'alias' => 'church_calendar_id',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'church_calendar_id' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'church_ecategory_id' => 
    array (
      'alias' => 'church_ecategory_id',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'church_ecategory_id' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'start_date' => 
    array (
      'alias' => 'start_date',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'start_date' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'end_date' => 
    array (
      'alias' => 'end_date',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'end_date' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'duration' => 
    array (
      'alias' => 'duration',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'duration' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'public_start' => 
    array (
      'alias' => 'public_start',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'public_start' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'public_end' => 
    array (
      'alias' => 'public_end',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'public_end' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'days' => 
    array (
      'alias' => 'days',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'days' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
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
    'zip' => 
    array (
      'alias' => 'zip',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'zip' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
);
