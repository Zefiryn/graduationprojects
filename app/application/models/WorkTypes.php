<?php

class Application_Model_WorkTypes extends GP_Application_Model
{
	public $work_type_id;
	public $work_type_name;
	protected $applications;

	protected $_dbTableModelName = 'Application_Model_DbTable_WorkTypes';

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

	public function findWorkType($name)
	{
		$row = $this->getDbTable()->findWorkType($name);
		if ($row)
		$this->populate($row);

		return $this;
	}

	public function prepareFormArray()
	{
		$data = array(
			'work_type_id' => $this->work_type_id,
			'work_type_name' => $this->work_type_name
		);

		return $data;
	}
}

