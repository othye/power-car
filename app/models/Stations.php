<?php

namespace Model;

use DateTime;

class Stations extends \PicORM\Model
{
    protected static $_tableName = 'stations';
    protected static $_primaryKey = 'id';
    protected static $_relations = array();

    protected static $_tableFields = array(
        'station_ref',
        'name_station',
        'address',
        'zip',
        'city',
        'latitude',
        'longitude'
    );

    public $id;
    public $station_ref;
    public $name_station;
    public $address;
    public $zip;
    public $city;
    public $latitude;
    public $longitude;

    protected static function defineRelations()
	{
		self::addRelationOneToMany('id', Todo::class, 'id_station');
	}

}