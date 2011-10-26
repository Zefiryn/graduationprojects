<?php

class CAptionsController extends Zefir_Controller_Action
{

    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
		$request = $this->getRequest();
		$lang = $request->getParam('loc_lang', 'pl');
		
		$caption = new Application_Model_Captions();
		$this->view->captions = $caption->fetchAll($caption->order('name ASC'));
		$this->view->caption_lang = $lang;
    }


    public function newAction() 
    {
    	$request = $this->getRequest();
    	$form = new Application_Form_Caption();
    	
    	if ($request->isPost())
    	{
    		if ($request->getParam('leave'))
    		{
    			$this->flashMe('cancel_edit');
				$this->_redirectToRoute(array(), 'captions');
    		}
    		
    		if ($form->isValid($request->getPost()))
    		{
    			$caption = new Application_Model_Captions();
    			$caption->populateFromForm($form->getValues());
    			$caption->save();
    			
    			$this->flashMe('caption_added');
				$this->_redirectToRoute(array(), 'captions');
    		}
    	}
    	
    	$this->view->form = $form;
    }
    
	public function editAction()
    {
    	$request = $this->getRequest();
    	$id = $request->getParam('id', null);
    	$form = new Application_Form_Caption();
    	
    	if ($request->isPost())
    	{
    		if ($request->getParam('leave'))
    		{
    			$this->flashMe('cancel_edit');
				$this->_redirectToRoute(array(), 'captions');
    		}
    		
    		if ($form->isValid($request->getPost()))
    		{
    			$caption = new Application_Model_Captions();
    			$caption->populateFromForm($form->getValues());
    			$caption->save();
    			
    			$this->flashMe('caption_added');
				$this->_redirectToRoute(array(), 'captions');
    		}
    	}
    	else 
    	{
    		if ($id)
    		{
    			$caption = new Application_Model_Captions($id);
    			$form->populate($caption->prepareFormArray());
    		}
    	}
    	
    	$this->view->form = $form;
    }
    
    public function deleteAction()
    {
    	$request = $this->getRequest();
		$id = $request->getParam('id', '');

		$caption = new Application_Model_Captions($id);
		$caption->delete();
		$this->flashMe('caption_deleted', 'SUCCESS');
		$this->_redirectToRoute(array(), 'captions');

    }
}

