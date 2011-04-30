<?php

class Application_Model_Regualtions extends GP_Application_Model
{
	public $paragraph_id;
	public $regulation_lang;
	public $paragraph_no;
	public $paragraph_text;
	protected $edition;
	
	protected $_dbTableModelName = 'Application_Model_DbTable_Regulations';
	
	public function __construct($id = null, array $options = null) 
	{
	    return parent::__construct($id, $options);
	}
	
	public function getRegulations($edition)
	{
		$lang = Zend_Registry::get('Zend_Translate');
		
		$editions = new Application_Model_Editions();
		$editions->getEdition($edition);
		
		$rowset = $this->getDbTable()->getRegulations($lang->getLocale(), $editions->edition_id);
		
		$regulations = array();
		foreach($rowset as $row)
		{
			$paragraph = new $this;
			$regulations[] = $paragraph->populate($row);
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

