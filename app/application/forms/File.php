<?php
/**
 * @package Application_Form_File
 */
/**
 * Declaration of the application file form
 * @author zefiryn
 * @since Feb 2011
 */

class Application_Form_File extends Zefir_Form_SubForm
{
	public function __construct($number, $type)
	{
		$this->init($number, $type);
	}
	
    public function init($number, $type)
    {
    	$L = $this->_regex['L'];
    	$N = $this->_regex['N'];
    	$S = $this->_regex['S'];
    	$E = $this->_regex['E'];
    	$B = $this->_regex['B'];
        parent::init();
        
		$this->setName('FileForm');
		$this->setTranslator(Zend_Registry::get('Zend_Translate'));
		
		$this->setDecorators(array(
					'PrepareElements', 
                	array('viewScript', array('viewScript' => 'forms/_fileForm.phtml',
                								'number' => $number)) 
				));
		$this->addElementPrefixPath('GP_Decorator', 'GP/Form/Decorator', 'decorator');
		$this->addPrefixPath('GP_Decorator', 'GP/Form/Decorator', 'decorator');
		
		$appSettings = Zend_Registry::get('appSettings');
		$element = $this->createElement('hidden', 'application_id');	
		$this->addElement($element);
		
		
		
		$options = Zend_Registry::get('options');
		
		$element = $this->createElement('hidden', 'file_'.$number.'Cache', array(
						'decorators' => array('ViewHelper')
		));
		$this->addElement($element);
		
		$element = $this->createElement('hidden', 'file_type', array(
						'decorators' => array('ViewHelper'),
						'value' => $type
		));
		$this->addElement($element);
		
		$element = new Zend_Form_Element_File('file_'.$number);
		$element->setLabel('file')
				->setDestination(APPLICATION_PATH.'/../public'.$options['upload']['cache'])
				->setAttribs(array('class' => 'file'))
				->setRequired(FALSE)
				->addValidators(array(
					array('Extension', true, array(false, 'jpg,png,jpeg')),
					array('MimeType', true, array(false, 'image')),
					array('Size', false, array('min' => 100, 'max' => $appSettings->_max_file_size)),
					array('ImageSize', false, array('minwidth' => 300,
                            						'maxwidth' => 1600,
                            						'minheight' => 300,
                            						'maxheight' => 1200))
				))
				->setDecorators(array(
					array('File'),
					array('ErrorMsg'),
					array('UnderDescription', array('class' => 'description', 'placement' => 'prepend')),
					array('MyLabel', array('placement' => 'prepend'))
				));
		$this->addElement($element);
		
		$element = $this->createElement('text', 'file_annotation');
		$element->setLabel('file_annotation')
				->setAttribs(array('class' => 'width2'))
				->setRequired(FALSE)
				->addValidators(array(
					
				))
				->setDecorators($this->_getZefirDecorators());
		$this->addElement($element);
		
		/**
		 * SUBMIT
		 */
	
    }


}