<?php

class JurorsController extends Zefir_Controller_Action
{
	public function init()
	{
		parent::init();
	}
	
	public function newAction()
	{
		$form = new Application_Form_Juror();
		$request = $this->getRequest();
		if ($request->isPost())
		{
			if($request->getParam('leave', null))
			{
				$this->flashMe('cancel_edit');
				$this->_redirectToRoute(array(), 'vote_settings');
			}
			if ($form->isValid($request->getParams()))
			{
				$juror = new Application_Model_Jurors();
				$juror->populateFromForm($form->getValues());
				$juror->save();
				$this->flashMe('juror_added');
				$this->_redirectToRoute(array(), 'vote_settings');
			}
		}
		$this->view->form = $form;
	}
	
	public function editAction()
	{
		$form = new Application_Form_Juror();
		$request = $this->getRequest();
		
		if ($request->isPost())
		{
			if($request->getParam('leave', null))
			{
				$this->flashMe('cancel_edit');
				$this->_redirectToRoute(array(), 'vote_settings');
			}
			if ($form->isValid($request->getParams()))
			{
				$juror = new Application_Model_Jurors();
				$juror->populateFromForm($form->getValues());
				$juror->save();
				$this->flashMe('juror_added');
				$this->_redirectToRoute(array(), 'vote_settings');
			}
		}
		else 
		{
			$id = $request->getParam('id');
			$juror = new Application_Model_Jurors($id);
			$form->populate($juror->toArray());
		}
		$this->view->form = $form;
	}
	
	public function addAction()
	{
		$form = new Application_Form_Juror_User();
		$request = $this->getRequest();
		
		if ($request->isPost())
		{
			if($request->getParam('leave', null))
			{
				$this->flashMe('cancel_edit');
				$this->_redirectToRoute(array(), 'vote_settings');
			}
			if ($form->isValid($request->getParams()))
			{
				$id = $form->getElement('juror_id')->getValue();
				foreach ($form->getElement('users')->getValue() as $user_id)
				{
					$user = new Application_Model_Users($user_id);
					$user->juror_id = $id;
					$user->save();
				}
				$this->flashMe('juror_added');
				$this->_redirectToRoute(array(), 'vote_settings');
			}
		}
		else
		{
			$juror_id = $request->getParam('id');
			$juror = new Application_Model_Jurors($juror_id);
			$form->populate($juror->prepareFormArray());
		}
		
		$this->view->form = $form;
	}
	
	public function deleteAction()
	{
		$request = $this->getRequest();
		$id = $request->getParam('id', '');
	
		$juror = new Application_Model_Jurors($id);
		$juror->delete();
		if (!$request->isXMLHttpRequest())
		{
			$this->flashMe('juror_deleted', 'SUCCESS');
			$this->_redirectToRoute(array(), 'vote_settings');
		}
		else
		{
			$this->_helper->layout()->disableLayout();
			$this->_helper->viewRenderer->setNoRender(true);
			echo Zend_Json::encode(array('link' => $this->view->url(array(), 'vote_settings')));
		}
	}
	
	public function removeAction()
	{
		$request = $this->getRequest();
		$user_id = $request->getParam('user_id');
		$juror = new Application_Model_Jurors($request->getParam('id'));
		
		foreach($juror->users as $user)
		{
			if ($user->user_id == $user_id)
			{
				$user->juror_id = null;
				$user->save();
			}
		}
		
		if (!$request->isXMLHttpRequest())
		{
			$this->flashMe('user_removed_from_juror', 'SUCCESS');
			$this->_redirectToRoute(array(), 'vote_settings');
		}
		else 
		{
			$this->_helper->layout()->disableLayout();
			$this->_helper->viewRenderer->setNoRender(true);
			echo Zend_Json::encode(array('link' => $this->view->url(array(), 'vote_settings')));
		}
	}
	
	
}