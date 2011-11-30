<?php
class Application_Form_Diploma_Fields extends Zefir_Form_SubForm
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
		$this->setName('DiplomaForm');
		$this->setTranslator(Zend_Registry::get('Zend_Translate'));

		$this->addElementPrefixPath('GP_Decorator', 'GP/Form/Decorator', 'decorator');
		$this->addPrefixPath('GP_Decorator', 'GP/Form/Decorator', 'decorator');


		$element = $this->createElement('hidden', 'lang_id');
		$element->setDecorators(array('ViewHelper'));
		$this->addElement($element);

		$element = $this->createElement('hidden', 'diploma_id');
		$element->setDecorators(array('ViewHelper'));
		$this->addElement($element);

		$element = $this->createElement('text', 'school');
		$element->setAttribs(array('class' => 'width1'))
		->setLabel('school')
		->setDecorators($this->_getStandardDecorators())
		->setRequired(FALSE)
		->addValidators(array(
		new Zend_Validate_Regex('/^['.$L.$N.$S.'\ ]*$/'),
		new Zend_Validate_StringLength(array('min' => 0, 'max' => 200))
		));
		$this->addElement($element);

		$element = $this->createElement('text', 'department');
		$element->setAttribs(array('class' => 'width1'))
		->setLabel('department')
		->setDecorators($this->_getStandardDecorators())
		->setRequired(FALSE)
		->addValidators(array(
		new Zend_Validate_Regex('/^['.$L.$N.$S.'\ ]*$/'),
		new Zend_Validate_StringLength(array('min' => 0, 'max' => 200))
		));
		$this->addElement($element);

		$element = $this->createElement('text', 'work_subject');
		$element->setAttribs(array('class' => 'width1'))
		->setLabel('work_subject')
		->setDecorators($this->_getZefirDecorators())
		->setRequired(FALSE)
		->addValidators(array(
		new Zend_Validate_Regex('/^['.$L.$N.$S.'\ ]*$/'),
		new Zend_Validate_StringLength(array('min' => 3, 'max' => 300))
		));
		$this->addElement($element);

		$element = $this->createElement('textarea', 'work_desc');
		$element->setAttribs(array('class' => 'desc'))
		->setLabel('work_desc')
		->setDescription('work_desc_count')
		->setDecorators($this->_getZefirDecorators())
		->setRequired(FALSE)
		->addValidators(array(
		//new Zend_Validate_Regex('/^['.$L.$N.$S.$E.$B.' ]+$/')
		));
		$this->addElement($element);
	}


}

