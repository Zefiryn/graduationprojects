<?php

class Application_Form_News_File extends Zefir_Form
{

    public function init()
    {
        $L = $this->_regex['L'];
    	$N = $this->_regex['N'];
    	$S = $this->_regex['S'];
    	$E = $this->_regex['E'];
    	$B = $this->_regex['B'];
        parent::init();
        $this->addElementPrefixPath('GP_Decorator', 'GP/Form/Decorator', 'decorator');
		$this->addPrefixPath('GP_Decorator', 'GP/Form/Decorator', 'decorator');
        $this->setName('FileForm');
		$this->setTranslator(Zend_Registry::get('Zend_Translate'));
		$this->setAttrib('enctype', 'multipart/form-data');
		$this->setAction('/news/upload');
		
		$options = Zend_Registry::get('options');
		$element = new Zend_Form_Element_File('file');
		$element->setDestination(APPLICATION_PATH.'/../public'.$options['upload']['cache'])
				->setAttribs(array('class' => 'file'))
				->setRequired(FALSE)
				->setAllowEmpty(TRUE)
				->addValidators(array(
					array('Extension', true, array(false, 'jpg,png,jpeg')),
					array('MimeType', true, array(false, 'image')),
					
				))
				->setDecorators(array(
					array('File'),
					array('ErrorMsg'),
					array('UnderDescription', array('class' => 'description', 'placement' => 'prepend')),
					array('MyLabel', array('placement' => 'prepend'))
				));
		$this->addElement($element);
		
		$element = $this->createElement('hidden', 'news_files');
		$element->setAttribs(array('class' => 'width1'))->setDecorators(array('ViewHelper'));	
		$this->addElement($element);
		
		$submit = $this->createElement('submit', 'submit', array(
			'ignore' => true,
			'label' => 'upload',
			'class' => 'submit prefered'
		));	 
		$submit->setDecorators(array(
							array('ViewHelper')
            				));
            				
      	$this->addElement($submit);
		$this->addDisplayGroup(array('submit'), 'submitFields')
        ->setDisplayGroupDecorators(array(
						'FormElements', 
						array('Fieldset', array('class' => 'upload'))
			));
    }


}

