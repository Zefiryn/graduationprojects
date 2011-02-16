<?php
/**
 * @package Application_Form_Login
 */
/**
 * Declaration of the login form
 * @author zefiryn
 * @since Feb 2011
 */

class Application_Form_Login extends Zefir_Form
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
		$this->setAction('/auth/login');

		$login = $this->createElement('text', 'nick');
		$login->setAttribs(array('size'=> 55,
								'maxlength' => 16,
								'class' => 'normal'))
				->setDescription('Użytkownik')
				->setLabel('user')
				->setRequired(true)
				->addValidator(new Zefir_Validate_Login())
				->setDecorators(array(
					array('TextField'),
					array('ErrorMsg')
				));	
		$this->addElement($login);		  
		
		$password = $this->createElement('password', 'password');
		$password->setAttribs(array('size'=> 55,
								'maxlength' => 16,
								'class' => 'normal'))
				->setDescription('Hasło')
				->setLabel('password')
				->setRequired(true)
				->addValidator(new Zefir_Validate_Password())
				->setDecorators(array(
					array('PasswordField'),
					array('ErrorMsg')
				));	
		$this->addElement($password);					   
		
		$this->addElement('hash', 'csrf', array(
			'ignore' => true,
			'decorators' => array(	array('ViewHelper'),
									array('Errors'))	
		));		 

		$submit = $this->createElement('submit', 'submit', array(
			'ignore' => true,
			'label' => 'login_submit',
			'attribs' => array('class' => 'submit')
		));		 
		$submit->setDecorators(array(
							array('TextField'),
							array('HtmlTag', array('tag' => 'p', 'class' => 'center'))
            				));
      $this->addElement($submit);
	}


}