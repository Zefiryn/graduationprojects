<?php

class Application_Model_TemplateSettings extends GP_Application_Model
{ 
	public $template_name; 
	public $template_id;
	
	protected $_dbTableModelName = 'Application_Model_DbTable_TemplateSettings';
	
	public function __construct($id = null, array $options = null) 
	{
	    return parent::__construct($id, $options);
	}
	
	public function getTemplates()
	{
		$rowset = $this->getDbTable()->fetchAll();
		
		foreach($rowset as $row)
		{
			$array[$row->template_id] = $row->template_name;
		}
		
		return $array;
	}
}

