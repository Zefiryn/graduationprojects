<?php

class StagesController extends Zefir_Controller_Action
{
	public function init()
	{
		parent::init();
	}
	
	public function newAction()
	{
		$form = new Application_Form_Stage();
		$request = $this->getRequest();
		if ($request->isPost())
		{
			if ($form->isValid($request->getParams()))
			{
				$stage = new Application_Model_Stages();
				$stage->populateFromForm($form->getValues());
				$stage->save();
				$this->flashMe('stage_added');
				$this->_redirectToRoute(array(), 'vote_settings');
			}
		}
		$this->view->form = $form;
	}
	
	public function editAction()
	{
		$form = new Application_Form_Stage();
		$request = $this->getRequest();
		
		if ($request->isPost())
		{
			if ($form->isValid($request->getParams()))
			{
				$stage = new Application_Model_Stages();
				$stage->populateFromForm($form->getValues());
				$stage->save();
				$this->flashMe('stage_added');
				$this->_redirectToRoute(array(), 'vote_settings');
			}
		}
		else 
		{
			$id = $request->getParam('id');
			$stage = new Application_Model_Stages($id);
			$form->populate($stage->toArray());
		}
		$this->view->form = $form;
	}
	
	
}