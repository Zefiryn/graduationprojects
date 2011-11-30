<?php

class Application_Model_Captions extends GP_Application_Model
{
	public $caption_id;
	public $name;
	protected $localizations;

	protected $_dbTableModelName = 'Application_Model_DbTable_Captions';

	public function __construct($id = null, array $options = null)
	{
		return parent::__construct($id, $options);
	}

	public function translate($string, $lang)
	{
		$translations = Zend_Registry::get('translations');
		return isset($translations[$lang][$string]) ? $translations[$lang][$string] : null;
	}

	public function getTranslationObject($lang)
	{
		$lang_id = $this->_getLangId($lang);
		$this->__get('localizations');
		foreach ($this->__get('localizations') as $loc)
		{
			if ($loc->lang_id == $lang_id)
			return $loc;
		}
		 
		return new Application_Model_Localizations();
	}

	public function getCaptionId($name)
	{
		$row = $this->getDbTable()->findCaption($name);
		 
		if ($row)
		return $row['caption_id'];
		else
		return NULL;
	}

	protected function _getLangId($language)
	{
		$lang = new Application_Model_Languages();
		$languages = $lang->findLang($language);
		return ($languages->lang_id);
	}


}

