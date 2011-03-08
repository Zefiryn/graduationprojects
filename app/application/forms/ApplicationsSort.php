<?php
/**
 * @package Application_Form_Application
 */
/**
 * Declaration of the application form
 * @author zefiryn
 * @since Feb 2011
 */

class Application_Form_ApplicationsSort extends Zefir_Form
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
		$this->setName('SortForm');
		$this->setAction('/applications');
		$this->setAttrib('class', 'application_sort');
		$this->setTranslator(Zend_Registry::get('Zend_Translate'));
		
		$this->addElementPrefixPath('GP_Decorator', 'GP/Form/Decorator', 'decorator');
		$this->addPrefixPath('GP_Decorator', 'GP/Form/Decorator', 'decorator');
		
		$edition = new Application_Model_Editions();
		$element = $this->createElement('select', 'edition');
		$element->setAttribs(array('class' => 'width3', 'size' => 1))
				->setLabel('edition_choice')
				->setDecorators(array(
					array('ViewHelper'),
					array('MyLabel', array('placement' => 'prepend', 'tag' => 'span')),
					array('ErrorMsg', array('image' => TRUE)),
					array('Description', array('tag' => 'p', 'class' => 'label', 'placement' => 'prepend'))
				))
				->setMultiOptions($edition->getEditions())
				->setRequired(TRUE);	
		$this->addElement($element);
    }
}
