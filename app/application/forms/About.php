<?php
/**
 * @package Application_Form_About
 */
/**
 * Declaration of the form which allowes to edit about page
 * @author zefiryn
 * @since Mar 2011
 */

class Application_Form_About extends Zefir_Form
{
	public function init()
    {
    	$L = $this->_regex['L'];
    	$N = $this->_regex['N'];
    	$S = $this->_regex['S'];
    	$E = $this->_regex['E'];
    	$B = $this->_regex['B'];
        parent::init();
        
        $this->setMethod('post');
		$this->setName('AboutForm');
		$this->setAction('/about/edit');
		$this->setTranslator(Zend_Registry::get('Zend_Translate'));
		
		
		$this->addElementPrefixPath('GP_Decorator', 'GP/Form/Decorator', 'decorator');
		$this->addPrefixPath('GP_Decorator', 'GP/Form/Decorator', 'decorator');
		
		$t = $this->getTranslator();
		
		$element = $this->createElement('hidden', 'about_id');
		$element->setValue($t->getLocale());	
		$this->addElement($element);
		
		$element = $this->createElement('text', 'about_title');
		$element->setAttribs(array('class' => 'width1'))
				->setLabel('about_title')
				->setDecorators($this->_getZefirDecorators())
				->setRequired()
				->addValidators(array(
					new Zend_Validate_Regex('/^['.$L.$N.$S.'\ ]*$/'),
					new Zend_Validate_StringLength(array('min' => 1, 'max' => 300))
				));
		$this->addElement($element);
		
		$element = $this->createElement('textarea', 'about_text');
		$element->setAttribs(array('class' => 'width1'))
				->setLabel('about')
				->setDecorators(array(
						array('TextField'),
						array('MyLabel', array('placement' => 'prepend')),
						array('ErrorMsg', array('image' => FALSE)),
						array('UnderDescription', array('class' => 'description', 'placement' => 'append')))
				)
				->setRequired(TRUE)
				->addValidators(array(
						new Zend_Validate_Regex('/^['.$L.$N.$S.$E.$B.']+$/')
					));	
		$this->addElement($element);
		
		$element = $this->createElement('hidden', 'lang_id');
		$element->setValue($t->getLocale());	
		$this->addElement($element);
		
		$this->_createCsrfElement();
		$this->_createStandardSubmit('submit');
		$this->addDisplayGroup(array('leave', 'submit'), 'submitFields')
        	->setDisplayGroupDecorators(array(
						'FormElements', 
						array('Fieldset', array('class' => 'submit'))
			));
		
		
    }
}