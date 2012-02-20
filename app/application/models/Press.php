<?php

class Application_Model_Press extends GP_Application_Model
{
	public $element_id;
	public $element_path;
	public $element_type;
	public $element_description;
	public $edition_id;
	protected $edition;
	
	protected $_dbTableModelName = 'Application_Model_DbTable_Press';

	public function __construct($id = null, array $options = null)
	{
		return parent::__construct($id, $options);
	}

	public function getAllAsType($edition)
	{
		$set = array();
		foreach ($this->getDbTable()->getAllAsType($edition) as $row)
		{
			$obj = new $this;
			$obj->populate($row);
			$set[$row->element_type][] = $obj;
		}
		
		return $set;
	}
}

