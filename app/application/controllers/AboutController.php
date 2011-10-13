<?php

class AboutController extends Zefir_Controller_Action
{
	protected function _getAbout()
	{		
		$lang = new Application_Model_Languages();
    	$lang->findLang($this->view->lang);
    	if (array_key_exists(0, $lang->about))
    		$about = $lang->about[0];
    	else 
    	{
    		$about = new Application_Model_About();
    		$about->lang_id = $lang->lang_id;
    	}
    	
    	return $about;
	}
	
	
    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
    	$about = $this->_getAbout();
    	
    	$this->view->about = $about;
		$this->view->about_text = explode("\n", $about->about_text);
		
    }
    
    public function newAction()
    {
    	
    }
    public function editAction()
    {
    	$form = new Application_Form_About();
    	
    	$request = $this->getRequest();
    	
    	$about = $this->_getAbout();
    	
    	if ($request->isPost())
    	{
    		if ($form->isValid($request->getPost()))
    		{
    			$about->populateFromForm($form->getValues());
    			$about->save();
    			
    			$this->flashMe('item_saved', 'SUCCESS');
    			$this->_redirect('/about');
    		}    		
    	}
    	else
    	{
    		$form->populate($about->toArray());
    	}
    	
    	$this->view->form = $form;
    }


}

