<?php

class Application_Model_Schools extends GP_Application_Model
{
	protected $_school_id;
	protected $_school_name;
	
	protected $_dbTableModelName = 'Application_Model_DbTable_Schools';
	
	protected $_set_vars = array('_school_id', '_school_name');
	protected $_get_vars = array('_school_id', '_school_name');
	
	
	public function __construct(array $options = null) 
	{
	    parent::__construct($options);
	}
	
	public function getSchools()
	{
		$rowset = $this->getDbTable()->fetchAll();
		
		$select = array(0 => 'add_new_school');
		foreach ($rowset as $row)
		{
			$select[$row->school_id] = $row->school_name; 
		}
		
		return $select;
	}
	

}

