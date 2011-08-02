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
}

