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
			$form->populate(array('juror_id' => $request->getParam('id')));
		}
		
		$this->view->form = $form;
	}
	
	
}