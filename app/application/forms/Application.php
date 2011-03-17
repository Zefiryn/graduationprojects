<?php
/**
 * @package Application_Form_Application
 */
/**
 * Declaration of the application form
 * @author zefiryn
 * @since Feb 2011
 */

class Application_Form_Application extends Zefir_Form
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
		$this->setName('PageForm');
		$this->setTranslator(Zend_Registry::get('Zend_Translate'));
		$this->setEnctype(Zend_Form::ENCTYPE_MULTIPART);
		
		$this->addElementPrefixPath('GP_Decorator', 'GP/Form/Decorator', 'decorator');
		$this->addPrefixPath('GP_Decorator', 'GP/Form/Decorator', 'decorator');

		
		$appSettings = Zend_Registry::get('appSettings');
		$element = $this->createElement('hidden', 'edition');
		$element->setDecorators(array('ViewHelper'));	
		$this->addElement($element);
		
		$element = $this->createElement('hidden', 'application_id');
		$element->setDecorators(array('ViewHelper'));	
		$this->addElement($element);
		
		$element = $this->createElement('hidden', 'user');
		$element->setDecorators(array('ViewHelper'));	
		$this->addElement($element);
		
		$country = array('pl' => 'Poland', 'sk' => 'Slovakia', 'cs' => 'Czech Republic');
		$element = $this->createElement('select', 'country');
		$element->setAttribs(array('class' => 'width1', 'size' => 1))
				->setLabel('country')
				->setDecorators($this->_getStandardDecorators())
				->setMultiOptions($country)
				->setRequired(TRUE);	
		$this->addElement($element);

		if ($this->_type == 'new')
		{
			$userSubForm = new Application_Form_User('subform');
			$userSubForm->removeDecorator('form');
			$userSubForm->removeElement('role');
			$this->addSubForm($userSubForm, 'user');
		}

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
					new Zend_Validate_Regex('/^['.$L.$N.$S.'\ ]*$/'),
					new Zend_Validate_StringLength(array('min' => 0, 'max' => 60))
				));
		$this->addElement($element);
		
		$element = $this->createElement('text', 'department');
		$element->setAttribs(array('class' => 'width2'))
				->setLabel('department')
				->setDecorators($this->_getZefirDecorators())
				->setRequired(TRUE)
				->addValidators(array(
					new Zend_Validate_Regex('/^['.$L.$N.$S.'\ ]*$/'),
					new Zend_Validate_StringLength(array('min' => 3, 'max' => 60))
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
					new Zend_Validate_Regex('/^['.$L.$N.$S.'\ ]*$/'),
					new Zend_Validate_StringLength(array('min' => 3, 'max' => 300))
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
				->setRequired(TRUE)
				->addValidators(array(
					new Zend_Validate_Regex('/^['.$L.$S.'\s ]+$/'),
					new Zend_Validate_StringLength(array('min' => 0, 'max' => 15))
				));
		$this->addElement($element);
		
		$element = $this->createElement('text', 'supervisor');
		$element->setAttribs(array('class' => 'width2'))
				->setLabel('supervisor')
				->setDecorators($this->_getZefirDecorators())
				->setRequired(TRUE)
				->addValidators(array(
					new Zend_Validate_Regex('/^['.$L.'\-\s ]+$/'),
					new Zend_Validate_StringLength(array('min' => 0, 'max' => 60))
				));
		$this->addElement($element);
		
		$element = $this->createElement('text', 'graduation_time');
		$element->setAttribs(array('class' => 'width1 date'))
				->setLabel('graduation_time')
				->setDecorators($this->_getZefirDecorators())
				->setRequired(TRUE)
				->addValidators(array(
					new Zefir_Validate_DatePeriod($appSettings->_work_start_date, $appSettings->_work_end_date)
				));
		$this->addElement($element);
		
		$element = $this->createElement('checkbox', 'personal_data_agreement');
		$element->setAttribs(array('class' => 'checkbox'))
				->setLabel('personal_data_agreement', array('tag' => 'label'))
				->setDecorators(array(
						array('ViewHelper'),
						array('MyLabel', array('placement' => 'prepend')),
						array('ErrorMsg', array('image' => FALSE)),
				))
				->setRequired(TRUE)
				->addValidators(array(
						new Zend_Validate_NotEmpty(Zend_Validate_NotEmpty::ZERO)
				));
		$this->addElement($element);
		
		$options = Zend_Registry::get('options');
		$element = new Zend_Form_Element_File('miniature');
		$element->setLabel('miniature')
				->setDescription('miniature_description')
				->setDestination(APPLICATION_PATH.'/../public'.$options['upload']['cache'])
				->setAttribs(array('class' => 'file'))
				->setRequired(TRUE)
				->addValidators(array(
					array('Extension', true, array(false, 'jpg,png,jpeg')),
					array('MimeType', true, array(false, 'image')),
					array('Size', false, array('min' => 100, 'max' => $appSettings->_max_file_size)),
					array('ImageSize', false, array('minwidth' => 800,
                            						'maxwidth' => 800,
                            						'minheight' => 800,
                            						'maxheight' => 800))
				))
				->setDecorators(array(
					array('File'),
					array('ErrorMsg'),
					array('UnderDescription', array('class' => 'description', 'placement' => 'prepend')),
					array('MyLabel', array('placement' => 'prepend'))
				));
		$this->addElement($element);
        
		$element = $this->createElement('hidden', 'miniatureCache', array(
						'decorators' => array('ViewHelper')
		));
		$this->addElement($element);
		
		/**
		 * SUBMIT
		 */
	
		for ($i = 1; $i <= $appSettings->_max_files; $i++)
		{
			$subForm = new Application_Form_File($i, $this->_type);
			$this->addSubForm($subForm, 'file_'.$i);
		}
		
		$this->_createCsrfElement();	 
		$this->_createStandardSubmit('application_submit');
		$this->addDisplayGroup(array('leave', 'submit'), 'submitFields')
        	->setDisplayGroupDecorators(array(
						'FormElements', 
						array('Fieldset', array('class' => 'submit'))
			));
    }


}
