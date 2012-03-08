<?php

class StagesController extends Zefir_Controller_Action
{
	public function init()
	{
		parent::init();
		$this->view->css = array(
				'simple/forms.css'
		);
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
			$desc = $this->view->translate('previous_stage_max').': '.$stage->countMaxQualificationScore();
			$form->getElement('qualification_score')->setDescription($desc);
		}
		$this->view->form = $form;
	}
	
	public function deleteAction()
	{
		$request = $this->getRequest();
		$id = $request->getParam('id', '');
		
		$stage = new Application_Model_Stages($id);
		$stage->delete();
		$this->flashMe('stage_deleted', 'SUCCESS');
		$this->_redirectToRoute(array(), 'vote_settings');
	}
	
	public function blockAction()
	{
		$request = $this->getRequest();
		$id = $request->getParam('id', '');
		$stage = new Application_Model_Stages($id);
		$stage->block();
		$this->flashMe('stage_blocked', 'SUCCESS');
		$this->_redirectToRoute(array(), 'vote_settings');
	}
	
	public function activateAction()
	{
		$request = $this->getRequest();
		$id = $request->getParam('id', '');
		$stage = new Application_Model_Stages($id);
		$stage->activate();
		$this->flashMe('stage_activated', 'SUCCESS');
		$this->_redirectToRoute(array(), 'vote_settings');
	}
	
	
}