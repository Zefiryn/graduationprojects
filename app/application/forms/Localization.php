<?php

class Application_Form_Localization extends Zefir_Form
{

	public function init()
    {
    	parent::init();
        $this->addElementPrefixPath('GP_Decorator', 'GP/Form/Decorator', 'decorator');
		$this->addPrefixPath('GP_Decorator', 'GP/Form/Decorator', 'decorator');
    	
        $this->_createCsrfElement();
        
        $name = $this->createElement('text', 'text');
		$name->setAttribs(array('size'=> 55,
								'maxlength' => 16,
								'class' => 'width1'))
				->setLabel('translation')
				->setDecorators(
					array(
						array('TextField'),
						array('ErrorMsg', array('image' => FALSE)),
						array('UnderDescription', array('class' => 'description', 'placement' => 'prepend')),
						array('MyLabel', array('placement' => 'prepend'))
					))
				->addValidators(array(
						//new Zend_Validate_Regex('/^['.$this->_regex['L'].$this->_regex['N'].$this->_regex['S'].' ]+$/')
					));	
		$this->addElement($name);

     	
     	$this->_createStandardSubmit('regulation_submit');
        $this->addDisplayGroup(array('leave', 'submit'), 'submitFields')
        ->setDisplayGroupDecorators(array(
						'FormElements', 
						array('Fieldset', array('class' => 'submit'))
			));
    }


}

