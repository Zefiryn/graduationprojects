<?php

class Application_Model_Editions extends GP_Application_Model
{
	public $edition_id;
	public $edition_name;
	public $edition_name2;
	protected $applications;
	protected $diplomas;
	
	protected $_dbTableModelName = 'Application_Model_DbTable_Editions';
	
	public function __construct($id = null, array $options = null) 
	{
	    return parent::__construct($id, $options);
	}

	public function editionExists($edition, $onlyName = FALSE)
	{
		$this->getEdition($edition, $onlyName);

		if ($this->edition_id != NULL)
			$check = TRUE;
		else 
			$check = FALSE;
		
		return $check;
	}
	
	public function getEditions($order = 'ASC')
	{
		$select = $this->getDbTable()->select()->order('edition_name '.$order);
		$rowset = $this->getDbTable()->fetchAll($select);
		
		$editions = array();
		foreach($rowset as $row)
		{ 
			$editions[$row->edition_id] = $row->edition_name;
		}
		
		return $editions;
	}
	
	
	public function getEdition($edition, $onlyName = FALSE)
	{
		if (ctype_digit($edition) && !$onlyName)
			$row = $this->getDbTable()->find($edition)->current();
		
		else 
			$row = $this->getDbTable()->fetchRow($this->getDbTable()->select()->where('edition_name = ? ', $edition));
		
		if ($row)
		{
			$this->populate($row);
		}
		return $this;
	}
	
	public function getEditionByName($edition)
	{
		$where = $this->getDbTable()->select()->where('edition_name = ?', $edition);
		$row = $this->getDbTable()->fetchRow($where);
		
		if ($row)
		{
			$this->populate($row);
		}
		
		return $this;
	}
	
	public function prepareFormArray()
	{
		$data = array(
			'edition_id' => $this->edition_id,
			'edition_name' => $this->edition_name
		);
		
		return $data;
	}
}

