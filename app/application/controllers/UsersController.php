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
		$user = new Application_Model_Users();
		
		$this->view->user = $user->getUser($id);
    }

    public function newAction()
    {
        $request = $this->getRequest();
    	
    	$form = new Application_Form_User('new');
    	$form->setAction('/users/new');
    	$form->setDecorators(array(
			array('ViewScript', array('viewScript' => 'forms/_userForm.phtml'))
		));
		
    	if ($request->isPost())
    	{
    		$form->populate($request->getPost());
			
			if($form->leave->isChecked())
			{
				$this->_redirect('/admin');	
			}
			
    		elseif ($form->isValid($request->getPost()))
    		{
    			$user = new Application_Model_Users();
    			$user->populateFromForm($form->getValues());
    			$user->save();
    			
    			$this->flashMe('user_added', 'SUCCESS');
    			$this->_redirect('/users');
    		}	
    		
    	}
    	
    	$this->view->form = $form;   
    }

    public function editAction()
    {
    	$request = $this->getRequest();
    	
    	$form = new Application_Form_User('edit');
    	$form->setAction('/users/edit');
    	$form->setDecorators(array(
			array('ViewScript', array('viewScript' => 'forms/_userForm.phtml'))
		));
		
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
    		$user = new Application_Model_Users();
    		$user->getUser($id);
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
        // action body
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
