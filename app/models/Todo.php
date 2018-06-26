<?php

namespace Model;

use DateTime;

class Todo extends \PicORM\Model
{
    protected static $_tableName = 'todo';
    protected static $_primaryKey = 'id';
    protected static $_relations = array();

    protected static $_tableFields = array(
        'title',
        'category_id',
        'created_at'
    );

    public $id;
    public $title;
    public $created_at;
    public $category_id;

    function __construct()
    {
        $now = new DateTime;
        $this->created_at = $now->format('Y-m-d H:i:s');
    }

    protected static function defineRelations()
	{
		self::addRelationOneToOne('category_id', Category::class, 'id');
	}

}