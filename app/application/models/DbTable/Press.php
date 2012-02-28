<?php

class Application_Model_DbTable_Press extends Zefir_Application_Model_DbTable
{

	protected $_raw_name = 'press';
	protected $_name;
	protected $_primary = 'element_id';

	public function getAllAsType()
	{
		$select = $this->select()->where('element_type != "descripption"')->order('element_type ASC');
		return $this->fetchAll($select);
	}
	
	public function getDescriptionElement()
	{
		$select = $this->select()->where('element_type = "description"');
		
		return $this->fetchAll($select);
	}

}

