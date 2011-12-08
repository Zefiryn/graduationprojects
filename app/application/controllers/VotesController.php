<?php

class VotesController extends Zefir_Controller_Action
{
	public function init()
	{
		parent::init();
	}
	
	public function settingsAction()
	{
		$jurors = new Application_Model_Jurors();
		$stages = new Application_Model_Stages();
		$form = new Application_Form_VoteSettings();
		
		$this->view->form = $form;
		$this->view->jurors = $jurors->fetchAll();
		$this->view->stages = $stages->fetchAll();
	}
	
	public function newstageAction()
	{
		$form = new Application_Form_Stage();
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
				$stage = new Application_Model_Stages();
				$stage->populateFromForm($form->getValues());
				$stage->save();
				$this->flashMe('stage_added');
				$this->_redirectToRoute(array(), 'vote_settings');
			}
		}
		$this->view->form = $form;
	}
}