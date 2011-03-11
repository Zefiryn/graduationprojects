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
        $edition = Zend_Registry::get('edition');
    	
        $regulations = new Application_Model_Regualtions();
        $regulationsData = $regulations->getRegulations($edition);
        
        foreach($regulationsData as $regulation)
        {
        	$paragraph = $this->createElement('text', 'paragraph_no_'.$regulation->_paragraph_id);
        	$paragraph->setLabel('paragraph_no')
        			->setAttribs(array('class' => 'width3'))
        			->setValue($regulation->_paragraph_no)
        			->setDecorators($this->_getZefirDecorators(TRUE));
        	$this->addElement($paragraph);
        	
        	$paragraph = $this->createElement('textarea', 'paragraph_text_'.$regulation->_paragraph_id);
        	$paragraph->setLabel('paragraph_text')
        			->setAttribs(array('class' => 'width1'))
        			->setValue($regulation->_paragraph_text)
        			->setDecorators($this->_getZefirDecorators(TRUE));
        	$this->addElement($paragraph);
        	
        	$paragraph = $this->createElement('checkbox', 'paragraph_remove_'.$regulation->_paragraph_id);
        	$paragraph->setLabel('paragraph_remove')
        		->setAttribs(array('class' => 'checkbox'))
        		->setValue('Remove paragraph')
        		->setDecorators(array(
					array('ViewHelper'),
					array('ErrorMsg'),
					array('MyLabel', array('placement' => 'prepend', 'class' => 'label checkbox'))
					)        			
        		);
        	$this->addElement($paragraph);
        
        }
        
        for ($i=1; $i <= $this->_new; $i++)
        {
	        $paragraph = $this->createElement('text', 'new_paragraph_no_'.$i);
	        $paragraph->setLabel('paragraph_no')
	        		->setAttribs(array('class' => 'width3'))
	        		->setDecorators($this->_getZefirDecorators(TRUE));
	        $this->addElement($paragraph);
	        	
	        $paragraph = $this->createElement('textarea', 'new_paragraph_text_'.$i);
	        $paragraph->setLabel('paragraph_text')
	        		->setAttribs(array('class' => 'width1'))
	        		->setDecorators($this->_getZefirDecorators(TRUE));
	        $this->addElement($paragraph);
        }
        
        $this->_createStandardSubmit('regulation_submit');
        $this->addDisplayGroup(array('leave', 'submit'), 'submitFields')
        ->setDisplayGroupDecorators(array(
						'FormElements', 
						array('Fieldset', array('class' => 'submit'))
			));;
    }


}

