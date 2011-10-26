<?php

class UsersController extends Zefir_Controller_Action
{

    public function init()
    {
        parent::init();
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
    	$form->setAction('/users/edit');
    	if ($role != 'admin')
    		$form->removeElement('role'); 
		
    	if ($request->isPost())
    	{
    		$form->populate($request->getPost());
    		
			if($form->leave->isChecked())
			{
				$this->_redirect('/users');	
			}
			
    		elseif ($form->isValid($request->getPost()))
    		{
    			$user = new Application_Model_Users();
    			$user->populateFromForm($form->getValues());
    			$user->save();
    			
    			$this->flashMe('user_edited', 'SUCCESS');
    			$this->_redirectToRoute(array('id'=>$user->_user_id), 'user');
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
		$this->flashMe('user_deleted', 'SUCCESS');
		$this->_redirect('users');
    }

    public function restoreAction()
    {
        // action body
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
