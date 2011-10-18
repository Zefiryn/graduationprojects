<?php

class Application_Model_Degrees extends GP_Application_Model
{
	public $degree_id;
	public $degree_name;
	protected $applications;
	
	protected $_dbTableModelName = 'Application_Model_DbTable_Degrees';
	
	public function __construct($id = null, array $options = null) 
	{
	    return parent::__construct($id, $options);
	}
	
	public function getDegreesList()
	{
		$rowset = $this->getDbTable()->fetchAll();
		
		$select[0] = 'empty_degree';
		foreach ($rowset as $row)
		{
			$select[$row->degree_id] = $row->degree_name; 
		}
		
		return $select;
	}
	
	public function findDegree($degree)
	{
		return($this->populate($this->getDbTable()->findDegree($degree)));
	}

}

