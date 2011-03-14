<?php

class Application_Model_Faqs extends GP_Application_Model
{
	protected $_faq_id;
	protected $_faq_lang;
	protected $_faq_question;
	protected $_faq_answer;
	
	protected $_dbTableModelName = 'Application_Model_DbTable_Faqs';
	
	protected $_set_vars = array('_faq_id',	'_faq_lang', '_faq_question', '_faq_answer'); 
	protected $_get_vars = array('_faq_id',	'_faq_lang', '_faq_question', '_faq_answer'); 
	
	
	public function __construct($id = null, array $options = null) 
	{
	    return parent::__construct($id, $options);
	}
	
	public function getFaqs()
	{
		$lang = Zend_Registry::get('Zend_Translate');
		$rowset = $this->getDbTable()->getFaq($lang->getLocale());
		
		$faq = array();
		foreach($rowset as $row)
		{
			$question = new $this;
			$faq[] = $question->populate($row);
		}
		
		return($faq);
		
	}

}

