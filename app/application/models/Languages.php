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
	
	/**
	 * Get language data according to given lang_code
	 * 
	 * @access public
	 * @param string $lang
	 * @return Application_Model_Languages $this
	 */
	public function findLang($lang)
	{
		$row = $this->getDbTable()->findLang($lang);
		
		if ($row)
			$this->populate($row);
			
		return ($this);
	}
	
	/**
	 * Get language id according to given lang_code
	 * 
	 * @access public
	 * @param string $lang
	 * @return int $lang_id
	 */
	public function findLangId($lang)
	{
		$this->findLang($lang);
		
		return ($this->lang_id);
	}
	
	/**
	 * Get language array for select fields
	 * 
	 * @access public
	 * @return array $select
	 */
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

