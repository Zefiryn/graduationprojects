<?php
/**
 * @package Application_Form_File
 */
/**
 * Declaration of the application file form
 * @author zefiryn
 * @since Feb 2011
 */

class Application_Form_Application_File extends Zefir_Form_SubForm
{
	protected $_number;
	protected $_type;
	public $order;

	public function __construct($number, $type = 'new')
	{
		$this->_number = $number;
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
		$number = $this->_number;
		$type = $this->_type;
		parent::init();

		$this->setName('FileForm');
		$this->setTranslator(Zend_Registry::get('Zend_Translate'));

		$this->setDecorators(array(

		array('viewScript', array('viewScript' => 'forms/_fileForm.phtml',
                								'number' => $number)) 
		));
		$this->addElementPrefixPath('GP_Decorator', 'GP/Form/Decorator', 'decorator');
		$this->addPrefixPath('GP_Decorator', 'GP/Form/Decorator', 'decorator');

		$appSettings = Zend_Registry::get('appSettings');

		$element = $this->createElement('hidden', 'application_id');
		$element->setDecorators(array('ViewHelper'));
		$this->addElement($element);

		$element = $this->createElement('hidden', 'file_id');
		$element->setDecorators(array('ViewHelper'));
		$this->addElement($element);

		$options = Zend_Registry::get('options');

		$element = $this->createElement('hidden', 'file_'.$number.'Cache', array(
						'decorators' => array('ViewHelper')
		));
		$this->addElement($element);

		$element = new Zend_Form_Element_File('file_'.$number);
		$element->setDestination(APPLICATION_PATH.'/../public'.$options['upload']['cache'])
		->setAttribs(array('class' => 'file'))
		->setRequired(FALSE)
		->setAllowEmpty(TRUE)
		->addValidators(array(
		array('Extension', true, array(false, 'jpg,jpeg')),
		//not working on production server
		//array('MimeType', true, array(false, 'image')),
		array('Size', false, array('max' => $appSettings->max_file_size)),
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
	}


}