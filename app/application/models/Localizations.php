<?php

class Application_Model_Localizations extends GP_Application_Model
{
	public $item_id;
	public $lang_id;
	public $caption_id;
	public $text;
	protected $language;
	protected $caption;
	protected $_languages = array();
	protected $_captions = array();
	
	protected $_dbTableModelName = 'Application_Model_DbTable_Localizations';
	
	public function __construct($id = null, array $options = null) 
	{
	    return parent::__construct($id, $options);
	}
	
	public function getTranslationFromDb()
	{
		$translation = array();
		$this->_getAllCaptions();
		$this->_getAllLanguages();
		
		$rowset = $this->getDbTable()->fetchAll();
		
		if ($rowset)
		{
			foreach($rowset as $row)
			{
				$translation[$this->_getLanguage($row->lang_id)][$this->_getCaption($row->caption_id)] = $row->text; 
			}
		}

		return $translation;
	}

	protected function _getCaption($caption_id)
	{
		return $this->_captions[$caption_id];
	}
	
	protected function _getLanguage($lang_id)
	{
		return $this->_languages[$lang_id];
	}
	
	protected function _getAllCaptions()
	{
		$caption = new Application_Model_Captions();
		$rowset = $caption->getDbTable()->fetchAll();
		foreach($rowset as $row)
		{
			$this->_captions[$row['caption_id']] = $row['name'];
		}
		
	}
	protected function _getAllLanguages()
	{
		$language = new Application_Model_Languages();
		$rowset = $language->getDbTable()->fetchAll();
		foreach($rowset as $row)
		{
			$this->_languages[$row['lang_id']] = $row['lang_code'];
		}
	}
}

