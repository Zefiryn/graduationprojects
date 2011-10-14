<?php

class Application_Form_Faq_Question extends Zefir_Form
{

    public function init()
    {
    	$L = $this->_regex['L'];
    	$N = $this->_regex['N'];
    	$S = $this->_regex['S'];
    	$E = $this->_regex['E'];
    	$B = $this->_regex['B'];
        parent::init();
        $this->addElementPrefixPath('GP_Decorator', 'GP/Form/Decorator', 'decorator');
		$this->addPrefixPath('GP_Decorator', 'GP/Form/Decorator', 'decorator');
    	
        $question = $this->createElement('hidden', 'faq_id');
        $question->setDecorators(array('ViewHelper'));
        $this->addElement($question);
        
        $question = $this->createElement('hidden', 'lang_id');
        $question->setDecorators(array('ViewHelper'));
        $this->addElement($question);
        
        $question = $this->createElement('text', 'faq_question');
        $question->setLabel('faq_question')
        		->setAttribs(array('class' => 'width2'))
        		->setDecorators($this->_getZefirDecorators(TRUE))
        		->setRequired(TRUE)
        		->addValidators(array(
        			new Zend_Validate_StringLength(array('min' => 0,'max' => 300)),
        			new Zend_Validate_Regex('/^['.$L.$N.$S.$E.$B.'\ ]*$/')
        		));
        		
        $this->addElement($question);
        
        $question = $this->createElement('textarea', 'faq_answer');
        $question->setLabel('faq_answer')
        		->setAttribs(array('class' => 'width1'))
        		->setDecorators($this->_getZefirDecorators())
        		->setRequired(TRUE)
        		->addValidators(array(
        			new Zend_Validate_Regex('/^['.$L.$N.$S.$E.$B.'\ ]*$/')
        		));
        $this->addElement($question);
        
        $this->_createCsrfElement();
        $this->_createStandardSubmit('faq_submit');
        $this->addDisplayGroup(array('leave', 'submit'), 'submitFields')
        ->setDisplayGroupDecorators(array(
						'FormElements', 
						array('Fieldset', array('class' => 'submit'))
			));;
    }

}

