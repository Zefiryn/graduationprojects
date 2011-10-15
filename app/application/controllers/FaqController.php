<?php

class FaqController extends Zefir_Controller_Action
{

	protected function _getFaq()
	{
		$lang = new Application_Model_Languages();
		$lang->findLang($this->view->lang);
		
		return $lang->faq;
	}
    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
    	$faqs = $this->_getFaq();
		$count = count($faqs)/2;
		
		foreach($faqs as $i => $faq)
		{
			if ($i < $count)
				$faqs_left[$i] = $faq;
			else
				$faqs_right[$i] = $faq;
		}
		
		$this->view->faq_left = $faqs_left;
		$this->view->faq_right = $faqs_right;
		
		if ($this->view->user->role == 'admin')
			$this->render('index');
		else
			 $this->render('show');
    }
	
    public function newAction()
    {
    	$request = $this->getRequest();
    	$form = new Application_Form_Faq_Question();
    	
    	if ($request->isPost())
    	{
    		$data = $request->getPost();
    		if (isset($data['leave']))
    		{
    			$this->flashMe('cancel_edit', 'SUCCESS');
    			$this->_redirectToRoute(array('lang' => $this->view->lang), 'faq');
    		}
    		else 
    		{
    			if ($form->isValid($request->getPost()))
				{
					$question = new Application_Model_Faqs();
					$question->populateFromForm($form->getValues());
					
					$lang = new Application_Model_Languages();
					$lang->findLang($this->view->lang);
					$question->lang_id = $lang->lang_id;
					
					$question->positionLast();
					
					$question->save();
					$this->flashMe('question_saved');
					$this->_redirectToRoute(array(), 'faq');
				}
    		}
    	}
    	
    	$this->view->form = $form;
    	
    }
    
    public function editAction()
    {
    	$request = $this->getRequest();
    	$form = new Application_Form_Faq_Question();
    	
    	if ($request->isPost())
    	{
    		$data = $request->getPost();
    		if (isset($data['leave']))
    		{
    			$this->flashMe('cancel_edit', 'SUCCESS');
    			$this->_redirectToRoute(array('lang' => $this->view->lang), 'faq');
    		}
    		else 
    		{
    			if ($form->isValid($request->getPost()))
				{
					
	    			//process existing paragraphs
					$question = new Application_Model_Faqs();
					$question->populateFromForm($form->getValues());
	
					$question->save();
					$this->flashMe('paragraph_saved');
					$this->_redirectToRoute(array(), 'faq');
				}				
    		}	
    	}
    	else
    	{
    		$id = $request->getParam('id', null);
    		$question = new Application_Model_Faqs($id);
    		$form->populate($question->toArray());
    	}	
    	$this->view->form = $form;
    }
    
    
	public function deleteAction()
    {
    	$request = $this->getRequest();
    	$id = $request->getParam('id', null);
    	if ($id) 
    	{
    		$i = 1;
    		foreach($this->_getFaq() as $faq)
    		{
    			if ($faq->faq_id == $id)
	    			$faq->delete();
	    		else
	    		{
	    			$faq->position = $i;
	    			$faq->save();
	    			$i++;
	    		}
    		}
    		$this->flashMe('question_deleted');
    		$this->_redirectToRoute(array(), 'faq');
    	} 
    }
    
	public function sortAction()
    {
    	$request = $this->getRequest();
    	$moveId = $request->getParam('move_id', null);
    	$behindId = $request->getParam('behind_id', null);
		
    	$sortedQuestion = array();
    	foreach ($this->_getFaq() as $id => $question)
    	{ 
    		$sortedQuestion[$question->faq_id] = $question;
    	}
    	$move = $sortedQuestion[$moveId];
    	unset($sortedQuestion[$moveId]);
    	$i = 1;
    	
    	//move to the first position
    	if ($behindId == 0)
    	{
    		$move->position = $i;
    		$move->save();
    		$i++;
    	}
    	
    	foreach($sortedQuestion as $faq_id => $q)
	    {
    		$q->position = $i;
    		$q->save();
    		$i++;
    		
    		if ($faq_id == $behindId)
    		{
    			$move->position = $i;
    			$move->save();
    			$i++;
    		}
    	}
	    

    	//$this->flashMe('regulation_sorted');
    	$this->_redirectToRoute(array(), 'faq');
    	
    }


}

