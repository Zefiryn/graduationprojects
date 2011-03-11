<?php

class RegulationsController extends Zefir_Controller_Action
{

    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
		$this->showAction();
    }


	public function showAction()
    {
		$regulations = new Application_Model_Regualtions();
		$edition = Zend_Registry::get('edition');
		
		$this->view->regulations = $regulations->getRegulations($edition);
		$this->_helper->viewRenderer('index');
    }
    
    public function editAction()
    {   	
    	$request = $this->getRequest();
    	
    	$edition = Zend_Registry::get('edition');
    	$regulations = new Application_Model_Regualtions();
        $regulationsData = $regulations->getRegulations($edition);
        $form = new Application_Form_Regulations();
    	
    	if ($request->isPost())
    	{
    		$data = $request->getPost();
    		if (isset($data['leave']))
    		{
    			$this->flashMe('cancel_edit', 'SUCCESS');
    			$this->_redirect('/regulations');
    		}
    		else 
    		{
	    		//get number of new paragraphs 
	    		for($i=1; isset($data['new_paragraph_no_'.$i]); $i++);
	    		$i--;
	    		
	    		$form = new Application_Form_Regulations($i);
	    		
	    		//add validators to new fields
	    		for($x=1; $x <= $i; $x++)
	    		{
	    			if ($data['new_paragraph_text_'.$x] != NULL)
	    				$form->getElement('new_paragraph_no_'.$x)->setRequired(TRUE);
	    		}
	    		
	    		//validate form
	    		if ($form->isValid($data))
	    		{
	    			//save form
	    			$regulations = new Application_Model_Regualtions();
	    			$regulations->saveRegulations($form->getValues());
	    			
	    			$this->flashMe('regulation_edited', 'SUCCESS');
	    			$this->_redirectToRoute(array('edition' => str_replace('/', '-', $edition)), 'regulation_edition');
	    		}
	    		else 
	    		{
	    			$this->view->regulations = $form;
	    		}
    		}	
    	}
    	else
    	{
    		$this->view->regulations = $form;
    	}
    }
}

