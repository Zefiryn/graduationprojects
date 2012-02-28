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
		$this->view->press = $press->getAllAsType();
	}
	
	public function newAction()
	{
		$form = new Application_Form_Press();
		$form->setDecorators(array(
				array('ViewScript', array('viewScript' => 'forms/_pressForm.phtml'))
		));
		
		if ($this->_request->isPost())
		{
			if ($this->_request->getPost('leave', false))
			{
				$this->flashMe('cancel_edit', 'SUCCESS');
				$this->_redirectToRoute(array(), 'press');
			}
			elseif ($form->isValid($this->_request->getPost()) || count($form->getMessages()) == 0)
			{
				$press_element = new Application_Model_Press();
				$press_element->populate($form->getValues());
				$press_element->element_path = $this->_saveFile($press_element->getUploadDir(), $form->getElement('element_path')->getDestination());
				$press_element->save();
			}
		}
		$this->view->form = $form;
	}
	 
	public function descriptionAction()
	{
		$form = new Application_Form_Press_Description();
		
		$press = new Application_Model_Press();
		$press->findDescription();
		if ($this->_request->isPost())
		{
			if ($this->_request->getPost('leave', false))
			{
				$this->flashMe('cancel_edit', 'SUCCESS');
				$this->_redirectToRoute(array(), 'press');
			}
		}
		else 
		{
			$form->populate($press->prepareDescriptionFormArray());
		}
		
		$this->view->form = $form;
	}


	protected function _saveFile($folder, $cache)
	{
		$element_files = array();
		foreach($_FILES as $file)
		{
			$filename = ($file['name']);
			$cache .= '/'.$filename;
			if (rename($cache, $folder.'/'.$filename)) 
			{
				$element_files[] = $filename;	
			}
			
		}
		return $element_files;
	}
}