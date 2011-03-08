<?php

class Application_Model_Editions extends GP_Application_Model
{
	protected $_edition_id;
	protected $_edition_name;
	protected $_applications;
	
	protected $_dbTableModelName = 'Application_Model_DbTable_Editions';
	
	protected $_set_vars = array('_edition_id', '_edition_name', '_applications');
	protected $_get_vars = array('_edition_id', '_edition_name', '_applications');
	
	
	public function __construct(array $options = null) 
	{
	    parent::__construct($options);
	}

	public function editionExists($edition)
	{
		$row = $this->getDbTable()->findEdition($edition);

		if ($row != NULL)
			$check = TRUE;
		else 
			$check = FALSE;
		
		return $check;
	}
	
	public function getEditions()
	{
		$rowset = $this->getDbTable()->fetchAll();
		
		$editions = array();
		foreach($rowset as $row)
		{
			$editions[$row->edition_id] = $row->edition_name;
		}
		
		return $editions;
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
	
}

