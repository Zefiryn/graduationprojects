<?php

class Application_Form_Application_File extends Zefir_Form_SubForm
{
	public function init()
	{
		$L = $this->_regex['L'];
		$N = $this->_regex['N'];
		$S = $this->_regex['S'];
		$E = $this->_regex['E'];
		$B = $this->_regex['B'];
		$number = $this->_number;
		$type = $this->_type;
		parent::init();
		
		$this->setName('FileForm');
		$this->setTranslator(Zend_Registry::get('Zend_Translate'));
		
		$this->addElementPrefixPath('GP_Decorator', 'GP/Form/Decorator', 'decorator');
		$this->addPrefixPath('GP_Decorator', 'GP/Form/Decorator', 'decorator');
		
		
	}
}