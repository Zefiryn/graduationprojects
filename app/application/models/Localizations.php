<?php

class Application_Model_Localizations extends GP_Application_Model
{
	protected $_item_id;
	protected $_name;
	protected $_lang_code;
	protected $_text;
	
	protected $_dbTableModelName = 'Application_Model_DbTable_Localizations';
	
	protected $_set_vars = array('_item_id', '_name', '_lang_code', '_text'); 
	protected $_get_vars = array('_item_id', '_name', '_lang_code', '_text'); 
	
	
	public function __construct(array $options = null) 
	{
	    parent::__construct($options);
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

