<?php

class Application_Form_Regulations extends Zefir_Form
{
	protected $_new;
	
	public function __construct($new = 1)
	{
		$this->_new = $new;
		parent::__construct();
	}
	
    public function init()
    {
        parent::init();
        $this->addElementPrefixPath('GP_Decorator', 'GP/Form/Decorator', 'decorator');
		$this->addPrefixPath('GP_Decorator', 'GP/Form/Decorator', 'decorator');
    	
        $this->_createCsrfElement();
                
        $langSession = new Zend_Session_Namespace('lang');
        $lang = new Application_Model_Languages();
        $lang->findLang($langSession->lang);
        
        foreach($lang->regulations as $regulation)
        {
        	$paragraph = new Application_Form_Regulations_Paragraph();
        	$paragraph->removeDecorator('form');		
        	$paragraph->removeElement('csrf');
        	$paragraph->removeDisplayGroup('submitFields');
        	$paragraph->removeElement('submit');
        	$paragraph->removeElement('leave');
        	$paragraph->setIsArray(TRUE);
        	$paragraph->populate($regulation->prepareFormArray());
			$this->addSubForm($paragraph, 'paragraph_'.$regulation->paragraph_id); 
        }
        
        
        for($i= 1; $i <= $this->_new; $i++)
        {
        	$paragraph = new Application_Form_Regulations_Paragraph();
        	$paragraph->removeDecorator('form');		
        	$paragraph->removeElement('csrf');
        	$paragraph->removeElement('paragraph_remove');
        	$paragraph->removeDisplayGroup('submitFields');
        	$paragraph->removeElement('submit');
        	$paragraph->removeElement('leave');
        	$paragraph->setIsArray(TRUE);
        	$paragraph->getElement('lang_id')->setValue($lang->lang_id);
        	$this->addSubForm($paragraph, 'new_paragraph_'.$i);
        }
        
        
        $submit = $this->createElement('submit', 'add_new_paragraph', array(
			'ignore' => true,
			'label' => 'add_new_paragraph',
			'class' => 'submit unprefered right'
		));	 
		$submit->setDecorators(array(
							array('ViewHelper')
            				));
     	$this->addElement($submit);
     	
     	$this->_createCsrfElement();
        $this->_createStandardSubmit('regulation_submit');
        $this->addDisplayGroup(array('leave', 'submit'), 'submitFields')
        ->setDisplayGroupDecorators(array(
						'FormElements', 
						array('Fieldset', array('class' => 'submit'))
			));
    }


}

