<?php
class Zefir_Form extends Zend_Form
{
	/**
	 * vars for validators regex
	 * @var array
	 */
	protected $_regex = array(
		'L' => 'a-zA-ZÁáČčĎďÉéĚěÍíŇňÓóŘřŠšŤťÚúŮůÝýŽžÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåæçèéêëíìíîïñòóôõöùúûüýÿĂĄăąĆćĈĉĊċČčĎĕĘęĚěĜĝĞğĠġĢģĤĥĴĵĶķĸĹĺĻļŁłŃŅńņŇňŎŏŔŕŖŗŘřŚśŜŝŞşŠšŢţŤťŨũŬŭŮŰůűŲųŴŵŶŸŷýŹźŻżŽž',
		'N' => '0-9',
		'S' => '&\.\-_;,":!?\(\)\/\'@ ',
		'E' => '\s\n\r',
		'B' => '\[\]~\/#='
	);
	
	/**
	 * default decorators
	 * @var array
	 */
	protected $_decoratorsStandard = array(
				array('ViewHelper'),
				array('ErrorMsg'),
				array('Description', array('tag' => 'p', 'class' => 'label', 'placement' => 'prepend'))
				
	);
	
	/**
	 * default bib decorators
	 * @var array
	 */
	protected $_bibDecoratorsStandard = array(
				array('TextField'),
				array('ErrorMsg'),
				array('Description', array('tag' => 'p', 'class' => 'label', 'placement' => 'prepend'))
				
	);
	
	/**
	 * default decorators for radio fields
	 * @var array
	 */
	protected $_decoratorsRadio = array(
				array('ViewHelper'),
				array('Description', array('tag' => 'p', 'class' => 'radio_label', 'placement' => 'prepend'))
				
	);
	
	public function init()
	{
		$this->addElementPrefixPath('Bib_Decorator', 'Bib/Form/Decorator', 'decorator');
		$this->addPrefixPath('Bib_Decorator', 'Bib/Form/Decorator', 'decorator');
	}
	
	protected function _getStandardDecorators($bib = TRUE)
	{
		if ($bib)
			return $this->_bibDecoratorsStandard;
		else
			return $this->_decoratorsStandard;
	}
	
	
	public function getRadioDecorators()
	{
		return $this->_decoratorsRadio;
	}
	
	
	protected function _createStandardSubmit($submit_label)
	{
		$this->addElement('hash', 'csrf', array(
			'ignore' => true,
			'decorators' => array(	array('ViewHelper'),
									array('ErrorMsg'))	
		));		
		
		$submit = $this->createElement('submit', 'leave', array(
			'ignore' => true,
			'label' => 'Zrezygnuj',
			'class' => 'unprefered'
		));	 
		$submit->setDecorators(array(
							array('ViewHelper')
            				));
     	$this->addElement($submit);


		$submit = $this->createElement('submit', 'submit', array(
			'ignore' => true,
			'label' => $submit_label,
			'class' => 'prefered'
		));	 
		$submit->setDecorators(array(
							array('ViewHelper')
            				));
            				
      	$this->addElement($submit);
	}
}