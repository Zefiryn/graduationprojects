<?php

class RegulationsController extends Zefir_Controller_Action
{
	protected function _getRegulations()
	{
		$lang = new Application_Model_Languages();
		$lang->findLang($this->view->lang);
		
		return $lang->regulations;
	}
	
    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
    	$regulations = $this->_getRegulations();
		
    	$columns = array('left' => array(), 'right' => array());
    	foreach($regulations as $i => $regulation)
		{
			if ($i < count($regulations)/2)
				$columns['left'][$i] = $regulation;
			else 
				$columns['right'][$i] = $regulation;	
		}
		
		
		$this->view->columns = $columns;
		$this->view->path = array(
    		0 => array('route' => 'root', 'data' => array(), 'name' => 'main_page'),
    		1 => array('route' => 'regulation', 'data' => array(), 'name' => 'regulation_link'),
    	);
		
		if ($this->view->user->role == 'admin')
			$this->render('index');
		else
			 $this->render('show');
		
    }

    public function newAction()
    {
    	$request = $this->getRequest();
    	$form = new Application_Form_Regulations_Paragraph();
    	
    	if ($request->isPost())
    	{
    		$data = $request->getPost();
    		if (isset($data['leave']))
    		{
    			$this->flashMe('cancel_edit', 'SUCCESS');
    			$this->_redirectToRoute(array('lang' => $this->view->lang), 'regulation');
    		}
    		else 
    		{
    			if ($form->isValid($request->getPost()))
				{
					$paragraph = new Application_Model_Regualtions();
					$paragraph->populateFromForm($form->getValues());
					
					$lang = new Application_Model_Languages();
					$lang->findLang($this->view->lang);
					$paragraph->lang_id = $lang->lang_id;
					
					$paragraph->positionLast();
					
					$paragraph->save();
					$this->flashMe('paragraph_saved');
					$this->_redirectToRoute(array(), 'regulation');
				}
    		}
    	}
    	
    	$this->view->regulations = $form;
    	
    }
    
	public function editAction()
    {   	
    	$request = $this->getRequest();
    	$form = new Application_Form_Regulations_Paragraph();
    	
    	if ($request->isPost())
    	{
    		$data = $request->getPost();
    		if (isset($data['leave']))
    		{
    			$this->flashMe('cancel_edit', 'SUCCESS');
    			$this->_redirectToRoute(array('lang' => $this->view->lang), 'regulation');
    		}
    		else 
    		{
    			if ($form->isValid($request->getPost()))
				{
					$paragraph = new Application_Model_Regualtions();
					$paragraph->populateFromForm($form->getValues());
	
					$paragraph->save();
					$this->flashMe('paragraph_saved');
					$this->_redirectToRoute(array(), 'regulation');
				}				
    		}	
    	}
    	else
    	{
    		$id = $request->getParam('id', null);
    		$paragraph = new Application_Model_Regualtions($id);
    		$form->populate($paragraph->toArray());
    	}	
    	$this->view->regulations = $form;
    }
    
    public function deleteAction()
    {
    	$request = $this->getRequest();
    	$id = $request->getParam('id', null);
    	if ($id) 
    	{
    		$i = 1;
    		foreach($this->_getRegulations() as $paragraph)
    		{
    			if ($paragraph->paragraph_id == $id)
	    			$paragraph->delete();
	    		else
	    		{
	    			$paragraph->paragraph_no = $i;
	    			$paragraph->save();
	    			$i++;
	    		}
    		}
    		$this->flashMe('paragraph_deleted');
    		$this->_redirectToRoute(array(), 'regulation');
    	} 
    }
    
    public function sortAction()
    {
    	$request = $this->getRequest();
    	$moveId = $request->getParam('move_id', null);
    	$behindId = $request->getParam('behind_id', null);
		
    	$sortedParagraphs = array();
    	foreach ($this->_getRegulations() as $id => $paragraph)
    	{ 
    		$sortedParagraphs[$paragraph->paragraph_id] = $paragraph;
    	}
    	$move = $sortedParagraphs[$moveId];
    	unset($sortedParagraphs[$moveId]);
    	$i = 1;
    	
    	//move to the first position
    	if ($behindId == 0)
    	{
    		$move->paragraph_no = $i;
    		$move->save();
    		$i++;
    	}
    	foreach($sortedParagraphs as $pid => $par)
    	{
    		$par->paragraph_no = $i;
    		$par->save();
    		$i++;
    		
    		if ($par->paragraph_id == $behindId)
    		{
    			$move->paragraph_no = $i;
    			$move->save();
    			$i++;
    		}
    	}

    	//$this->flashMe('regulation_sorted');
    	$this->_redirectToRoute(array(), 'regulation');
    	
    }
    
    protected function _countNewParagraphs()
    {
    	$data = $this->getRequest()->getPost();
    	for($i = 1; isset($data['new_paragraph_'.$i]); $i++);
    	
    	return --$i; 
    }
    
    protected function _addNewValidators($form, $count)
    {
    	$data = $this->getRequest()->getPost();
    	
    	for($i = 1; $i <= $count; $i++)
    	{
    		$par_no = $data['new_paragraph_'.$i]['paragraph_no'];
    		$par_text = $data['new_paragraph_'.$i]['paragraph_text'];
    		
    		if ($par_no != null || $par_text != null)
    		{
    			$form->getSubform('new_paragraph_'.$i)->getElement('paragraph_no')->setRequired(TRUE);
    			$form->getSubform('new_paragraph_'.$i)->getElement('paragraph_text')->setRequired(TRUE);
    		}
    	}
    	
    	return $form;
    }
}

