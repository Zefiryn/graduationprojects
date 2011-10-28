<?php

class Application_Form_News extends Zefir_Form
{
	protected $_languages;
	
    public function init()
    {
    	$lang = new Application_Model_Languages();
    	$this->_languages = $lang->fetchAll(); 
        $L = $this->_regex['L'];
    	$N = $this->_regex['N'];
    	$S = $this->_regex['S'];
    	$E = $this->_regex['E'];
    	$B = $this->_regex['B'];
        parent::init();
        $this->addElementPrefixPath('GP_Decorator', 'GP/Form/Decorator', 'decorator');
		$this->addPrefixPath('GP_Decorator', 'GP/Form/Decorator', 'decorator');
        
        $this->setMethod('post');
		$this->setName('NewsForm');
		$this->setTranslator(Zend_Registry::get('Zend_Translate'));
		$this->setDecorators(array(
			array('ViewScript', array('viewScript' => 'forms/_newsForm.phtml'))
		));
		
		$element = $this->createElement('hidden', 'news_id');	
		$this->addElement($element);
		
		$element = $this->createElement('hidden', 'lang_id');	
		$this->addElement($element);
		
		$element = $this->createElement('hidden', 'files');
		$element->setDecorators(array('ViewHelper'));	
		$this->addElement($element);
		
		$element = $this->createElement('checkbox', 'published');
		$element->setAttribs(array('class' => 'checkbox'))
				->setLabel('publish', array('tag' => 'label'))
				->setDecorators($this->_getStandardDecorators())
				->setRequired(FALSE)
				->addValidators(array(
						new Zend_Validate_Regex('/^0|1$/')
					));	
		$this->addElement($element);
		
		foreach($this->_languages as $lang)
		{
			$subform = new Application_Form_News_Detail();
			$subform->getElement('lang_id')->setValue($lang->lang_id);
			$subform->removeDecorator('form');
			$subform->removeElement('csrf');
			$subform->removeElement('leave');
			$subform->removeElement('submit');
			$subform->removeDisplayGroup('submitFields');
			$subform->setIsArray(true);
			$this->addSubForm($subform, $lang->lang_code);
		}
        $this->_createCsrfElement();	 
		$this->_createStandardSubmit('news_submit');
        $this->addDisplayGroup(array('leave', 'submit'), 'submitFields')
        ->setDisplayGroupDecorators(array(
						'FormElements', 
						array('Fieldset', array('class' => 'submit'))
			));
    }

    public function getLanguages()
    {
    	return $this->_languages;
    }

}

