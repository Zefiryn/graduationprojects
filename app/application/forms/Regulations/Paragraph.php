<?php

class Application_Form_Regulations_Paragraph extends Zefir_Form
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
        $this->setDecorators(array('FormElements'));
    	
        $paragraph = $this->createElement('hidden', 'paragraph_id');
        $paragraph->setDecorators(array('ViewHelper'));
        $this->addElement($paragraph);
        
        $paragraph = $this->createElement('hidden', 'lang_id');
        $paragraph->setDecorators(array('ViewHelper'));
        $this->addElement($paragraph);
        
        $paragraph = $this->createElement('text', 'paragraph_no');
        $paragraph->setLabel('paragraph_no')
        		->setAttribs(array('class' => 'width3'))
        		->setDecorators($this->_getZefirDecorators(TRUE))
        		->addValidators(array(
        			new Zend_Validate_Digits()
        		));
        		
        $this->addElement($paragraph);
        
        $paragraph = $this->createElement('textarea', 'paragraph_text');
        $paragraph->setLabel('paragraph_text')
        		->setAttribs(array('class' => 'width1'))
        		->setDecorators($this->_getZefirDecorators())
        		->addValidators(array(
        			new Zend_Validate_Regex('/^['.$L.$N.$S.$E.$B.'\ ]*$/')
        		));
        $this->addElement($paragraph);
        
        $paragraph = $this->createElement('checkbox', 'paragraph_remove');
        $paragraph->setLabel('paragraph_remove')
        	->setAttribs(array('class' => 'checkbox'))
        	->setValue('Remove paragraph')
        	->setDecorators(array(
				array('ViewHelper'),
				array('ErrorMsg'),
				array('MyLabel', array('placement' => 'prepend', 'class' => 'label checkbox'))
				)        			
        	);
        $this->addElement($paragraph);
        
        $this->_createCsrfElement();
        $this->_createStandardSubmit('regulation_submit');
        $this->addDisplayGroup(array('leave', 'submit'), 'submitFields')
        ->setDisplayGroupDecorators(array(
						'FormElements', 
						array('Fieldset', array('class' => 'submit'))
			));;
    }


}

