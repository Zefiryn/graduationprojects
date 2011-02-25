<?php

class Application_Form_Application extends Zefir_Form
{

    public function init()
    {
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
						new Zend_Validate_Regex('/^['.$this->_regex['L'].'\- ]+$/')
					));	
		$this->addElement($element);
		
		$element = $this->createElement('text', 'user_surname');
		$element->setAttribs(array('class' => 'width2'))
				->setLabel('user_surname')
				->setDecorators($this->_getZefirDecorators())
				->setRequired(TRUE)
				->addValidators(array(
						new Zend_Validate_Regex('/^['.$this->_regex['L'].'\- ]+$/')
					));	
		$this->addElement($element);
		
		$element = $this->createElement('text', 'nick');
		$element->setAttribs(array('class' => 'width2'))
				->setLabel('nick')
				->setDecorators($this->_getZefirDecorators())
				->setRequired(TRUE)
				->addValidators(array(
						new Zend_Validate_Regex('/^['.$this->_regex['L'].'\- ]+$/')
					));	
		$this->addElement($element);
		
		$element = $this->createElement('text', 'address');
		$element->setAttribs(array('class' => 'width2'))
				->setLabel('address')
				->setDecorators($this->_getZefirDecorators())
				->setRequired(TRUE)
				->addValidators(array(
						new Zend_Validate_Regex('/^['.$this->_regex['L'].'\- ]+$/')
					));	
		$this->addElement($element);
		
		$element = $this->createElement('text', 'phone');
		$element->setAttribs(array('class' => 'width1'))
				->setLabel('phone')
				->setDescription('phone_description')
				->setDecorators($this->_getZefirDecorators())
				->setRequired(TRUE)
				->addValidators(array(
						new Zend_Validate_Regex('/^['.$this->_regex['L'].'\- ]+$/')
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
		
		$element = $this->createElement('text', 'email_repeat');
		$element->setAttribs(array('class' => 'width1'))
				->setLabel('email_repeat')
				->setDecorators($this->_getZefirDecorators())
				->setRequired(TRUE)
				->addValidators(array(
						
					));	
		$this->addElement($element);
		
		
		$element = $this->createElement('checkbox', 'show_email');
		$element->setAttribs(array('class' => 'width1'))
				->setLabel('show_email')
				->setDecorators($this->_getZefirDecorators(FALSE))
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
				->setRequired(FALSE);	
		$this->addElement($element);
		
		$element = $this->createElement('text', 'new_school');
		$element->setAttribs(array('class' => 'width2'))
				->setDecorators($this->_getZefirDecorators())
				->setRequired(FALSE);	
		$this->addElement($element);
		
		$element = $this->createElement('text', 'department');
		$element->setAttribs(array('class' => 'width2'))
				->setLabel('department')
				->setDecorators($this->_getZefirDecorators())
				->setRequired(TRUE);
		$this->addElement($element);
		
		$degree = new Application_Model_Degrees();
		$element = $this->createElement('select', 'degree');
		$element->setAttribs(array('class' => 'width1'))
				->setLabel('degree')
				->setMultiOptions($degree->getDegrees())
				->setDecorators($this->_getStandardDecorators())
				->setRequired(TRUE);
		$this->addElement($element);
		
		$element = $this->createElement('text', 'work_subject');
		$element->setAttribs(array('class' => 'width2'))
				->setLabel('work_subject')
				->setDecorators($this->_getZefirDecorators())
				->setRequired(TRUE);
		$this->addElement($element);
		
		$element = $this->createElement('select', 'work_type');
		$element->setAttribs(array('class' => 'width1'))
				->setLabel('work_type')
				->setDecorators($this->_getStandardDecorators())
				->setRequired(TRUE);
		$this->addElement($element);
		
		$element = $this->createElement('textarea', 'work_desc');
		$element->setAttribs(array('class' => 'desc'))
				->setLabel('work_desc')
				->setDescription('work_desc_count')
				->setDecorators($this->_getZefirDecorators())
				->setRequired(TRUE);
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
				->setRequired(TRUE);
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

