<?php

class PressController extends Zefir_Controller_Action
{

	public function init()
	{
		parent::init();
	}

	public function indexAction()
	{
		$appSettings = Zend_Registry::get('appSettings');
		$press = new Application_Model_Press();
		$this->view->press = $press->getAllAsType($appSettings->current_edition);
	}
	
	public function newAction()
	{
		$form = new Application_Form_Press();
		
		if ($this->_request->isPost())
		{
			if ($this->_request->getPost('leave', false))
			{
				$this->flashMe('cancel_edit', 'SUCCESS');
				$this->_redirectToRoute(array(), 'press');
			}
		}
		$this->view->form = $form;
	}


}

