<?php

class Application_Model_Regualtions extends GP_Application_Model
{
	protected $_paragraph_id;
	protected $_edition;
	protected $_regulation_lang;
	protected $_paragraph_no;
	protected $_paragraph_text;
	
	protected $_dbTableModelName = 'Application_Model_DbTable_Regulations';
	
	protected $_set_vars = array('_paragraph_id', '_edition', '_regulation_lang', 
								'_paragraph_no', '_paragraph_text'); 
	protected $_get_vars = array('_paragraph_id', '_edition', '_regulation_lang', 
								'_paragraph_no', '_paragraph_text'); 
	
	
	public function __construct($id = null, array $options = null) 
	{
	    return parent::__construct($id, $options);
	}
	
	public function getRegulations($edition)
	{
		$lang = Zend_Registry::get('Zend_Translate');
		
		$editions = new Application_Model_Editions();
		$editions->getEdition($edition);
		
		$rowset = $this->getDbTable()->getRegulations($lang->getLocale(), $editions->_edition_id);
		
		$regulations = array();
		foreach($rowset as $row)
		{
			$paragraph = new $this;
			$regulations[] = $paragraph->populateWithReference($row);
		}
		
		return($regulations);	
	}
	
	public function deleteRegulations($edition)
	{
		$this->getDbTable()->deleteRegulation($edition);
	}

	public function prepareFormArray()
	{
		$data = array(	'paragraph_id' => $this->_paragraph_id,
						'edition' => $this->_edition->_edition_id,
						'regulation_lang' => $this->_regulation_lang,
						'paragraph_no' => $this->_paragraph_no,
						'paragraph_text' => $this->_paragraph_text);
		
		return $data;
	}
	
	
	
}

