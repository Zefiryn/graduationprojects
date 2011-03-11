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
	
	
	public function __construct(array $options = null) 
	{
	    parent::__construct($options);
	}
	
	public function getRegulations($edition)
	{
		$lang = Zend_Registry::get('Zend_Translate');
		
		$editions = new Application_Model_Editions();
		$edition = $editions->getDbTable()->findEdition($edition);

		$rowset = $this->getDbTable()->getRegulations($lang->getLocale(), $edition->edition_id);
		
		$regulations = array();
		foreach($rowset as $row)
		{
			$paragraph = new $this;
			$regulations[] = $paragraph->populate($row);
		}
		
		return($regulations);	
	}

	public function saveRegulations($data)
	{
		
		$data = $this->_prepareDataFromForm($data);
		
		//get editing edition and language
		$current_edition = Zend_Registry::get('edition');
		$edition = new Application_Model_Editions();
		$edition->populate($edition->getDbTable()->findEdition($current_edition));
		$lang = new Zend_Session_Namespace('lang');

		
		//update existing one
		foreach($data as $number => $regulation)
		{	
			$reg = new $this;
			if (!strstr($number, 'new_'))
				$reg->_paragraph_id = $number; 
			$reg->_edition = $edition->_edition_id;
			$reg->_regulation_lang = $lang->lang;  
			$reg->_paragraph_no = $regulation['paragraph_no'];
			$reg->_paragraph_text = $regulation['paragraph_text'];
			
			if (isset($regulation['paragraph_remove']) && $regulation['paragraph_remove'] == '1')
			{
				$this->getDbTable()->deleteParagraph($reg);
			}	
			else
			{
				$this->getDbTable()->saveParagraph($reg);
			}
			
		}
	}
	
	protected function _prepareDataFromForm($data)
	{
		foreach($data as $key => $value)
		{
			
			if (!strstr($key, 'new_paragraph'))
			{
				$no = substr($key, strrpos($key, '_')+1);
				$key = substr($key, 0, strrpos($key, '_'));
				$save[$no][$key] = $value;
			}
			else
			{
				if ($value != '')
				{
					$no = substr($key, strrpos($key, '_')+1);
					$key = substr($key, 0, strrpos($key, '_'));
					$key = str_replace('new_', '', $key);
					$save['new_'.$no][$key] = $value;
				}
			}
		}
		
		return ($save);
	}
	
	public function getValues()
	{
		return array('paragraph_id' => $this->_paragraph_id,
					'paragraph_no' => $this->_paragraph_no,
					'paragraph_text' => $this->_paragraph_text,
					'edition' => $this->_edition,
					'regulation_lang' => $this->_regulation_lang); 	
	}
}

