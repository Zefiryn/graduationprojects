<?php

class Application_Model_Languages extends GP_Application_Model
{
	public $lang_id;
	public $lang_code;
	protected $localizations;
	
	protected $_dbTableModelName = 'Application_Model_DbTable_Languages';
	
	public function __construct($id = null, array $options = null) 
	{
	    return parent::__construct($id, $options);
	}
	
	public function isLocalization($lang)
	{
		return $this->getDbTable()->isLocalization($lang);
	}
	
	public function findLang($lang)
	{
		return $this->fetchAll($this->where('lang_code = ?', $lang));
	}
	
	public function findLangId($lang)
	{
		$rowset = $this->findLang($lang);
		return (array_shift($rowset)->lang_id);
	}
	
}

