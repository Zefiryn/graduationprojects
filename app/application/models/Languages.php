<?php

class Application_Model_Languages extends GP_Application_Model
{
	public $lang_id;
	public $lang_code;
	protected $localizations;
	protected $about;
	protected $news;
	protected $regualtions;
	
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
		$row = $this->getDbTable()->fetchRow($this->where('lang_code = ?', $lang));

		return ($this->populate($row));
	}
	
	public function findLangId($lang)
	{
		$rowset = $this->findLang($lang);
		return (array_shift($rowset)->lang_id);
	}
	
	public function getLanguages()
	{
		$rowset = $this->getDbTable()->fetchAll();
		
		foreach ($rowset as $row)
		{
			$select[$row->lang_id] = $row->lang_code; 
		}
		
		return $select;
	}
	
}

