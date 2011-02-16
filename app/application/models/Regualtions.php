<?php

class Application_Model_Regualtions extends GP_Application_Model
{
	protected $_paragraph_id;
	protected $_regulation_lang;
	protected $_paragraph_no;
	protected $_paragraph_text;
	
	protected $_dbTableModelName = 'Application_Model_DbTable_Regulations';
	
	protected $_set_vars = array('_paragraph_id', '_regulation_lang', 
								'_paragraph_no', '_paragraph_text'); 
	protected $_get_vars = array('_paragraph_id', '_regulation_lang', 
								'_paragraph_no', '_paragraph_text'); 
	
	
	public function __construct(array $options = null) 
	{
	    parent::__construct($options);
	}
	
	public function getRegulations()
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

}

