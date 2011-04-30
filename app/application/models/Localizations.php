<?php

class Application_Model_Localizations extends GP_Application_Model
{
	public $item_id;
	public $name;
	public $lang_code;
	public $text;
	
	protected $_dbTableModelName = 'Application_Model_DbTable_Localizations';
	
	public function __construct($id = null, array $options = null) 
	{
	    return parent::__construct($id, $options);
	}
	
	public function getTranslationFromDb()
	{
		$translation = array();
		
		$rowset = $this->getDbTable()->fetchAll();
		
		if ($rowset)
		{
			foreach($rowset as $row)
			{
				$translation[$row->lang_code][$row->name] = $row->text; 
			}
		}

		return $translation;
	}
	
	public function populateFromForm($data)
	{
		$this->_name = $data['name'];
		$this->_lang_code = $data['lang_code'];
		$this->_text = $data['text'];
	}
}

