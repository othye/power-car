<?php

namespace Model;

class Technicals extends \PicORM\Model
{
    protected static $_tableName = 'technical_infos';
    protected static $_primaryKey = 'id';
    protected static $_relations = array();

    protected static $_tableFields = array(
        'company',
        'charge_type',
        'nbr_pdc',
        'connectors_type',
        'more_infos',
        'sources',
        'id_station'
    );

    public $id;
    public $company;
    public $charge_type;
    public $nbr_pdc;
    public $connectors_type;
    public $more_infos;
    public $sources;
    public $id_station;


   /*  function __construct()
    {
        $now = new DateTime;
        $this->created_at = $now->format('Y-m-d H:i:s');
    } */

    protected static function defineRelations()
	{
		self::addRelationOneToOne('id_station', stations::class, 'id');
	}
    

}