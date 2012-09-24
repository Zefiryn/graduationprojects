<?php

class PartnersController extends Zefir_Controller_Action
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
		$request = $this->getRequest();

		$partner = new Application_Model_Partners();
		$this->view->partners = $partner->fetchAll();
	}


	public function newAction()
	{
		$request = $this->getRequest();
		$form = new Application_Form_Partner();
		 
		if ($request->isPost())
		{
			if ($request->getParam('leave'))
			{
				$this->flashMe('cancel_edit');
				$this->_redirectToRoute(array(), 'partners');
			}
			$cached = $this->_checkFileCache('new');
			$valid = $form->isValid($this->getRequest()->getPost());
			if ($valid || count($form->getErrorMessages()) == 0)
			{
				if (!$form->getElement('partner_file')->hasErrors()) {
					$this->_handleFiles($form, $cached);
					$partner = new Application_Model_Partners();
					$partner->populateFromForm($form->getValues());
					$partner->save();
					$this->flashMe('partner_added');
					$this->_redirectToRoute(array(), 'partners');
				}
			}
		}
		 
		$this->view->form = $form;
	}

	public function editAction()
	{
		$request = $this->getRequest();
		$form = new Application_Form_Partner();
		$form->getElement('partner_file')->setRequired(false);
		$id = $request->getParam('id', null);
		 
		if ($request->isPost())
		{
			if ($request->getParam('leave'))
			{
				$this->flashMe('cancel_edit');
				$this->_redirectToRoute(array(), 'partners');
			}
			$cached = $this->_checkFileCache('edit');
			$valid = $form->isValid($this->getRequest()->getPost());
			if ($valid || count($form->getErrorMessages()) == 0)
			{
				if (!$form->getElement('partner_file')->hasErrors()) {
					$this->_handleFiles($form, $cached);
					$partner = new Application_Model_Partners();
					$partner->populateFromForm($form->getValues());
					$partner->save();
					$this->flashMe('partner_saved');
					$this->_redirectToRoute(array(), 'partners');
				}
			}
		}
		else
		{
			if ($id)
			{
				$partner = new Application_Model_Partners($id);
				$form->populate($partner->prepareFormArray());
			}
		}
		 
		$this->view->form = $form;
	}

	public function deleteAction()
	{
		$request = $this->getRequest();
		$id = $request->getParam('id', '');

		$partner = new Application_Model_Partners($id);
		$partner->delete();
		$data = array(0 => $this->view->url(array(), 'partners'));
		if ($request->isXMLHttpRequest())
		{
			$this->_helper->json($data);
		}
		else
		{
			$this->flashMe('partner_deleted');
			$this->_redirectToRoute(array(), 'partners');
		}

	}
	
	protected function _checkFileCache($type = 'new')
	{
		$params = $this->getRequest()->getParams();
		$options = Zend_Registry::get('options');
		$cached = FALSE;
			
		$fileCache = isset($params['partner_fileCache']) ? $params['partner_fileCache'] : null;
	
		if ($type == 'new')
		{
			$file = APPLICATION_PATH.'/../public'.$options['upload']['cache'].'/'.$fileCache;
		}
		else
		{
			$file = APPLICATION_PATH.'/../public'.$options['upload']['partners'].'/'.$fileCache;
		}
	
		if ($fileCache != null && file_exists($file))
		{
			$cached = TRUE;
		}
	
		return $cached;
	}
	
	protected function _handleFiles($form, $cached, $type = 'new')
	{
		$options  = Zend_Registry::get('options');
		$newFile = FALSE;
		$form = $this->_cacheFile($options['upload']['cache'], $form, 'partner_file');
		if ($form->getElement('partner_fileCache')->getValue() != null)
		{
			$newFile = TRUE;
		}
	
		if (!$cached && !$newFile)
		{
			//add error to the first file field as there are no cached nor new uploaded files
			$form->getElement('partner_file')->addError($this->view->translate('fileUploadErrorNoFile'));
		}
	
		return $form;
	}
}