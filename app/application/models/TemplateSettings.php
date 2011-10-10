<?php

class Application_Model_TemplateSettings extends GP_Application_Model
{ 
	public $template_name; 
	public $template_id;
	public $news_limit;
	
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
	
	public function findTemplateByName($name)
	{
		$this->populate($this->getDbTable()->findTemplateByName($name));
		
		return $this;
	}
	
	public function toArray()
	{
		return array(	'template_id' => $this->template_id,
						'template_name' => $this->template_name,
						'news_limit' => $this->news_limit);
	}
}

