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
	public $fileOrder;
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
		$element = $this->createElement('hidden', 'edition_id');
		$element->setDecorators(array('ViewHelper'));	
		$this->addElement($element);
		
		$element = $this->createElement('hidden', 'application_id');
		$element->setDecorators(array('ViewHelper'));	
		$this->addElement($element);
		
		if ($this->_type != 'new')
		{
			$element = $this->createElement('hidden', 'user_id');
			$element->setDecorators(array('ViewHelper'));	
			$this->addElement($element);
		}
		
		$country = array('pl' => 'Poland', 'sk' => 'Slovakia', 'cs' => 'Czech Republic', 'hu' => 'Hungary');
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
			$userSubForm->removeElement('csrf');
			$userSubForm->removeElement('role');
			$this->addSubForm($userSubForm, 'user');
		}

		$element = $this->createElement('text', 'school');
		$element->setAttribs(array('class' => 'width1'))
				->setLabel('school')
				->setDecorators($this->_getZefirDecorators())
				->setDescription('school_suggestion')
				->setRequired(TRUE)
				->addValidators(array(
					new Zend_Validate_Regex('/^['.$L.$N.$S.'\ ]*$/'),
					new Zend_Validate_StringLength(array('min' => 0, 'max' => 60))
				));
		$this->addElement($element);
		
		$element = $this->createElement('text', 'department');
		$element->setAttribs(array('class' => 'width1'))
				->setLabel('department')
				->setDecorators($this->_getZefirDecorators())
				->setRequired(TRUE)
				->addValidators(array(
					new Zend_Validate_Regex('/^['.$L.$N.$S.'\ ]*$/'),
					new Zend_Validate_StringLength(array('min' => 3, 'max' => 60))
				));
		$this->addElement($element);
		
		$degree = new Application_Model_Degrees();
		$element = $this->createElement('select', 'degree_id');
		$element->setAttribs(array('class' => 'width1'))
				->setLabel('degree')
				->setMultiOptions($degree->getDegreesList())
				->setDecorators($this->_getStandardDecorators())
				->setRequired(TRUE)
				->addValidators(array(
					new Zend_Validate_NotEmpty(Zend_Validate_NotEmpty::ZERO),
					new Zend_Validate_Digits()
				));
		$this->addElement($element);
		
		$element = $this->createElement('text', 'work_subject');
		$element->setAttribs(array('class' => 'width1'))
				->setLabel('work_subject')
				->setDecorators($this->_getZefirDecorators())
				->setRequired(TRUE)
				->addValidators(array(
					new Zend_Validate_Regex('/^['.$L.$N.$S.'\ ]*$/'),
					new Zend_Validate_StringLength(array('min' => 3, 'max' => 300))
				));
		$this->addElement($element);
		
		$work_type = new Application_Model_WorkTypes();
		$element = $this->createElement('select', 'work_type_id');
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
				->addFilter('HtmlEntities')
				->addValidators(array(
					new Zend_Validate_Regex('/^['.$L.$N.$S.$E.$B.' ]+$/'),
					new Zend_Validate_StringLength(array('max'=>2100, 'encoding' => 'utf8'))
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
		$element->setAttribs(array('class' => 'width1'))
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
					new Zefir_Validate_DatePeriod($appSettings->work_start_date, $appSettings->work_end_date)
				));
		$this->addElement($element);
		
		$element = $this->createElement('checkbox', 'personal_data_agreement');
		$element->setAttribs(array('class' => 'checkbox'))
				->setLabel('personal_data_agreement', array('tag' => 'label'))
				->setDecorators(array(
						array('ViewHelper'),
						array('MyLabel', array('placement' => 'append')),
						array('ErrorMsg', array('image' => FALSE)),
				))
				->setRequired(TRUE)
				->addValidators(array(
						new Zend_Validate_NotEmpty(Zend_Validate_NotEmpty::ZERO)
				));
		$this->addElement($element);
		
		/**
		 * SUBMIT
		 */
	
		for ($i = 1; $i <= $appSettings->max_files; $i++)
		{
			$subForm = new Application_Form_Application_File($i, $this->_type);
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

