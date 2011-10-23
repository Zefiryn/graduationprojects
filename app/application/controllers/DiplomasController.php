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
    	$cur_edition = $this->getRequest()->getParam('edition', array_shift($editions));
		$selected_edition = str_replace('-', '/', $cur_edition);
		
		$edition->getEdition($selected_edition, TRUE);
		
		$this->view->diplomas = $edition->diplomas;
		$this->view->path = array(
			0 => array('route' => 'root', 'data' => array(), 'name' => array('main_page')),
    		1 => array('route' => 'diplomas', 'data' => array('edition' => $cur_edition), 'name' => array('edition', $cur_edition)),
		);
    }
    
	public function showAction()
    {
    	$request = $this->getRequest();
    	$id = $request->getParam('id', null);
    	$diploma = new Application_Model_Diplomas($id);
    	$this->view->diploma = $diploma;
    	$this->view->adjacent = $diploma->getAdjacentDiplomas();
    	
    	$this->view->path = array(
			0 => array('route' => 'root', 'data' => array(), 'name' => array('main_page')),
    		1 => array('route' => 'diplomas', 'data' => array('edition' => $diploma->edition->edition_name2), 'name' => array('edition', $diploma->edition->edition_name2)),
    		2 => array('route' => 'show_diploma', 'data' => array('id' => $diploma->diploma_id), 'name' => array($diploma->getAuthorName())),
		);
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

