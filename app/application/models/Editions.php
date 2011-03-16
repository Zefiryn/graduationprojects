<?php

class Application_Model_Editions extends GP_Application_Model
{
	protected $_edition_id;
	protected $_edition_name;
	protected $_applications;
	
	protected $_dbTableModelName = 'Application_Model_DbTable_Editions';
	
	protected $_set_vars = array('_edition_id', '_edition_name', '_applications');
	protected $_get_vars = array('_edition_id', '_edition_name', '_applications');
	
	
	public function __construct($id = null, array $options = null) 
	{
	    return parent::__construct($id, $options);
	}

	public function editionExists($edition, $onlyName = FALSE)
	{
		$this->getEdition($edition, $onlyName);

		if ($this->_edition_id != NULL)
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
			$this->getDbTable()->getChildren($row, $this);
		}
		
		return $this;
	}
	
	public function prepareFormArray()
	{
		$data = array(
			'edition_id' => $this->_edition_id,
			'edition_name' => $this->_edition_name
		);
		
		return $data;
	}
}

