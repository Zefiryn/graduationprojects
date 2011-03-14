<?php

class Application_Model_WorkTypes extends GP_Application_Model
{
	protected $_work_type_id;
	protected $_work_type_name;
	
	protected $_dbTableModelName = 'Application_Model_DbTable_WorkTypes';
	
	protected $_set_vars = array('_work_type_id', '_work_type_name');
	protected $_get_vars = array('_work_type_id', '_work_type_name');
	
	
	public function __construct($id = null, array $options = null) 
	{
	    return parent::__construct($id, $options);
	}
	

	public function getWorkTypes()
	{
		$rowset = $this->getDbTable()->fetchAll();
		
		$select[0] = 'empty_type';
		foreach ($rowset as $row)
		{
			$select[$row->work_type_id] = $row->work_type_name; 
		}
		
		return $select;
	}
	
	public function getWorkType($type_id)
	{
		$row = $this->getDbTable()->find($type_id)->current();
		
		if ($row)
			$this->populate($row);

		return $this;
	}
	
	public function prepareFormArray()
	{
		$data = array(
			'work_type_id' => $this->_work_type_id,
			'work_type_name' => $this->_work_type_name
		);
		
		return $data;
	}
}

