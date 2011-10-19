<?php

class DiplomasController extends Zefir_Controller_Action
{

    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
		$edition = new Application_Model_Editions();
    	$editions = $edition->getEditions('DESC');
		$selected_edition = str_replace('-', '/', $this->getRequest()->getParam('edition', array_shift($editions)));
		
		$edition->getEdition($selected_edition, TRUE);
		
		$this->view->diplomas = $edition->diplomas;
    }
    
	public function showAction()
    {
    	$request = $this->getRequest();
    	$id = $request->getParam('id', null);
    	$diploma = new Application_Model_Diplomas($id);
    	$this->view->diploma = $diploma;
    }
    
    
	public function newAction()
    {
		
    }
    
	public function editAction()
    {
    	
    }
    
    public function deleteAction()
    {
    	
    }


}

