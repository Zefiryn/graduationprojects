<?php

class Application_Model_DiplomaFields extends GP_Application_Model
{
	public $diploma_field_id;
	public $diploma_id;
	public $lang_id;
	public $field_id;
	public $entry;
	protected $diploma;
	protected $lang;
	protected $field;
	
	protected $_dbTableModelName = 'Application_Model_DbTable_DiplomaFields';
	
	public function __construct($id = null, array $options = null) 
	{
	    return parent::__construct($id, $options);
	}
	
	
	

}

