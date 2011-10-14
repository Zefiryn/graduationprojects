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
        
    	
        $paragraph = $this->createElement('hidden', 'paragraph_id');
        $paragraph->setDecorators(array('ViewHelper'));
        $this->addElement($paragraph);
        
        $paragraph = $this->createElement('hidden', 'lang_id');
        $paragraph->setDecorators(array('ViewHelper'));
        $this->addElement($paragraph);
        
        $paragraph = $this->createElement('hidden', 'paragraph_no');
        $paragraph->setDecorators(array('ViewHelper'));
        $this->addElement($paragraph);
        
        $paragraph = $this->createElement('textarea', 'paragraph_text');
        $paragraph->setLabel('paragraph_text')
        		->setAttribs(array('class' => 'width1'))
        		->setDecorators(array(
					array('TextField'),
					array('ErrorMsg', array('image' => FALSE, 'placement' => 'append')),
					array('MyLabel', array('placement' => 'prepend')),
					array('UnderDescription', array('class' => 'description', 'placement' => 'append')))
				)
        		->setRequired()
        		->addValidators(array(
        			new Zend_Validate_Regex('/^['.$L.$N.$S.$E.$B.'\ ]*$/')
        		));
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

