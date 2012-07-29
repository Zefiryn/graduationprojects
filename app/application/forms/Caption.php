<?php
/**
 * @package Application_Form_Caption
 */
/**
 * Declaration of the new caption form
 * @author zefiryn
 * @since Nov 2011
 */

class Application_Form_Caption extends Zefir_Form
{

	/**
	 * Inital; set fields, decorators and validators
	 * @access public
	 * @return void
	 */
	public function init()
	{
		$L = $this->_regex['L'];
		$N = $this->_regex['N'];
		$S = $this->_regex['S'];
		$E = $this->_regex['E'];
		$B = $this->_regex['B'];
		parent::init();

		$this->setMethod('post');
		$this->setName('PageForm');
		$this->setTranslator(Zend_Registry::get('Zend_Translate'));

		$element = $this->createElement('hidden', 'caption_id');
		$element->setDecorators(array('ViewHelper'));
		$this->addElement($element);

		$element = $this->createElement('text', 'name');
		$element->setAttribs(array('class' => 'width1'))
		->setLabel('caption_name')
		->setDecorators($this->_getStandardDecorators())
		->setRequired(TRUE)
		->addValidators(array(
		//new Zend_Validate_Regex('/^['.$L.$N.$S.'\ ]*$/'),
		new Zend_Validate_StringLength(array('min' => 0, 'max' => 60))
		));
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

