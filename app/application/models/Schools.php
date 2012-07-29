<?php

class Application_Model_Schools extends GP_Application_Model
{
	public $school_id;
	public $school_name;
	public $school_country;
	protected $applications;

	protected $_dbTableModelName = 'Application_Model_DbTable_Schools';

	public function __construct($id = null, array $options = null)
	{
		return parent::__construct($id, $options);
	}
	
	public function __toString()
	{
		return $this->school_name;
	}

	public function getSchools($with_country = false)
	{
		if ($with_country) 
		{
			$query = $this->getDbTable()->select()->order(array('school_country ASC', 'school_name ASC'));
		}
		else 
		{
			$query= $this->getDbTable()->select()->order('school_name ASC');
		} 
			
		$rowset = $this->getDbTable()->fetchAll($query);

		$select = array('unknown' => array(), 'pl' => array(), 'sk' => array(), 'cz' => array(), 'hu' => array());
		if (!$with_country) $select = array(0 => 'choose_new_school');
		foreach ($rowset as $row)
		{
			$country = $row->school_country != null ? $row->school_country : 'unknown'; 
			$with_country ? $select[$country][$row->school_id] = $row->school_name : $select[$row->school_id] = $row->school_name;
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
		$row = $this->getDbTable()->findByName($name);
		if ($row)
		{
			$this->populate($row);
		}
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
		$data = array(	
						'school_id' => $this->school_id,
						'school_name' => $this->school_name,
						'school_country' => $this->school_country);

		return $data;
	}

	public function findSchool($name)
	{
		$rowset = $this->getDbTable()->findSchool($name);
		$data = array();
		foreach($rowset as $row)
		{
			$data[] = $row['school_name'];
		}

		return $data;
	}

}

