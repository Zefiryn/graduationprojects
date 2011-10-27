<?php

class FaqController extends Zefir_Controller_Action
{

	protected $_faq;
	
	protected function _getFaq($refresh = FALSE)
	{
		if ($this->_faq == null || $refresh)
		{
			$lang = new Application_Model_Languages();
			$lang->findLang($this->view->lang);
			$this->_faq = $lang->faq;
		}
		
		return $this->_faq;
	}
    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
    	$faqs = $this->_getFaq();
    	
    	$columns = array('left' => array(), 'right' => array());
		foreach($faqs as $i => $faq_section)
		{
			if ($i < count($faqs)/2)
				$columns['left'][$i] = $faq_section;
			else 
				$columns['right'][$i] = $faq_section;
				
				
		}
		
		$this->view->columns = $columns;
		$this->view->path = array(
    		0 => array('route' => 'root', 'data' => array(), 'name' => array('main_page')),
    		1 => array('route' => 'faq', 'data' => array(), 'name' => array('faq_link')),
    	);
    	
		if ($this->view->user->role == 'admin' || $this->view->user->role == 'juror')
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
    	$questionPosition = $request->getParam('position', 1);
		
    	$position = 1;
    	foreach ($this->_getFaq() as $id => $question)
    	{ 
    		if ($question->faq_id != $moveId && $position < $questionPosition)
    		{ 
    			$question->position = $position ;
    			$question->save();
    		}
    		elseif ($question->faq_id != $moveId && $position  > $questionPosition)
    		{
    				$question->position = $position + 1;
    				$question->save();
    		}
    		elseif ($question->faq_id != $moveId && $position  == $questionPosition)
    		{
    				$question->position = $position + 1;
    				$question->save();
    		}
    		elseif ($question->faq_id == $moveId)
    		{
    			$question->position = $questionPosition;
    			$question->save();
    			$position--;	//reduce position so next questions would fill the place
    		} 
    		$position++;
    	}

    	$this->flashMe('faq_sorted');
    	$this->_redirectToRoute(array(), 'faq');
    }


}

