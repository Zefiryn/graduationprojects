<?php

class Application_Form_Faq extends Zefir_Form
{

	protected $_new;
	
	public function __construct($new = 1)
	{
		$this->_new = $new;
		parent::__construct();
	}
	
    public function init()
    {
    	parent::init();
        $this->addElementPrefixPath('GP_Decorator', 'GP/Form/Decorator', 'decorator');
		$this->addPrefixPath('GP_Decorator', 'GP/Form/Decorator', 'decorator');
        $edition = Zend_Registry::get('edition');
    	
        $this->_createCsrfElement();
        
        $faq = new Application_Model_Faqs();
        $faqData = $faq->getFaqs();
        
    	foreach($faqData as $faqQuestion)
        {
        	$question = new Application_Form_Faq_Question();
        	$question->removeDecorator('form');		
        	$question->removeElement('csrf');
        	$question->removeDisplayGroup('submitFields');
        	$question->removeElement('submit');
        	$question->removeElement('leave');
        	$question->setIsArray(TRUE);
        	$question->populate($faqQuestion->prepareFormArray());
			$this->addSubForm($question, 'question_'.$faqQuestion->_faq_id); 
        }
        
		$lang = new Zend_Session_Namespace('lang');
        
        for($i= 1; $i <= $this->_new; $i++)
        {
        	$question = new Application_Form_Faq_Question();
        	$question->removeDecorator('form');		
        	$question->removeElement('csrf');
        	$question->removeElement('question_remove');
        	$question->removeDisplayGroup('submitFields');
        	$question->removeElement('submit');
        	$question->removeElement('leave');
        	$question->setIsArray(TRUE);
        	$question->getElement('faq_lang')->setValue($lang->lang);
        	$this->addSubForm($question, 'new_question_'.$i);
        }
        
        $submit = $this->createElement('submit', 'add_new_question', array(
			'ignore' => true,
			'label' => 'add_new_question',
			'class' => 'submit unprefered right'
		));	 
		$submit->setDecorators(array(
							array('ViewHelper')
            				));
     	$this->addElement($submit);
     	
     	$this->_createStandardSubmit('regulation_submit');
        $this->addDisplayGroup(array('leave', 'submit'), 'submitFields')
        ->setDisplayGroupDecorators(array(
						'FormElements', 
						array('Fieldset', array('class' => 'submit'))
			));
    }


}

