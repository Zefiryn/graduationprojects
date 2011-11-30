<?php

class Application_Model_Fields extends GP_Application_Model
{
	public $field_id;
	public $field_name;
	protected $result_fields;


	protected $_dbTableModelName = 'Application_Model_DbTable_Fields';


	public function __construct($id = null, array $options = null)
	{
		return parent::__construct($id, $options);
	}

	public function getField($name)
	{
		$this->populate($this->getDbTable()->getField($name));
		return $this;
	}


}

