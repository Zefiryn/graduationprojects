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
    			$new_paragraphs = $this->_countNewParagraphs();
    			$form = new Application_Form_Regulations($new_paragraphs);
				$form = $this->_addNewValidators($form, $new_paragraphs);
    			if ($form->isValid($request->getPost()))
    			{
		    		foreach ($form->getValues() as $id => $paragraphData)
		    		{
		    			if (!strstr($id, 'new_paragraph_'))
		    			{//process existing paragraphs
		    				$paragraph = new Application_Model_Regualtions();
		    				$paragraph->populateFromForm($paragraphData);
		    				
		    				if (isset($paragraphData['paragraph_remove']) && 
		    					$paragraphData['paragraph_remove'] == '1')
		    					$paragraph->delete();
		    				else
		    					$paragraph->save();
		    			}
		    			else
		    			{//add new paragraphs
		    				$paragraph = new Application_Model_Regualtions();
		    				$paragraph->populateFromForm($paragraphData);
		    				
		    				if ($paragraph->_paragraph_no != null)
	    						$paragraph->save();
		    			}
		    		}
		    		
		    		$this->flashMe('regulations_saved');
		    		$this->_redirect('/regulations');
    			}
    		}	
    	}
    	else
    		$form = new Application_Form_Regulations();
    		
    		
    	$this->view->regulations = $form;
    }
    
    public function deleteAction()
    {
    	$edition = Zend_Registry::get('edition');
    	$regulation = new Application_Model_Regualtions();
    	$regulation->deleteRegulations($edition);
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

