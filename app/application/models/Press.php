<?php

class Application_Model_Press extends GP_Application_Model
{
	public $element_id;
	public $element_path;
	public $element_type;
	public $element_description;
	public $position;
	protected $_uploadDir = 'press';
	
	protected $_dbTableModelName = 'Application_Model_DbTable_Press';

	public function __construct($id = null, array $options = null)
	{
		return parent::__construct($id, $options);
	}

	public function getAllAsType()
	{
		$set = array();
		foreach ($this->getDbTable()->getAllAsType() as $row)
		{
			$obj = new $this;
			$obj->populate($row);
			$set[$row->element_type][] = $obj;
		}
		
		return $set;
	}
	
	public function findDescription()
	{
		$row = $this->getDbTable()->getDescriptionElement();
		$this->populate($row);
		return $this;
	}
	
	public function prepareDescriptionFormArray()
	{
		return array(
				'element_id' => $this->element_id,
				'element_type' => 'description',
				'element_description' => $this->element_description,
				);
	}
	
	public function getUploadDir()
	{
		return APPLICATION_PATH . '/../public/assets/' . $this->_uploadDir;
	}
	
	public function save()
	{
		$this->element_path = serialize($this->element_path);
		$this->element_type = 'file';
		parent::save();
	}
	
	public function populate($row)
	{
		parent::populate($row);
		if ($this->element_path)
		{
			$this->element_path = @unserialize($this->element_path);
		}
	}
	
	public function getPath($file)
	{
		$options = Zend_Registry::get('options');
		$baseUrl = isset($options['resources']['frontController']['baseUrl']) ?  $options['resources']['frontController']['baseUrl'] : '/';
		$baseUrl .= Zefir_Filter::getLastChar($baseUrl) != '/' ? '/' : null;  
		return $baseUrl . 'assets/' . $this->_uploadDir .'/' . $file;
	}
}

