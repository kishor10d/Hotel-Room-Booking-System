<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| ACCESS MATRIX MODULE LIST
| -------------------------------------------------------------------
| This is the canonical list of modules governed by the per-role
| access matrix (see Roles controller / Role_model). Adding a new
| module here makes it available in the permissions editor.
|
*/
$config['moduleList'] = array(
    array('module'=>'Floors',
        'total_access'=>0, 'list'=>0, 'create_records'=>0, 'edit_records'=>0, 'delete_records'=>0),
    array('module'=>'Rooms',
        'total_access'=>0, 'list'=>0, 'create_records'=>0, 'edit_records'=>0, 'delete_records'=>0),
    array('module'=>'RoomSizes',
        'total_access'=>0, 'list'=>0, 'create_records'=>0, 'edit_records'=>0, 'delete_records'=>0),
    array('module'=>'BaseFare',
        'total_access'=>0, 'list'=>0, 'create_records'=>0, 'edit_records'=>0, 'delete_records'=>0),
    array('module'=>'Reports',
        'total_access'=>0, 'list'=>0, 'create_records'=>0, 'edit_records'=>0, 'delete_records'=>0),
    array('module'=>'Customer',
        'total_access'=>0, 'list'=>0, 'create_records'=>0, 'edit_records'=>0, 'delete_records'=>0),
    array('module'=>'Booking',
        'total_access'=>0, 'list'=>0, 'create_records'=>0, 'edit_records'=>0, 'delete_records'=>0),
);
