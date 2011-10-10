<?php

class Application_Model_ResultFields extends GP_Application_Model
{
	public $result_field_id;
	public $result_id;
	public $lang_id;
	public $field_id;
	public $entry;
	protected $result;
	protected $lang;
	protected $field;
	
	protected $_dbTableModelName = 'Application_Model_DbTable_ResultFields';
	
	public function __construct($id = null, array $options = null) 
	{
	    return parent::__construct($id, $options);
	}
	
	

}

