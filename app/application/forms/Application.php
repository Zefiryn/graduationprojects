<?php

class Application_Form_Application extends Zefir_Form
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
		$this->setName('PageForm');
		$this->setAction('/applications/new');
		$this->setTranslator(Zend_Registry::get('Zend_Translate'));
		
		$this->addElementPrefixPath('GP_Decorator', 'GP/Form/Decorator', 'decorator');
		$this->addPrefixPath('GP_Decorator', 'GP/Form/Decorator', 'decorator');

		$country = array('pl' => 'Poland', 'sk' => 'Slovakia', 'cs' => 'Czech Republic');
		$element = $this->createElement('select', 'country');
		$element->setAttribs(array('class' => 'width1', 'size' => 1))
				->setLabel('country')
				->setDecorators($this->_getStandardDecorators())
				->setMultiOptions($country)
				->setRequired(TRUE);	
		$this->addElement($element);

		$element = $this->createElement('text', 'user_name');
		$element->setAttribs(array('class' => 'width2'))
				->setLabel('user_name')
				->setDecorators($this->_getZefirDecorators())
				->setRequired(TRUE)
				->addValidators(array(
						new Zend_Validate_Regex('/^['.$L.'\- ]+$/')
					));	
		$this->addElement($element);
		
		$element = $this->createElement('text', 'user_surname');
		$element->setAttribs(array('class' => 'width2'))
				->setLabel('user_surname')
				->setDecorators($this->_getZefirDecorators())
				->setRequired(TRUE)
				->addValidators(array(
						new Zend_Validate_Regex('/^['.$L.'\- ]+$/')
					));	
		$this->addElement($element);
		
		$element = $this->createElement('text', 'nick');
		$element->setAttribs(array('class' => 'width1'))
				->setLabel('nick')
				->setDecorators($this->_getZefirDecorators())
				->setRequired(TRUE)
				->addValidators(array(
						new Zend_Validate_Regex('/^['.$L.'\- ]+$/')
					));	
		$this->addElement($element);

		$element = $this->createElement('password', 'password');
		$element->setAttribs(array('class' => 'width1'))
				->setLabel('password')
				->setDecorators($this->_getStandardDecorators())
				->setRequired(TRUE)
				->addValidators(array(
					));	
		$this->addElement($element);
		
		$element = $this->createElement('password', 'password_check');
		$element->setAttribs(array('class' => 'width1'))
				->setLabel('password_repeat')
				->setDecorators($this->_getStandardDecorators())
				->setRequired(TRUE)
				->addValidators(array(
						new Zefir_Validate_IdenticalField('password')
					));	
		$this->addElement($element);
		
		$element = $this->createElement('text', 'address');
		$element->setAttribs(array('class' => 'width2'))
				->setLabel('address')
				->setDecorators($this->_getZefirDecorators())
				->setRequired(TRUE)
				->addValidators(array(
						new Zend_Validate_Regex('/^['.$L.$N.$S.' ]+$/')
					));	
		$this->addElement($element);
		
		$element = $this->createElement('text', 'phone');
		$element->setAttribs(array('class' => 'width1'))
				->setLabel('phone')
				->setDescription('phone_description')
				->setDecorators($this->_getZefirDecorators())
				->setRequired(TRUE)
				->addValidators(array(
						new Zend_Validate_Regex('/^\+[0-9]{2,3}( )?[0-9]{4,9}$/')
					));	
		$this->addElement($element);
		
		$element = $this->createElement('text', 'email');
		$element->setAttribs(array('class' => 'width1'))
				->setLabel('email')
				->setDecorators($this->_getZefirDecorators())
				->setRequired(TRUE)
				->addValidators(array(
						new Zend_Validate_EmailAddress()
					));	
		$this->addElement($element);
		
		$element = $this->createElement('checkbox', 'show_email');
		$element->setAttribs(array('class' => 'checkbox'))
				->setLabel('show_email', array('tag' => 'label'))
				->setDecorators($this->_getStandardDecorators())
				->setRequired(FALSE)
				->addValidators(array(
						
					));	
		$this->addElement($element);

		$school = new Application_Model_Schools();
		$element = $this->createElement('select', 'school');
		$element->setAttribs(array('class' => 'width2', 'size' => 1))
				->setLabel('school')
				->setDecorators($this->_getStandardDecorators())
				->setMultiOptions($school->getSchools())
				->setRequired(FALSE)
				->addValidators(array(
					new Zend_Validate_Digits()
				));
		$this->addElement($element);
		
		$element = $this->createElement('text', 'new_school');
		$element->setAttribs(array('class' => 'width2'))
				->setDecorators($this->_getZefirDecorators(FALSE))
				->setAllowEmpty(FALSE)
				->addValidators(array(
					new Zefir_Validate_NotEmptyCombo('school'),
					new Zend_Validate_Regex('/^['.$L.$N.$S.'\ ]*$/')
				));
		$this->addElement($element);
		
		$element = $this->createElement('text', 'department');
		$element->setAttribs(array('class' => 'width2'))
				->setLabel('department')
				->setDecorators($this->_getZefirDecorators())
				->setRequired(TRUE)
				->addValidators(array(
					new Zend_Validate_Regex('/^['.$L.$N.$S.'\ ]*$/')
				));
		$this->addElement($element);
		
		$degree = new Application_Model_Degrees();
		$element = $this->createElement('select', 'degree');
		$element->setAttribs(array('class' => 'width1'))
				->setLabel('degree')
				->setMultiOptions($degree->getDegrees())
				->setDecorators($this->_getStandardDecorators())
				->setRequired(TRUE)
				->addValidators(array(
					new Zend_Validate_NotEmpty(Zend_Validate_NotEmpty::ZERO),
					new Zend_Validate_Digits()
				));
		$this->addElement($element);
		
		$element = $this->createElement('text', 'work_subject');
		$element->setAttribs(array('class' => 'width2'))
				->setLabel('work_subject')
				->setDecorators($this->_getZefirDecorators())
				->setRequired(TRUE)
				->addValidators(array(
					new Zend_Validate_Regex('/^['.$L.$N.$S.'\ ]*$/')
				));
		$this->addElement($element);
		
		$work_type = new Application_Model_WorkTypes();
		$element = $this->createElement('select', 'work_type');
		$element->setAttribs(array('class' => 'width1'))
				->setLabel('work_type')
				->setMultiOptions($work_type->getWorkTypes())
				->setDecorators($this->_getStandardDecorators())
				->setRequired(TRUE)
				->addValidators(array(
					new Zend_Validate_NotEmpty(Zend_Validate_NotEmpty::ZERO),
					new Zend_Validate_Digits()
				));
		$this->addElement($element);
		
		$element = $this->createElement('textarea', 'work_desc');
		$element->setAttribs(array('class' => 'desc'))
				->setLabel('work_desc')
				->setDescription('work_desc_count')
				->setDecorators($this->_getZefirDecorators())
				->setRequired(TRUE)
				->addValidators(array(
					new Zend_Validate_Regex('/^['.$L.$N.$S.$E.$B.' ]+$/')
				));
		$this->addElement($element);
		
		$element = $this->createElement('text', 'supervisor_degree');
		$element->setAttribs(array('class' => 'width1'))
				->setLabel('supervisor_degree')
				->setDecorators($this->_getZefirDecorators())
				->setRequired(TRUE);
		$this->addElement($element);
		
		$element = $this->createElement('text', 'supervisor');
		$element->setAttribs(array('class' => 'width2'))
				->setLabel('supervisor')
				->setDecorators($this->_getZefirDecorators())
				->setRequired(TRUE);
		$this->addElement($element);
		
		$element = $this->createElement('text', 'graduation_time');
		$element->setAttribs(array('class' => 'width1 date'))
				->setLabel('graduation_time')
				->setDecorators($this->_getZefirDecorators())
				->setRequired(TRUE)
				->addValidators(array(
					new Zefir_Validate_DatePeriod('1259622000', '1291071600')
				));
		$this->addElement($element);
		
		
		/**
		 * SUBMIT
		 */
	
		
		$this->addElement('hash', 'csrf', array(
			'ignore' => true,
			'decorators' => array(	array('ViewHelper'),
									array('ErrorMsg'))	
		));		 

		$submit = $this->createElement('submit', 'submit', array(
			'ignore' => true,
			'label' => 'application_submit',
			'attribs' => array('class' => 'submit')
		));		 
		$submit->setDecorators(array(
							array('TextField'),
							array('HtmlTag', array('tag' => 'p', 'class' => 'center'))
            				));
      $this->addElement($submit);
    }


}

