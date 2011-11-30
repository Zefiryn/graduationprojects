<?php

class Application_Model_DbTable_TemplateSettings extends Zefir_Application_Model_DbTable
{

	protected $_raw_name = 'template_settings';
	protected $_primary = 'template_id';

	public function findTemplateByName($name)
	{
		$select = $this->select()->where('template_name = ?', $name);
		return $this->fetchAll($select)->current();
	}
	 
}

