<?php

class ContactController extends Zefir_Controller_Action
{

	public function init()
	{
		parent::init();
		$this->view->css = array(
				'simple/forms.css'
		);
	}

	public function indexAction()
	{
		$form = new Application_Form_Contact();
		$form->setDecorators(array(
		array('ViewScript', array('viewScript' => 'forms/_contactForm.phtml'))
		));
		$this->view->form = $form;
	}

	public function sendAction()
	{
		$request = $this->getRequest();

		$form = new Application_Form_Contact();
		$form->setDecorators(array(
		array('ViewScript', array('viewScript' => 'forms/_contactForm.phtml'))
		));
		$this->view->form = $form;
		 
		if ($request->isPost())
		{
			if ($form->isValid($request->getPost()))
			{
				$options = Zend_Registry::get('options');
				 
				$mail = new Zend_Mail('UTF-8');
				$mail->setBodyText($form->getValue('mail_text'). "\n\n\n".$form->getValue('email'));
				$mail->setFrom($form->getValue('email'), $form->getValue('name'));
				$mail->addTo($options['mail']['to'], 'Graduation Projects');
				$mail->setSubject($form->getValue('mail_title'));

				try {
					$mail->send();
					if (FALSE)
					throw new Zend_Exception('test');
				} catch (Exception $e){
					$this->view->error = $e->getMessage();
				}
			}
			else
			{
				$this->_helper->viewRenderer('index');
			}
		}
		else
		{
			$this->_helper->viewRenderer('index');
		}
	}


}



