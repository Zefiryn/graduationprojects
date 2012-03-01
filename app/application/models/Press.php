<?php

class Application_Model_Press extends GP_Application_Model
{
	public $element_id;
	public $element_type;
	public $element_description;
	public $position;
	protected $files;
	protected $_uploadDir = 'press';
	
	protected $_dbTableModelName = 'Application_Model_DbTable_Press';

	public function __construct($id = null, array $options = null)
	{
		return parent::__construct($id, $options);
	}

	public function getAllFiles()
	{
		$set = array();
		$rowset = $this->getDbTable()->getAllFiles();
		if ($rowset)
		{
			foreach ($rowset as $row)
			{
				$obj = new $this;
				$obj->populate($row);
				$set[] = $obj;
			}
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
		$this->element_type = 'file';
		if ($this->popsition == null )$this->popsition = $this->getDbTable()->getLastPosition();
		parent::save();
		
		if ($this->files != null)
		{
			foreach($this->files as $file)
			{
				$file->element_id = $this->element_id;
				$file->save();
			}
		}
		
		return $this;
		
	}
}

