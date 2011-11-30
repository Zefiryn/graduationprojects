<?php

class Application_Form_News_Detail extends Zefir_Form
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


		$element= $this->createElement('hidden', 'news_id');
		$element->setDecorators(array('ViewHelper'));
		$this->addElement($element);

		$element= $this->createElement('hidden', 'lang_id');
		$element->setDecorators(array('ViewHelper'));
		$this->addElement($element);

		$element = $this->createElement('text', 'news_title');
		$element->setLabel('news_title')
		->setAttribs(array('class' => 'width1'))
		->setDecorators($this->_getZefirDecorators(TRUE))
		->addValidators(array(
		new Zend_Validate_Regex('/^['.$L.$N.$S.$E.$B.'\ ]*$/'),
		new Zend_Validate_StringLength(array('min' => 0,'max' => 300))
		));
		$this->addElement($element);

		$element = $this->createElement('textarea', 'news_lead');
		$element->setLabel('news_lead')
		->setAttribs(array('class' => 'width1'))
		->setDecorators($this->_getZefirDecorators())
		->addValidators(array(
		new Zend_Validate_Regex('/^['.$L.$N.$S.$E.$B.'\ ]*$/')
		));
		$this->addElement($element);

		$element = $this->createElement('textarea', 'news_text');
		$element->setLabel('news_text')
		->setAttribs(array('class' => 'width1'))
		->setDecorators($this->_getZefirDecorators())
		->addValidators(array(
		new Zend_Validate_Regex('/^['.$L.$N.$S.$E.$B.'\ ]*$/')
		));
		$this->addElement($element);

		$this->_createCsrfElement();
		$this->_createStandardSubmit('news_submit');
		$this->addDisplayGroup(array('leave', 'submit'), 'submitFields')
		->setDisplayGroupDecorators(array(
						'FormElements', 
		array('Fieldset', array('class' => 'submit'))
		));
	}


}

