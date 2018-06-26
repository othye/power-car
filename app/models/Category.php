<?php

namespace Model;

class Category extends \PicORM\Model
{
    protected static $_tableName = 'category';
    protected static $_primaryKey = 'id';
    protected static $_relations = array();

    protected static $_tableFields = array(
        'name',
    );

    public $id;
    public $name;

    protected static function defineRelations()
	{
		self::addRelationOneToMany('id', Todo::class, 'category_id');
	}

}