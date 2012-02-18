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

		if (self::$_cache != null)
		{
			$table= self::$_cache->load('Application_Model_Localizations');
			if ($table == null)
			{
				$table = $this->_createCachedTable();
			}	
			$rowset = $table['data'];
		}
		else
		{
			$rowset = $this->fetchAll();
		}
		if ($rowset)
		{
			foreach($rowset as $row)
			{
				$translation[$this->_getLanguage($row->lang_id)][$this->_getCaption($row->caption_id)] = $row->text;
			}
			
		}

		return $translation;
	}
	
	public function isLocalization($lang)
	{
		if (self::$_cache != null)
		{
			$languages  = self::$_cache->load('languages');
			
			if ($languages == null)
			{//cache data
				$languagesObject = new Application_Model_Languages();
				$languages = $languagesObject->_createCachedTable();
			}
			
			foreach($languages['data'] as $language)
			{
				if ($lang == $language->lang_code)
				{
					return TRUE;
				}
			}
			return FALSE;
		}
		else 
		{
			return $this->getDbTable()->isLocalization($lang);
		}
	}

	protected function _getCaption($caption_id)
	{
		$data = (isset($this->_captions[$caption_id])) ? $this->_captions[$caption_id] : array();
		return $data;
	}

	protected function _getLanguage($lang_id)
	{
		$data = (isset($this->_languages[$lang_id])) ? $this->_languages[$lang_id] : array();
		return $data;
	}

	protected function _getAllCaptions()
	{
		if (self::$_cache != null)
		{
			$rowset = self::$_cache->load('Application_Model_Captions');
			$rowset = $rowset['data'];
			
			if ($rowset == null)
			{
				$caption = new Application_Model_Captions();
				$table = $caption->_createCachedTable();
				$rowset = $table['data'];
			}
		}
		else
		{
			$caption = new Application_Model_Captions();
			$rowset = $caption->fetchAll();
		}
		
		foreach($rowset as $row)
		{
			$this->_captions[$row->caption_id] = $row->name;
		}

	}
	
	public function getAllLanguages()
	{
		$this->_getAllLanguages();
		return $this->_languages;
	}
	
	protected function _getAllLanguages()
	{
		if (self::$_cache != null)
		{
			$rowset = self::$_cache->load('Application_Model_Languages');
			$rowset = $rowset['data']; 
		
			if ($rowset == null)
			{
				$language = new Application_Model_Languages();
				$table = $language->_createCachedTable();
				$rowset = $table['data'];
			}
		}
		else 
		{
			$language = new Application_Model_Languages();
			$rowset = $language->fetchAll();
		}

		foreach($rowset as $row)
		{
			$this->_languages[$row->lang_id] = $row->lang_code;
		}	
		
	}
}

