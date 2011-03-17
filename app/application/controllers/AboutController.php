<?php

class AboutController extends Zefir_Controller_Action
{

    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
    	$about = $this->view->translations[$this->view->lang]['about_text'];
		$this->view->about = explode("\n", $about);
    }
    
    public function editAction()
    {
    	$form = new Application_Form_About();
    	$request = $this->getRequest();
    	
    	if ($request->isPost())
    	{
    		
    		if ($form->isValid($request->getPost()))
    		{
    			$record = new Application_Model_Localizations();
    			$record->populateFromForm($form->getValues());
    			$record->save();
    			
    			$this->flashMe('item_saved', 'SUCCESS');
    			$this->_redirect('/about');
    		}
    		
    		
    	}
    	else
    	{
    		$about_text = trim($this->view->translations[$this->view->lang]['about_text']);
    		$form->getElement('text')->setValue($about_text);
    	}
    	
    	$this->view->form = $form;
    }


}

