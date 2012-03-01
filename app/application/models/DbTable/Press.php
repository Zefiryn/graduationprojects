<?php

class Application_Model_DbTable_Press extends Zefir_Application_Model_DbTable
{

	protected $_raw_name = 'press';
	protected $_name;
	protected $_primary = 'element_id';
	
	protected $_hasMany = array(
			'files' => array(
					'model' => 'Application_Model_PressFiles',
					'refColumn' => 'element_id',
		));

	public function getAllFiles()
	{
		$select = $this->select()->where('element_type = ?', 'file')->order('position ASC');
		return $this->fetchAll($select);
	}
	
	public function getLastPosition()
	{
		$select = $this->select()->order('position DESC')->limit(1);
		$row = $this->fetchAll($select)->current();
		return $row->position;
	}
	
	public function getDescriptionElement()
	{
		$select = $this->select()->where('element_type = "description"');
		
		return $this->fetchAll($select);
	}

}

