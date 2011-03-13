<?php

class Application_Model_Schools extends GP_Application_Model
{
	protected $_school_id;
	protected $_school_name;
	protected $_appliations;
	
	protected $_dbTableModelName = 'Application_Model_DbTable_Schools';
	
	protected $_set_vars = array('_school_id', '_school_name', '_applications');
	protected $_get_vars = array('_school_id', '_school_name', '_applications');
	
	
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
	
	/**
	 * Find school according to its name 
	 * @param string $name
	 * @param 
	 */
	public function getSchoolByName($name)
	{
		$this->getDbTable()->findByName($name, $this);
		
		return $this;
	}
	
	/**
	 * Find school by id 
	 * @param string $name
	 * @param 
	 */
	public function getSchool($id)
	{
		$row = $this->getDbTable()->find($id)->current();
		
		$this->populate($row);
		
		return $this;
	}
	
	public function prepareFormArray()
	{
		$data = array(	'school_id' => $this->_school_id,
						'school_name' => $this->_school_name);
		
		return $data;
	}

}

