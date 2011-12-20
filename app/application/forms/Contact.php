<?php
/**
 * @package Application_Form_Login
 */
/**
 * Declaration of the contact form
 * @author zefiryn
 * @since Feb 2011
 */

class Application_Form_Contact extends Zefir_Form
{

	/**
	 * Inital; set fields, decorators and validators
	 * @access public
	 * @return void
	 */
	public function init()
	{
		parent::init();

		$this->setMethod('post');
		$this->setName('PageForm');
		$this->setAction('/contact/send');
		$this->setTranslator(Zend_Registry::get('Zend_Translate'));

		$name = $this->createElement('text', 'name');
		$name->setAttribs(array('size'=> 55,
								'maxlength' => 16,
								'class' => 'width1'))
				->setLabel('contact_name')
				->setDecorators($this->_getZefirDecorators())
				->addValidators(array(
					new Zend_Validate_Regex('/^['.$this->_regex['L'].'\- ]+$/')
				));
		$this->addElement($name);

		$name = $this->createElement('text', 'email');
		$name->setAttribs(array('size'=> 55,
								'maxlength' => 16,
								'class' => 'width1'))
				->setLabel('contact_email')
				->setRequired(true)
				->setDecorators($this->_getZefirDecorators())
				->addValidators(array(
					new Zend_Validate_EmailAddress()
				));
		$this->addElement($name);

		$name = $this->createElement('text', 'mail_title');
		$name->setAttribs(array('size'=> 55,
								'maxlength' => 16,
								'class' => 'width1'))
				->setLabel('mail_title')
				->setRequired(true)
				->setDecorators($this->_getZefirDecorators())
				->addValidators(array(
					new Zend_Validate_Regex('/^['.$this->_regex['L'].$this->_regex['N'].$this->_regex['S'].' ]+$/')
				));
		$this->addElement($name);

		$mail_text = $this->createElement('textarea', 'mail_text');
		$mail_text->setAttribs(array(	'id' =>'mail_text',
										'cols' => 'auto',
										'rows' => 'auto'))
				->setLabel('mail_text')
				->setdecorators($this->_getZefirDecorators())
				->setRequired(TRUE)
				->addValidators(array(
					new Zend_Validate_NotEmpty(),
					new Zend_Validate_StringLength(array('min' => 20, 'max' => 5000))
				));
		$this->addElement($mail_text);

		$this->addElement('captcha', 'captcha', array(
						'label' => 'captcha',
						'required'   => true,
						'captcha'    => array(
								'captcha' => 'Image',
								'wordLen' => 5,
								'timeout' => 300,
								'font' => APPLICATION_PATH . '/../public/img/verdana.ttf',
								'fontSize' => 30,
								'imgDir' => APPLICATION_PATH . '/../public/assets/captcha',
								'imgUrl' => '/assets/captcha/',
							),
						'decorators' =>  array(
									array('Captcha', array('placement' => 'prepend', 'separator' => '<br />')), 
									array('MyLabel', array('placement' => 'prepend')),
									array('ErrorMsg', array('image' => FALSE)))
					));
		
		$this->addElement('hash', 'csrf', array(
			'ignore' => true,
			'decorators' => array(	array('ViewHelper'),
		array('ErrorMsg'))
		));

		$submit = $this->createElement('submit', 'submit', array(
			'ignore' => true,
			'label' => 'contact_submit',
			'attribs' => array('class' => 'submit')
		));
		$submit->setDecorators(array(
		array('TextField'),
		array('HtmlTag', array('tag' => 'p', 'class' => 'center'))
		));
		$this->addElement($submit);
	}


}

