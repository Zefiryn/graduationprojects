<?php


class Application_Form_Juror_User extends Zefir_Form
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
		$this->setName('UserForm');
		$this->setTranslator(Zend_Registry::get('Zend_Translate'));
	
		$this->addElementPrefixPath('GP_Decorator', 'GP/Form/Decorator', 'decorator');
		$this->addPrefixPath('GP_Decorator', 'GP/Form/Decorator', 'decorator');
	
		$element = $this->createElement('hidden', 'juror_id')->setDecorators(array('ViewHelper'));
		$this->addElement($element);
	
		$users = new Application_Model_Users();
		$element = $this->createElement('multiCheckbox', 'users');
		foreach ($users->getAll(array('role = "juror"')) as $user)
		{
			$element->addMultiOption($user->user_id, $user->getUserFullName());
		}
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