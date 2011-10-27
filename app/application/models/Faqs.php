<?php

class Application_Model_Faqs extends GP_Application_Model
{
	public $faq_id;
	public $lang_id;
	public $faq_question;
	public $faq_answer;
	public $position;
	protected $lang;
	
	protected $_dbTableModelName = 'Application_Model_DbTable_Faqs';
	
	
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
	
	public function deleteFaq($lang)
	{
		$this->getDbTable()->deleteFaq($lang);
	}
	
	public function prepareFormArray()
	{
		$data = array(
			'faq_id' => $this->_faq_id,
			'faq_lang' => $this->_faq_lang,
			'faq_question' => $this->_faq_question,
			'faq_answer' => $this->_faq_answer
		);
		
		return $data;
	}
	
	public function positionLast()
	{
		if ($this->lang_id != null)
		{
			$last = $this->getDbTable()->findLastQuestion($this->lang_id);
			if ($last)
				$this->position = $last->position++;
			else 
				$this->position = 1; 
		}
		
		return $this;
	}
	
	public function getFaqLength($lang)
	{
		return $this->getDbTable()->getStringLength($lang);
	}

}

