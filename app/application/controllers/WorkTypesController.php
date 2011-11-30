<?php

class WorkTypesController extends Zefir_Controller_Action
{

	public function init()
	{
		parent::init();
	}

	public function indexAction()
	{
		$work_type = new Application_Model_WorkTypes();
		$types = $work_type->getWorkTypes();
		unset($types[0]);
		$this->view->work_types = $types;
	}

	public function newAction()
	{
		$request = $this->getRequest();
		$form = new Application_Form_WorkType();

		if ($request->isPost())
		{
			$form->populate($request->getPost());
				
			if($form->leave->isChecked())
			{
				$this->_redirect('/schools');
			}
				
			elseif ($form->isValid($request->getPost()))
			{
				$type = new Application_Model_WorkTypes();
				$type->populateFromForm($form->getValues());
				$type->save();
				 
				$this->flashMe('type_added', 'SUCCESS');
				$this->_redirect('/work-types');
			}
		}
		else
		{
			$id = $request->getParam('id', '');
			$type = new Application_Model_WorkTypes($id);
			$form->populate($type->prepareFormArray());
				
		}

		$this->view->form = $form;
	}

	public function editAction()
	{
		$request = $this->getRequest();
		$form = new Application_Form_WorkType();

		if ($request->isPost())
		{
			$form->populate($request->getPost());
				
			if($form->leave->isChecked())
			{
				$this->_redirect('/schools');
			}
				
			elseif ($form->isValid($request->getPost()))
			{
				$type = new Application_Model_WorkTypes();
				$type->populateFromForm($form->getValues());
				$type->save();
				 
				$this->flashMe('type_edited', 'SUCCESS');
				$this->_redirect('/work-types');
			}
		}
		else
		{
			$id = $request->getParam('id', '');
			$type = new Application_Model_WorkTypes($id);
			$form->populate($type->prepareFormArray());
				
		}

		$this->view->form = $form;
	}

	public function deleteAction()
	{
		$request = $this->getRequest();
		$id = $request->getParam('id', '');

		$type = new Application_Model_WorkTypes($id);
		$type->delete();
		$this->flashMe('work_type_deleted', 'SUCCESS');
		$this->_redirect('work-types');
	}

}







