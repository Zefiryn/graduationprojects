<?php

class Application_Model_Regualtions extends GP_Application_Model
{
	public $paragraph_id;
	public $lang_id;
	public $paragraph_no;
	public $paragraph_text;
	protected $lang;
	
	protected $_dbTableModelName = 'Application_Model_DbTable_Regulations';
	
	public function __construct($id = null, array $options = null) 
	{
	    return parent::__construct($id, $options);
	}
	
	public function getRegulations($edition)
	{
		$lang = Zend_Registry::get('Zend_Translate');
		
		$rowset = $this->getDbTable()->getRegulations($lang->getLocale());
		
		$regulations = array();
		foreach($rowset as $row)
		{
			$paragraph = new $this;
			$regulations[] = $paragraph->populate($row);
		}
		
		return($regulations);	
	}
	
	public function deleteRegulations($edition)
	{
		$this->getDbTable()->deleteRegulation($edition);
	}

	public function prepareFormArray()
	{
		$data = array(	'paragraph_id' => $this->paragraph_id,
						'regulation_lang' => $this->lang_id,
						'paragraph_no' => $this->paragraph_no,
						'paragraph_text' => $this->paragraph_text);
		
		return $data;
	}
	
	public function positionLast()
	{
		if ($this->lang_id != null)
		{
			$last = $this->getDbTable()->findLastParagraph($this->lang_id);
			if ($last)
				$this->paragraph_no = $last->paragraph_no++; 
			else
				$this->paragraph_no = 1;
		}
		
		return $this;
	}
	
	public function getRegulationLength($lang)
	{
		return $this->getDbTable()->getStringLength($lang);
	}
	
	
}

