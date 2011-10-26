<?php

class SchoolsController extends Zefir_Controller_Action
{

    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
		$school = new Application_Model_Schools();
		$schools = $school->getSchools();
		unset($schools[0]);
		$this->view->schools = $schools;
    }

    public function newAction()
    {
    	$request = $this->getRequest();
		$form = new Application_Form_School();
		
		
    	if ($request->isPost())
		{
			if ($request->getParam('leave'))
    		{
    			$this->flashMe('cancel_edit');
				$this->_redirectToRoute(array(), 'schools');
    		}
			
    		elseif ($form->isValid($request->getPost()))
    		{
    			$school = new Application_Model_Schools();
    			$school->populateFromForm($form->getValues());
    			$school->save();
    			
    			$this->flashMe('school_edited', 'SUCCESS');
    			$this->_redirect('/schools');
    		}
		}
		
		$this->view->form = $form;
    }

    public function editAction()
    {
    	$request = $this->getRequest();
		$form = new Application_Form_School();
				
		if ($request->isPost())
		{
			if ($request->getParam('leave'))
    		{
    			$this->flashMe('cancel_edit');
				$this->_redirectToRoute(array(), 'schools');
    		}
			
    		elseif ($form->isValid($request->getPost()))
    		{
    			$school = new Application_Model_Schools();
    			$school->populateFromForm($form->getValues());
    			$school->save();
    			
    			$this->flashMe('school_edited', 'SUCCESS');
    			$this->_redirect('/schools');
    		}
		}
		else
		{
			$id = $request->getParam('id', '');
			$school = new Application_Model_Schools($id);
			$form->populate($school->prepareFormArray());
			
		}
		
		$this->view->form = $form;
    }
    
    public function deleteAction()
    {
    	$request = $this->getRequest();
		$id = $request->getParam('id', '');

		$school = new Application_Model_Schools($id);
		$school->delete();
		$this->flashMe('school_deleted', 'SUCCESS');
		$this->_redirect('schools');
    }

}







