<?php

class Application_Form_Edition extends Zefir_Form
{
	protected $_type;

	public function __construct($type)
	{
		$this->_type = $type;
		parent::__construct();
	}

	public function init()
	{
		$L = $this->_regex['L'];
		$N = $this->_regex['N'];
		$S = $this->_regex['S'];
		$E = $this->_regex['E'];
		$B = $this->_regex['B'];
		parent::init();

		$this->setMethod('post');
		$this->setName('EditionForm');
		$this->setTranslator(Zend_Registry::get('Zend_Translate'));

		$this->addElementPrefixPath('GP_Decorator', 'GP/Form/Decorator', 'decorator');
		$this->addPrefixPath('GP_Decorator', 'GP/Form/Decorator', 'decorator');

		$element = $this->createElement('hidden', 'edition_id');
		$this->addElement($element);

		$element = $this->createElement('text', 'edition_name');
		$element->setAttribs(array('class' => 'width2'))
		->setLabel('edition_name')
		->setDecorators($this->_getZefirDecorators())
		->setRequired(TRUE)
		->addValidators(array(
		new Zend_Validate_Regex('/^[0-9]{4}\/[0-9]{4}$/'),
		new Zend_Validate_StringLength(array('min' => 0, 'max' => 10)),
		new Zefir_Validate_Unique(array(
        							'table' => 'editions',
        							'field' => 'edition_name',
									'id' => 'edition_id'))
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