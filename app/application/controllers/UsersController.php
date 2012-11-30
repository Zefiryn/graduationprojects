<?php

class UsersController extends Zefir_Controller_Action
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
		$user = new Application_Model_Users();
		$users = $user->getUsers();
		$this->view->users = $this->sortByRole($users);
	}

	public function showAction()
	{
		$id = $this->getRequest()->getParam('id', '');
		$user = new Application_Model_Users($id);

		if ($this->view->user->_role == 'admin'
		|| $this->view->user->user_id == $user->user_id)
		{
			$this->view->account = $user;
		}
		else
		{
			$this->flashMe('not_allowed', 'FAILURE');
			$this->_redirect('index');
		}
	}

	public function newAction()
	{
		$request = $this->getRequest();
		 
		$form = new Application_Form_User('new');
		 
		if ($request->isPost())
		{
			if($request->getParam('leave'))
			{
				$this->flashMe('cancel_edit');
				$this->_redirectToRoute(array(), 'users');
			}
				
			elseif ($form->isValid($request->getPost()))
			{
				$user = new Application_Model_Users();
				$user->populateFromForm($form->getValues());
				$user->setUserRole($form->getElement('role')->getValue());
				$user->save();
				 
				$this->flashMe('user_added', 'SUCCESS');
				$this->_redirectToRoute(array(), 'users');
			}

		}
		 
		$this->view->form = $form;
	}

	public function editAction()
	{
		$request = $this->getRequest();
		$role = Zend_Registry::get('role');
		 
		$form = new Application_Form_User();
		 
		if ($role != 'admin')
		$form->removeElement('role');

		if ($request->isPost())
		{
			$form->populate($request->getPost());

			if($form->leave->isChecked())
			{
				$this->flashMe('cancel_edit');
				$this->_redirectToRoute(array(), 'users');
			}
				
			elseif ($form->isValid($request->getPost()))
			{
				$user = new Application_Model_Users();
				$user->populateFromForm($form->getValues());
				if ($role == 'admin')
				$user->setUserRole($form->getElement('role')->getValue());
				 
				$user->save();
				 
				$this->flashMe('user_edited', 'SUCCESS');
				$this->_redirectToRoute(array('id'=>$user->user_id), 'user');
			}

		}
		else
		{
			$id = $request->getParam('id', '');
			$user = new Application_Model_Users($id);
			$form->populate($user->prepareFormArray());
		}
		 
		$this->view->form = $form;
	}

	public function promoteAction()
	{
		// action body
	}

	public function deleteAction()
	{
		$request = $this->getRequest();
		$id = $request->getParam('id', '');
		$user = new Application_Model_Users($id);
		$user->delete();
		if ($request->isXMLHttpRequest())
		{
			echo $this->_helper->json(array('element_id' => $id));
		}
		else 
		{
			$this->flashMe('user_deleted', 'SUCCESS');
			$this->_redirect('users');
		}
	}

	public function restoreAction()
	{
		// action body
	}
	
	public function resetJurorAction()
	{
		//reset juror
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$request = $this->getRequest();	
		
		if ($request->isXMLHttpRequest())
		{
			$id = $request->getParam('user_id');
			$user = new Application_Model_Users($id);
			$user->juror_id = null;
			$user->save();
			$this->_helper->json(array('success' => TRUE));
		}
		else
		{
			$this->flashMe('ajax_only', 'FAILURE');
			$this->_redirectToRoute(array(), 'root');
		}
	}
	
	public function assignJurorAction()
	{
		//reset juror
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$request = $this->getRequest();
	
		if ($request->isXMLHttpRequest())
		{
			$user_id = $request->getParam('user_id');
			$juror_id = $request->getParam('juror_id');
			
			try {
				$user = new Application_Model_Users($user_id);
				$user->juror_id = $juror_id;
				$user->save();
				$this->_helper->json(array('success' => TRUE));
			}
			catch (Zend_Exception $e) {
				$this->_helper->json(array('fail' => $e->getMessage()));
				
			}
		}
		else
		{
			$this->flashMe('ajax_only', 'FAILURE');
			$this->_redirectToRoute(array(), 'root');
		}
	}

	private function sortByRole($users)
	{
		foreach ($users as $user)
		{
			$sorted[$user->_role][] = $user;
		}

		return $sorted;
	}

	

}
