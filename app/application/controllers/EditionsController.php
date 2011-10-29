<?php

class EditionsController extends Zefir_Controller_Action
{

    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
		$edition = new Application_Model_Editions();
		$this->view->editions = $edition->getEditions();
    }
    
	public function showAction()
    {
    	$edition = new Application_Model_Editions();
		$this->view->editions = $edition->getEditions();
		$this->render('index');
    }
    
    
	public function newAction()
    {
		$request = $this->getRequest();
		$form = new Application_Form_Edition('new');
		$form->setAction($this->view->baseUrl('editions/new'));
	
		
		if ($request->isPost())
		{
			$form->populate($request->getPost());
			if($form->leave->isChecked())
			{
				$this->flashMe('adding_left', 'SUCCESS');
				$this->_redirect('/editions');
			}
			
    		elseif ($form->isValid($request->getPost()))
    		{
    			$edition = new Application_Model_Editions();
    			$edition->populate($form->getValues());
    			$edition->save();
    			
    			$this->flashMe('edition_edited', 'SUCCESS');
    			$this->_redirect('/editions');
    		}
		}

		$this->view->form = $form;
    }
    
	public function editAction()
    {
    	$request = $this->getRequest();
    	$id = $request->getParam('id', '');
		$edition = new Application_Model_Editions($id);
		$form = new Application_Form_Edition('edit');
		$form->setAction($this->view->baseUrl('editions/edit'));
		
		
		if ($request->isPost())
		{
			$form->populate($request->getPost());
			if($form->leave->isChecked())
			{
				$this->flashMe('edition_left', 'SUCCESS');
				$this->_redirect('/editions');	
			}
			
    		elseif ($form->isValid($request->getPost()))
    		{
    			$edition->populate($form->getValues());
    			$edition->save();
    			
    			$this->flashMe('edition_edited', 'SUCCESS');
    			$this->_redirect('/editions');
    		}
		}
		else
		{
			$form->populate($edition->prepareFormArray());
		}
		$this->view->form = $form;
    }
    
    public function deleteAction()
    {
    	$request = $this->getRequest();
		$id = $request->getParam('id', '');

		$edition = new Application_Model_Editions($id);
		$edition->delete();
		$this->flashMe('edition_deleted', 'SUCCESS');
		$this->_redirect('editions');
    }


}

