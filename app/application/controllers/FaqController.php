<?php

class FaqController extends Zefir_Controller_Action
{

    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
		$faqs = new Application_Model_Faqs();
		$this->view->faqs = $faqs->getFaqs();
    }
    
    public function editAction()
    {
    	$request = $this->getRequest();
    
    	if ($request->isPost())
    	{
    		$data = $request->getPost();
    		if (isset($data['leave']))
    		{
    			$this->flashMe('cancel_edit', 'SUCCESS');
    			$this->_redirect('/faq');
    		}
    		else 
    		{
    			$new_questions = $this->_countNewQuestions();
    			$form = new Application_Form_Faq($new_questions);
				$form = $this->_addNewValidators($form, $new_questions);
				if ($form->isValid($request->getPost()))
    			{
    				foreach ($form->getValues() as $id => $questionData)
		    		{
		    			if (!strstr($id, 'new_question_'))
		    			{//process existing questions
		    				$question = new Application_Model_Faqs();
		    				$question->populateFromForm($questionData);
		    				if (isset($questionData['question_remove']) && 
		    					$questionData['question_remove'] == '1')
		    					$question->delete();
		    				else
    							$question->save();
		    			}
		    			else
		    			{//add new question
		    				$question = new Application_Model_Faqs();
		    				$question->populateFromForm($questionData);
		    				
		    				if ($question->_faq_question != null 
		    					&& $question->_faq_answer != null)
		    				{
    							$question->save();
		    				}
		    			}
		    		}
    				$this->flashMe('faq_saved');
		    		$this->_redirect('/faq');	
    			}
    		}
    	}
    	else 
    	{
    		$form = new Application_Form_Faq();
    	}
    	
    	$this->view->form = $form;
    
    }
    
    
	public function deleteAction()
    {
    	$lang = new Zend_Session_Namespace('lang');
    	$faq = new Application_Model_Faqs();
    	$faq->deleteFaq($lang->_lang);
    }
    
 	protected function _countNewQuestions()
    {
    	$data = $this->getRequest()->getPost();
    	for($i = 1; isset($data['new_question_'.$i]); $i++);
    	
    	return --$i; 
    }
    
	protected function _addNewValidators($form, $count)
    {
    	$data = $this->getRequest()->getPost();
    	
    	for($i = 1; $i <= $count; $i++)
    	{
    		$faq_question = $data['new_question_'.$i]['faq_question'];
    		$faq_answer = $data['new_question_'.$i]['faq_answer'];
    		
    		if ($faq_question != null || $faq_answer != null)
    		{
    			$form->getSubform('new_question_'.$i)->getElement('faq_question')->setRequired(TRUE);
    			$form->getSubform('new_question_'.$i)->getElement('faq_answer')->setRequired(TRUE);
    		}
    		else
    		{
    			$form->getSubform('new_question_'.$i)->getElement('faq_question')->setRequired(FALSE);
    			$form->getSubform('new_question_'.$i)->getElement('faq_answer')->setRequired(FALSE);
    		}
    	}
    	
    	return $form;
    }


}

